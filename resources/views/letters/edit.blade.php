@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto w-full pt-4">
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-bold text-gray-900 tracking-tight flex items-center justify-center gap-3">
            Perbarui Arsip
            <span class="text-xs font-mono font-medium bg-gray-100 text-gray-700 px-3 py-1 rounded-full border border-gray-200 mt-1">{{ $letter->letter_no }}</span>
        </h2>
        <p class="text-gray-500 mt-3 text-sm">Sesuaikan kembali metadata dari fisik yang Anda pegang dengan rekapan digital.</p>
    </div>

    <form method="POST" action="{{ route('letters.update', $letter) }}" class="bg-white rounded-[2rem] p-8 sm:p-12 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
        @method('PUT')
        @include('letters._form', ['letter' => $letter, 'statuses' => $statuses])
    </form>
</div>
@endsection
