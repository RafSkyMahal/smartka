<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private array $plans = [
        'premium' => [
            'name'       => 'SMARTKA Premium',
            'price'      => 79000,
            'price_year' => 699000,
        ],
        'premium_plus' => [
            'name'       => 'SMARTKA Premium Plus',
            'price'      => 129000,
            'price_year' => 1199000,
        ],
    ];

    // ── Inisialisasi Midtrans ─────────────────────────────
    private function initMidtrans(): void
    {
        \Midtrans\Config::$serverKey    = config('services.midtrans.server_key');
        \Midtrans\Config::$clientKey    = config('services.midtrans.client_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized  = config('services.midtrans.is_sanitized');
        \Midtrans\Config::$is3ds        = config('services.midtrans.is_3ds');
    }

    // ── Halaman Checkout ──────────────────────────────────
    public function checkout(string $plan)
    {
        if (!array_key_exists($plan, $this->plans)) {
            return redirect()->route('premium')->with('error', 'Paket tidak valid.');
        }

        /** @var \App\Models\User $user */
        $user      = Auth::user();
        $planData  = $this->plans[$plan];
        $clientKey = config('services.midtrans.client_key');

        return view('premium.checkout', compact('plan', 'planData', 'user', 'clientKey'));
    }

    // ── Proses Pembayaran (Buat Snap Token) ───────────────
    public function process(Request $request)
    {
        $request->validate([
            'plan'       => 'required|in:premium,premium_plus',
            'period'     => 'required|in:monthly,yearly',
            'promo_code' => 'nullable|string|max:20',
        ]);

        /** @var \App\Models\User $user */
        $user     = Auth::user();
        $plan     = $request->plan;
        $period   = $request->period;
        $planData = $this->plans[$plan];

        // Hitung harga
        $amount = $period === 'yearly'
            ? $planData['price_year']
            : $planData['price'];

        // Diskon promo
        if ($request->promo_code === 'SMARTKA10') {
            $amount = $amount - (int) ($amount * 0.10);
        }

        // Buat record payment
        $payment = Payment::create([
            'user_id'        => $user->id,
            'plan'           => $plan,
            'amount'         => $amount,
            'payment_method' => $period, // monthly | yearly
            'status'         => 'pending',
        ]);

        $orderId = 'SMARTKA-' . $payment->id . '-' . time();

        // Generate Midtrans Snap Token
        try {
            $this->initMidtrans();

            $snapToken = \Midtrans\Snap::getSnapToken([
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => (int) $amount,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email'      => $user->email,
                    'phone'      => $user->phone ?? '',
                ],
                'item_details' => [
                    [
                        'id'       => $plan . '_' . $period,
                        'price'    => (int) $amount,
                        'quantity' => 1,
                        'name'     => $planData['name'] . ' (' . ($period === 'yearly' ? 'Tahunan' : 'Bulanan') . ')',
                    ],
                ],
                'callbacks' => [
                    'finish' => route('payment.finish', $payment->id),
                ],
            ]);

            $payment->update(['gateway_transaction_id' => $orderId]);

            return response()->json([
                'snap_token' => $snapToken,
                'payment_id' => $payment->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans error: ' . $e->getMessage());

            // Fallback dev mode — aktifkan langsung dan balik ke dashboard
            if (app()->environment('local')) {
                $this->activateSubscription($payment);
                return response()->json([
                    'redirect' => route('dashboard') . '?payment=success',
                ]);
            }

            return response()->json(['error' => 'Gagal menghubungi Midtrans. Coba lagi.'], 500);
        }
    }

    // ── Finish Handler (setelah Snap berhasil) ────────────
    // Dipanggil via redirect dari onSuccess JS, BUKAN webhook.
    // Tugasnya: aktifkan subscription lalu redirect ke dashboard.
    public function finish(Request $request, $paymentId)
    {
        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $payment = Payment::find($paymentId);

        // Validasi payment milik user ini
        if (!$payment || (int) $payment->user_id !== (int) $user->id) {
            return redirect()->route('dashboard')
                ->with('error', 'Data pembayaran tidak ditemukan.');
        }

        // Aktivasi jika belum (webhook mungkin belum tiba)
        if ($payment->status === 'pending') {
            $transactionStatus = $request->query('transaction_status', 'settlement');

            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                $this->activateSubscription($payment, $request->query('transaction_id'));
            }
        }

        // Refresh user dari database agar status terbaru
        $payment->refresh();

        return redirect()->route('dashboard')
            ->with('premium_success', '🎉 Selamat! Akun kamu sudah aktif sebagai ' . ucwords(str_replace('_', ' ', $payment->plan)) . '!');
    }

    // ── Webhook Callback Midtrans ─────────────────────────
    public function callback(Request $request)
    {
        Log::info('Midtrans callback', $request->all());

        $this->initMidtrans();

        $notif = new \Midtrans\Notification();

        $orderId       = $notif->order_id;
        $statusCode    = $notif->status_code;
        $grossAmount   = $notif->gross_amount;
        $transactionId = $notif->transaction_id;
        $transStatus   = $notif->transaction_status;
        $fraudStatus   = $notif->fraud_status ?? null;

        // Verifikasi signature SHA-512
        $serverKey    = config('services.midtrans.server_key');
        $expectedSign = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($expectedSign !== $notif->signature_key) {
            Log::warning('Midtrans: Invalid signature for order ' . $orderId);
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        // Cari payment ID dari order_id (format: SMARTKA-{id}-{timestamp})
        $parts     = explode('-', $orderId);
        $paymentId = $parts[1] ?? null;
        $payment   = $paymentId ? Payment::find($paymentId) : null;

        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        // Proses status
        if ($transStatus === 'capture' && $fraudStatus === 'accept') {
            $this->activateSubscription($payment, $transactionId);
        } elseif ($transStatus === 'settlement') {
            $this->activateSubscription($payment, $transactionId);
        } elseif ($transStatus === 'pending') {
            $payment->update(['status' => 'pending']);
        } elseif (in_array($transStatus, ['deny', 'expire', 'cancel', 'failure'])) {
            $payment->update(['status' => 'failed']);
        }

        return response()->json(['ok' => true]);
    }

    // ── Helper: Aktivasi langganan ────────────────────────
    private function activateSubscription(Payment $payment, ?string $transactionId = null): void
    {
        // Jangan proses dua kali
        if ($payment->status === 'success') {
            return;
        }

        $months = ($payment->payment_method === 'yearly') ? 12 : 1;

        $payment->update([
            'status'                 => 'success',
            'paid_at'                => now(),
            'gateway_transaction_id' => $transactionId ?? $payment->gateway_transaction_id,
        ]);

        $payment->user->update([
            'subscription_status'  => $payment->plan,
            'subscription_ends_at' => now()->addMonths($months),
        ]);

        Subscription::updateOrCreate(
            ['user_id' => $payment->user_id, 'plan' => $payment->plan],
            [
                'start_date'     => now(),
                'end_date'       => now()->addMonths($months),
                'payment_status' => 'success',
                'amount'         => $payment->amount,
                'payment_method' => $payment->payment_method,
                'transaction_id' => $transactionId ?? $payment->gateway_transaction_id,
            ]
        );
    }
}