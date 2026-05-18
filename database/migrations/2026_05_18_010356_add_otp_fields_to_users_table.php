<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('otp_code')->nullable()->after('password');
        $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
        $table->string('role')->default('student')->after('otp_expires_at');
        $table->string('grade_level')->nullable()->after('role');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['otp_code', 'otp_expires_at', 'role', 'grade_level']);
    });
}
};
