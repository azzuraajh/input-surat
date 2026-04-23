@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto w-full pt-4">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <a href="{{ route('letters.index') }}" class="group inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Daftar
            </a>
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Rincian Dokumen</h2>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('letters.edit', $letter) }}" class="inline-flex items-center justify-center bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 px-5 py-2.5 rounded-full text-sm font-medium transition-all focus:outline-none focus:ring-4 focus:ring-gray-100">
                Lakukan Revisi
            </a>
            <form method="POST" action="{{ route('letters.destroy', $letter) }}" onsubmit="return confirm('Tindakan ini tidak bisa dibatalkan secara langsung. Yakin ingin membuang rekapan?')" class="m-0">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center justify-center bg-red-50 hover:bg-red-100 text-red-600 px-5 py-2.5 rounded-full text-sm font-medium transition-all focus:outline-none focus:ring-4 focus:ring-red-50">
                    Selesai / Buang
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] p-8 sm:p-12 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
        
        <div class="flex flex-col-reverse md:flex-row justify-between items-start gap-10 mb-12">
            <div class="w-full">
                @php
                    $statusStyles = [
                        'draft' => 'bg-gray-100 text-gray-700',
                        'sent' => 'bg-emerald-100 text-emerald-800',
                        'archived' => 'bg-amber-100 text-amber-800',
                    ];
                    $badgeStyle = $statusStyles[$letter->status] ?? 'bg-blue-100 text-blue-800';
                @endphp
                <div class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest {{ $badgeStyle }} mb-6">
                    Posisi: {{ $letter->status }}
                </div>
                
                <h3 class="text-3xl md:text-5xl tracking-tight font-extrabold text-gray-900 leading-tight mb-4 break-words">
                    {{ $letter->subject }}
                </h3>
                
                @if($letter->category)
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-gray-50 text-gray-500 rounded-lg text-sm border border-gray-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                        Sifat Surat: <span class="font-medium text-gray-700">{{ $letter->category }}</span>
                    </div>
                @endif
            </div>

            <div class="w-full md:w-auto md:min-w-[240px] text-left md:text-right flex-shrink-0 bg-gray-50/80 p-6 rounded-3xl border border-gray-100">
                <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase mb-1">Identifikasi</p>
                <p class="text-2xl font-mono text-gray-900 tracking-tight">{{ $letter->letter_no }}</p>
                <div class="w-full h-px bg-gray-200 my-4"></div>
                <p class="text-[11px] font-bold tracking-widest text-gray-400 uppercase mb-1">Diterbitkan Tgl</p>
                <p class="text-lg text-gray-900">{{ $letter->date->format('d F Y') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <div class="bg-gray-50/50 p-6 rounded-3xl border border-gray-100">
                <h4 class="text-xs font-bold tracking-widest text-gray-400 uppercase mb-4 flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-blue-400"></div> Dikirim Oleh
                </h4>
                <p class="text-xl text-gray-900 font-medium leading-relaxed">{{ $letter->sender }}</p>
            </div>
            
            <div class="bg-gray-50/50 p-6 rounded-3xl border border-gray-100">
                <h4 class="text-xs font-bold tracking-widest text-gray-400 uppercase mb-4 flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-purple-400"></div> Tujuan Utama
                </h4>
                <p class="text-xl text-gray-900 font-medium leading-relaxed">{{ $letter->recipient }}</p>
            </div>
        </div>

        @if($letter->notes)
            <div class="mt-10 pt-10 border-t border-gray-100">
                <h4 class="text-xs font-bold tracking-widest text-gray-400 uppercase mb-4">Catatan Laci / Instruksi Disposisi</h4>
                <div class="prose prose-sm prose-gray max-w-none text-gray-600 bg-yellow-50/50 p-6 rounded-3xl border border-yellow-100/60 leading-loose">
                    {!! nl2br(e($letter->notes)) !!}
                </div>
            </div>
        @endif

        <div class="mt-12 flex items-center justify-between text-[11px] text-gray-400 font-mono tracking-wider">
            <p>ENTRY: {{ $letter->created_at->format('Y-m-d H:i') }}</p>
            <p>UPD: {{ $letter->updated_at->format('Y-m-d H:i') }}</p>
        </div>

    </div>
</div>
@endsection
