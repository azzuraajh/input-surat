<div class="space-y-6">
    <section class="soft-panel rounded-[28px] p-5 sm:p-6">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <span class="section-kicker">Bagian 1</span>
                <h3 class="mt-2 text-2xl font-semibold text-[var(--color-ink)]">Identitas arsip</h3>
                <p class="mt-2 max-w-2xl text-sm leading-7 text-[var(--color-muted)]">
                    Pastikan informasi inti seperti kode klasifikasi, nomor arsip, dan jenis dokumen terisi dengan rapi.
                </p>
            </div>
            <span class="status-pill">Wajib: kode, nomor arsip, boks</span>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label class="form-label">Kode Klasifikasi <span class="text-[var(--color-danger)]">*</span></label>
                <input type="text" name="kode_klasifikasi" value="{{ old('kode_klasifikasi', $arsip->kode_klasifikasi) }}" list="kodeList" class="form-input" placeholder="Contoh: SET, KSA" required>
                <datalist id="kodeList">
                    @foreach($kodeList as $kode)
                        <option value="{{ $kode }}">
                    @endforeach
                </datalist>
                <p class="form-hint">Kode pengelompokan arsip yang dipakai sebagai identitas klasifikasi.</p>
                @error('kode_klasifikasi') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Nomor Arsip <span class="text-[var(--color-danger)]">*</span></label>
                <input type="text" name="nomor_arsip" value="{{ old('nomor_arsip', $arsip->nomor_arsip) }}" class="form-input font-mono" placeholder="Contoh: 16706" required>
                <p class="form-hint">Nomor unik utama untuk pencarian dan pembaruan data arsip.</p>
                @error('nomor_arsip') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Nomor Berkas</label>
                <input type="text" name="nomor_berkas" value="{{ old('nomor_berkas', $arsip->nomor_berkas) }}" class="form-input" placeholder="Contoh: SETKSDAE/24">
                @error('nomor_berkas') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Jenis Dokumen</label>
                <input type="text" name="uraian" value="{{ old('uraian', $arsip->uraian) }}" list="uraianList" class="form-input" placeholder="Contoh: Surat Tugas">
                <datalist id="uraianList">
                    @foreach($uraianList as $u)
                        <option value="{{ $u }}">
                    @endforeach
                </datalist>
                @error('uraian') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>

    <section class="soft-panel rounded-[28px] p-5 sm:p-6">
        <div class="mb-6">
            <span class="section-kicker">Bagian 2</span>
            <h3 class="mt-2 text-2xl font-semibold text-[var(--color-ink)]">Kandungan informasi</h3>
            <p class="mt-2 max-w-2xl text-sm leading-7 text-[var(--color-muted)]">
                Tuliskan deskripsi arsip secara cukup jelas agar pengguna lain bisa memahami isi berkas tanpa membuka dokumen fisik terlebih dahulu.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="form-label">Uraian Informasi Arsip</label>
                <textarea name="uraian_informasi" rows="4" class="form-textarea" placeholder="Tuliskan informasi lengkap mengenai arsip di sini...">{{ old('uraian_informasi', $arsip->uraian_informasi) }}</textarea>
                @error('uraian_informasi') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Kurun Waktu</label>
                <input type="text" name="kurun_waktu" value="{{ old('kurun_waktu', $arsip->kurun_waktu) }}" class="form-input font-mono" placeholder="Contoh: 2021">
                @error('kurun_waktu') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Tanggal Arsip</label>
                <input type="text" name="tanggal_arsip" value="{{ old('tanggal_arsip', $arsip->tanggal_arsip) }}" class="form-input" placeholder="Contoh: 11 Juni 2021">
                @error('tanggal_arsip') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>

    <section class="soft-panel rounded-[28px] p-5 sm:p-6">
        <div class="mb-6">
            <span class="section-kicker">Bagian 3</span>
            <h3 class="mt-2 text-2xl font-semibold text-[var(--color-ink)]">Fisik dan penyimpanan</h3>
            <p class="mt-2 max-w-2xl text-sm leading-7 text-[var(--color-muted)]">
                Detail lokasi dan status fisik diposisikan jelas supaya proses pencarian dokumen di rak berjalan lebih cepat.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label class="form-label">Jumlah Item</label>
                <input type="text" name="jumlah_item" value="{{ old('jumlah_item', $arsip->jumlah_item) }}" class="form-input" placeholder="Contoh: 3 lembar">
                @error('jumlah_item') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Tingkat Perkembangan</label>
                <input type="text" name="tingkat_perkembangan" value="{{ old('tingkat_perkembangan', $arsip->tingkat_perkembangan) }}" class="form-input" placeholder="Contoh: Konsep, Asli">
                @error('tingkat_perkembangan') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Keterangan Status</label>
                <select name="keterangan" class="form-select">
                    <option value="">Pilih Status</option>
                    <option value="Terbuka" @selected(old('keterangan', $arsip->keterangan) == 'Terbuka')>Terbuka</option>
                    <option value="Tertutup" @selected(old('keterangan', $arsip->keterangan) == 'Tertutup')>Tertutup</option>
                </select>
                @error('keterangan') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Lokasi Rak</label>
                <input type="text" name="lokasi" value="{{ old('lokasi', $arsip->lokasi) }}" class="form-input" placeholder="Contoh: Juanda 15, R 33">
                @error('lokasi') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Boks <span class="text-[var(--color-danger)]">*</span></label>
                <input type="text" name="boks" value="{{ old('boks', $arsip->boks) }}" class="form-input font-mono" placeholder="Contoh: 147" required>
                @error('boks') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>
</div>

<div class="page-shell flex flex-col gap-4 rounded-[28px] p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
    <div>
        <span class="section-kicker">Finalisasi</span>
        <p class="mt-2 text-lg font-semibold text-[var(--color-ink)]">Pastikan nomor arsip, status, dan box penyimpanan sudah sesuai.</p>
    </div>
    <div class="flex flex-col gap-3 sm:flex-row">
        <a href="{{ $backUrl ?? route('arsips.index') }}" class="btn-ghost">Batal</a>
        <button type="submit" class="btn-primary">
        Simpan Data Arsip
        </button>
    </div>
</div>
@csrf
