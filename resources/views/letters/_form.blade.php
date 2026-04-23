@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-7 pb-6">
    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Periode Diterbitkan <span class="text-red-400">*</span></label>
        <input type="date" name="date" value="{{ old('date', optional($letter->date)->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required class="w-full px-4 py-3 border-none bg-gray-50 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900 focus:bg-white transition-all text-sm text-gray-800">
        @error('date') <p class="mt-1.5 ml-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Posisi Arsip <span class="text-red-400">*</span></label>
        <div class="relative">
            <select name="status" required class="w-full px-4 py-3 border-none bg-gray-50 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900 focus:bg-white transition-all text-sm text-gray-800 appearance-none">
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status', $letter->status) === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </div>
        </div>
        @error('status') <p class="mt-1.5 ml-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Subjek Utama / Judul <span class="text-red-400">*</span></label>
        <input type="text" name="subject" value="{{ old('subject', $letter->subject) }}" required placeholder="Masukkan gambaran isi surat secukupnya..." class="w-full px-4 py-3 border-none bg-gray-50 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900 focus:bg-white transition-all text-sm text-gray-800">
        @error('subject') <p class="mt-1.5 ml-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2 my-2">
        <h3 class="text-lg font-semibold tracking-tight text-gray-900 mb-1">Entitas Bersangkutan</h3>
        <p class="text-sm text-gray-500">Tentukan asal kiriman dan tujuan yang spesifik.</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Dikirim Oleh <span class="text-red-400">*</span></label>
        <input type="text" name="sender" value="{{ old('sender', $letter->sender) }}" required placeholder="Contoh: Kementerian, Kepala Dinas, Andi..." class="w-full px-4 py-3 border-none bg-gray-50 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900 focus:bg-white transition-all text-sm text-gray-800">
        @error('sender') <p class="mt-1.5 ml-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Ditujukan Kepada <span class="text-red-400">*</span></label>
        <input type="text" name="recipient" value="{{ old('recipient', $letter->recipient) }}" required placeholder="Individu atau unit divisi..." class="w-full px-4 py-3 border-none bg-gray-50 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900 focus:bg-white transition-all text-sm text-gray-800">
        @error('recipient') <p class="mt-1.5 ml-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2 pt-2">
        <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Sifat & Tag <span class="text-gray-400 font-normal">(opsional)</span></label>
        <input type="text" name="category" value="{{ old('category', $letter->category) }}" placeholder="Rahasia, Tembusan, Prioritas..." class="w-full px-4 py-3 border-none bg-gray-50 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900 focus:bg-white transition-all text-sm text-gray-800">
        @error('category') <p class="mt-1.5 ml-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1.5 ml-1">Catatan Historis / Laci <span class="text-gray-400 font-normal">(opsional)</span></label>
        <textarea name="notes" rows="4" placeholder="Keterlambatan, posisi dokumen fisik dalam rak, instruksi lanjut..." class="w-full px-4 py-3 border-none bg-gray-50 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900 focus:bg-white transition-all text-sm text-gray-800 resize-y">{{ old('notes', $letter->notes) }}</textarea>
        @error('notes') <p class="mt-1.5 ml-1 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-6 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
    <a href="{{ route('letters.index') }}" class="px-6 py-3 text-sm font-medium text-gray-500 bg-transparent hover:text-gray-800 rounded-full transition-colors focus:outline-none">
        Kembali
    </a>
    <button type="submit" class="px-8 py-3 text-sm font-medium text-white bg-gray-900 rounded-full hover:bg-gray-800 transition-transform hover:scale-105 shadow-sm focus:outline-none focus:ring-4 focus:ring-gray-200">
        Simpan Arsip
    </button>
</div>
