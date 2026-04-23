@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Arsip Jendela Utama</h2>
        <p class="text-sm text-gray-500 mt-1">Daftar rekapitulasi surat secara terstruktur.</p>
    </div>
    <div class="flex-shrink-0">
        <a href="{{ route('letters.create') }}" class="group inline-flex items-center justify-center bg-gray-900 hover:bg-gray-800 text-white font-medium px-5 py-2.5 rounded-full text-[13px] transition-all shadow-sm hover:shadow-md focus:outline-none focus:ring-4 focus:ring-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Baris Data
        </a>
    </div>
</div>

{{-- Modern & Clean Control Strip --}}
<div class="mb-5 bg-white rounded-2xl border border-gray-100 shadow-sm p-2 flex flex-col md:flex-row gap-2 items-center text-[13px] relative z-10 w-full group">
    <form method="GET" class="w-full flex-grow flex flex-col sm:flex-row items-center gap-2 m-0">
        <div class="relative w-full md:flex-grow flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
            </div>
            <input type="text" name="q" placeholder="Cari sel..." value="{{ $filters['q'] }}" class="block w-full pl-9 pr-4 py-2 border-none bg-gray-50/50 rounded-xl text-[13px] focus:ring-2 focus:ring-gray-900 outline-none text-gray-800 placeholder-gray-400 transition-all">
        </div>
        
        <div class="flex items-center gap-2 w-full md:w-auto">
            <select name="status" class="block w-full md:w-36 px-4 py-2 border-none bg-gray-50/50 rounded-xl text-[13px] focus:ring-2 focus:ring-gray-900 outline-none text-gray-700 cursor-pointer transition-all">
                <option value="">Semua Status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected($filters['status'] === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            
            <div class="flex items-center border-none bg-gray-50/50 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-gray-900 transition-all w-full md:w-auto">
                <select name="sort" class="block w-full md:w-32 pl-4 pr-6 py-2 border-none bg-transparent text-[13px] focus:ring-0 outline-none text-gray-700 cursor-pointer">
                    @foreach(['date' => 'Kolom Tgl', 'letter_no' => 'No Surat', 'subject' => 'Perihal', 'created_at' => 'Waktu Entry'] as $key => $label)
                        <option value="{{ $key }}" @selected($filters['sort'] === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="w-px bg-gray-200 h-4"></div>
                <select name="direction" class="block w-auto pl-2 pr-4 py-2 border-none bg-transparent text-[13px] cursor-pointer focus:ring-0 outline-none text-gray-700">
                    <option value="desc" @selected($filters['direction'] === 'desc')>Terdahulu</option>
                    <option value="asc" @selected($filters['direction'] === 'asc')>Terbaru</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-[13px] font-medium transition-colors flex items-center justify-center whitespace-nowrap tooltip" title="Jalankan Query">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
            </button>
        </div>
    </form>
</div>

{{-- Modern SaaS Data Grid --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.02)] overflow-hidden w-full relative">
    <div class="overflow-x-auto overflow-y-auto max-h-[60vh]">
        <table class="w-full text-left whitespace-nowrap min-w-[900px] border-collapse">
            <thead class="bg-white/95 backdrop-blur-sm sticky top-0 z-10 shadow-[0_1px_0_theme(colors.gray.100)] text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                <tr>
                    <th scope="col" class="px-4 py-3 border-b border-gray-100 text-center w-12">#</th>
                    <th scope="col" class="px-4 py-3 border-b border-gray-100 font-bold w-[140px]">No. Arsip</th>
                    <th scope="col" class="px-4 py-3 border-b border-gray-100 font-bold w-[100px]">Tanggal</th>
                    <th scope="col" class="px-4 py-3 border-b border-gray-100 font-bold max-w-[250px]">Perihal Surat</th>
                    <th scope="col" class="px-4 py-3 border-b border-gray-100 font-bold w-[150px]">Pengirim</th>
                    <th scope="col" class="px-4 py-3 border-b border-gray-100 font-bold w-[150px]">Penerima</th>
                    <th scope="col" class="px-4 py-3 border-b border-gray-100 font-bold w-[100px]">Kategori</th>
                    <th scope="col" class="px-4 py-3 border-b border-gray-100 text-center font-bold w-[100px]">Status</th>
                    <th scope="col" class="px-4 py-3 border-b border-gray-100 text-center font-bold w-[90px] sticky right-0 bg-white shadow-[-1px_0_0_theme(colors.gray.100)] z-20">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-[13px] text-gray-700 divide-y divide-gray-50">
                @forelse($letters as $index => $letter)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="px-4 py-2.5 text-center text-gray-400 font-mono text-[11px] select-none">{{ $letters->firstItem() + $index }}</td>
                        
                        <td class="px-4 py-2.5 font-mono text-[12px] group-hover:text-blue-600 transition-colors cursor-pointer" title="{{ $letter->letter_no }}">
                            <a href="{{ route('letters.show', $letter) }}" class="hover:underline">{{ $letter->letter_no }}</a>
                        </td>
                        
                        <td class="px-4 py-2.5">
                            <span class="inline-flex items-center gap-1.5 bg-gray-50 px-2 py-0.5 rounded-md border border-gray-100/50 text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                {{ $letter->date->format('d/m/Y') }}
                            </span>
                        </td>
                        
                        <td class="px-4 py-2.5 truncate max-w-[250px] font-medium text-gray-900" title="{{ $letter->subject }}">
                            {{ $letter->subject }}
                        </td>
                        
                        <td class="px-4 py-2.5 truncate max-w-[150px]" title="{{ $letter->sender }}">
                            {{ $letter->sender }}
                        </td>
                        
                        <td class="px-4 py-2.5 truncate max-w-[150px]" title="{{ $letter->recipient }}">
                            {{ $letter->recipient }}
                        </td>
                        
                        <td class="px-4 py-2.5 truncate max-w-[100px] text-gray-500">
                            {{ $letter->category ?? '—' }}
                        </td>
                        
                        <td class="px-4 py-2.5 text-center">
                            @php
                                $statusStyles = [
                                    'draft' => 'bg-gray-100 text-gray-600',
                                    'sent' => 'bg-emerald-50 text-emerald-700',
                                    'archived' => 'bg-amber-50 text-amber-700',
                                ];
                                $style = $statusStyles[$letter->status] ?? 'bg-gray-50 text-gray-600';
                            @endphp
                            <span class="inline-flex w-full items-center justify-center px-1 py-[3px] rounded-md text-[10px] font-bold {{ $style }} uppercase tracking-wider border border-transparent">
                                {{ $letter->status }}
                            </span>
                        </td>
                        
                        <td class="px-2 py-1.5 text-center sticky right-0 bg-white group-hover:bg-gray-50/50 shadow-[-1px_0_0_theme(colors.gray.100)] transition-colors">
                            <div class="flex items-center justify-center gap-1 opacity-40 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('letters.edit', $letter) }}" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-200 rounded-lg outline-none transition-colors" title="Edit Baris">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </a>
                                <form method="POST" action="{{ route('letters.destroy', $letter) }}" onsubmit="return confirm('Hapus permanen baris data surat {{ $letter->letter_no }}?')" class="m-0 inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg outline-none transition-colors" title="Hapus Baris">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center bg-gray-50/50">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <svg class="h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <p class="text-sm font-medium">Sistem arsip kosong</p>
                                <p class="text-xs mt-1 text-gray-400">Belum ada dokumen yang dibukukan ke dalam database.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 flex items-center justify-between text-xs text-gray-500 pb-10">
    <div class="w-full">
        {{ $letters->links('pagination::tailwind') }}
    </div>
</div>
@endsection
