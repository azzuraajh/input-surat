@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto w-full pt-4">
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Catat Arsip Baru</h2>
        <p class="text-gray-500 mt-2 text-sm">Validasi dokumen dan simpan metadata surat ke dalam pangkalan data tersentralisasi.</p>
    </div>

    <form method="POST" action="{{ route('letters.store') }}" class="bg-white rounded-[2rem] p-8 sm:p-12 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
        @include('letters._form', ['letter' => $letter, 'statuses' => $statuses])
    </form>
</div>
@endsection
