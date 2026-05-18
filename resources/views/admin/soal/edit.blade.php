@extends('layouts.admin')

@section('title', 'Edit Soal')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.soal.index') }}" class="text-gray-400 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-xl font-bold text-white">Edit Soal</h1>
    </div>

    <form action="{{ route('admin.soal.update', $question) }}" method="POST"
          class="bg-gray-800 rounded-2xl p-6 space-y-5">
        @csrf
        @method('PUT')

        {{-- Mata Pelajaran --}}
        <div>
            <label class="block text-sm text-gray-400 mb-1">Mata Pelajaran</label>
            <select name="subject_id" class="w-full bg-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" @selected($question->subject_id == $subject->id)>
                        {{ $subject->name }} (Kelas {{ $subject->grade_level }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Topik --}}
        <div>
            <label class="block text-sm text-gray-400 mb-1">Topik</label>
            <input type="text" name="topic" value="{{ old('topic', $question->topic?->name) }}"
                   class="w-full bg-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                   placeholder="Contoh: Persamaan Linear">
        </div>

        {{-- Tipe --}}
        <div>
            <label class="block text-sm text-gray-400 mb-1">Tipe Soal</label>
            <select name="type" class="w-full bg-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="multiple_choice" @selected($question->type === 'multiple_choice')>Pilihan Ganda</option>
                <option value="essay" @selected($question->type === 'essay')>Esai</option>
            </select>
        </div>

        {{-- Pertanyaan --}}
        <div>
            <label class="block text-sm text-gray-400 mb-1">Pertanyaan</label>
            <textarea name="content" rows="4"
                      class="w-full bg-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                      placeholder="Tulis soal di sini...">{{ old('content', $question->content) }}</textarea>
        </div>

        {{-- Pilihan Jawaban --}}
        <div x-data="{ type: '{{ $question->type }}' }">
            <select name="type" x-model="type" class="hidden"></select>

            <div x-show="type === 'multiple_choice'" class="space-y-3">
                <label class="block text-sm text-gray-400">Pilihan Jawaban</label>
                @foreach(['A','B','C','D'] as $opt)
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 flex items-center justify-center bg-gray-700 rounded-lg text-sm font-bold text-gray-300">{{ $opt }}</span>
                        <input type="text" name="options[{{ $opt }}]"
                               value="{{ old('options.'.$opt, $question->options[$opt] ?? '') }}"
                               class="flex-1 bg-gray-700 text-white rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               placeholder="Opsi {{ $opt }}">
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Jawaban Benar --}}
        <div>
            <label class="block text-sm text-gray-400 mb-1">Jawaban Benar</label>
            <input type="text" name="correct_answer" value="{{ old('correct_answer', $question->correct_answer) }}"
                   class="w-full bg-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                   placeholder="A / B / C / D atau teks jawaban esai">
        </div>

        {{-- Pembahasan --}}
        <div>
            <label class="block text-sm text-gray-400 mb-1">Pembahasan (Opsional)</label>
            <textarea name="explanation" rows="3"
                      class="w-full bg-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                      placeholder="Penjelasan jawaban...">{{ old('explanation', $question->explanation) }}</textarea>
        </div>

        {{-- Tingkat Kesulitan --}}
        <div>
            <label class="block text-sm text-gray-400 mb-1">Tingkat Kesulitan</label>
            <select name="difficulty" class="w-full bg-gray-700 text-white rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="easy" @selected($question->difficulty === 'easy')>Mudah</option>
                <option value="medium" @selected($question->difficulty === 'medium')>Sedang</option>
                <option value="hard" @selected($question->difficulty === 'hard')>Sulit</option>
            </select>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-2.5 rounded-xl transition">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.soal.index') }}"
               class="flex-1 text-center bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2.5 rounded-xl transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection