<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('letters');

        Schema::create('arsips', function (Blueprint $table) {
            $table->id();
            $table->integer('no_urut')->nullable();
            $table->string('kode_klasifikasi')->nullable()->index();
            $table->string('nomor_arsip')->nullable()->unique();
            $table->string('nomor_berkas')->nullable();
            $table->string('uraian')->nullable()->index();        // jenis: Surat Tugas, Nota Dinas, dll
            $table->string('kurun_waktu', 20)->nullable()->index(); // tahun
            $table->text('uraian_informasi')->nullable();         // deskripsi lengkap
            $table->string('jumlah_item')->nullable();            // "3 Lembar", "1 Berkas"
            $table->string('tanggal_arsip')->nullable();          // tanggal sebagai string (format bervariasi)
            $table->string('tingkat_perkembangan')->nullable();   // Asli, Konsep, Copy
            $table->string('keterangan')->nullable();             // KKAA: Terbuka/Tertutup
            $table->string('lokasi')->nullable()->index();        // "Juanda 15, R 33"
            $table->string('boks')->nullable()->index();          // nomor boks
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['kode_klasifikasi', 'kurun_waktu']);
            $table->index(['boks', 'lokasi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsips');
    }
};
