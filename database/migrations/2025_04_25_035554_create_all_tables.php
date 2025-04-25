<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Pengguna
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('umur');
            $table->enum('gender', ['L', 'P']); // Bisa disesuaikan
            $table->timestamps();
        });

        // 2. Admin
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('nama');
            $table->string('password');
            $table->timestamps();
        });

        // 3. Status Gula
        Schema::create('status_gula', function (Blueprint $table) {
            $table->id();
            $table->enum('nama', ['low', 'normal', 'high']);
            $table->decimal('gulaMin', 5, 2);
            $table->decimal('gulaMax', 5, 2);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 4. Jurnal
        Schema::create('jurnal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penggunaID')->constrained('pengguna')->onDelete('cascade');
            $table->foreignId('statusGulaID')->constrained('status_gula')->onDelete('cascade');
            $table->enum('waktuMakan', ['pagi', 'siang', 'malam']);
            $table->decimal('totalGula', 6, 2);
            $table->date('date');
            $table->timestamps();
        });

        // 5. Berita
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('gambar')->nullable();
            $table->text('deskripsi');
            $table->string('sumber')->nullable();
            $table->string('penulis')->nullable();
            $table->date('tanggalterbit');
            $table->timestamps();
        });

        // 6. Resep Makanan
        Schema::create('resep_makanan', function (Blueprint $table) {
            $table->id();
            $table->decimal('kadarGula', 5, 2);
            $table->string('nama');
            $table->string('brand');
            $table->string('gambar')->nullable();
            $table->decimal('totalKalori', 6, 2);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 7. Resep Favorit (pivot)
        Schema::create('resep_favorit', function (Blueprint $table) {
            $table->foreignId('penggunaID')->constrained('pengguna')->onDelete('cascade');
            $table->foreignId('resepID')->constrained('resep_makanan')->onDelete('cascade');
            $table->primary(['penggunaID', 'resepID']);
            $table->timestamps();
        });

        // 8. Makanan
        Schema::create('makanan', function (Blueprint $table) {
            $table->id();
            $table->decimal('totalKalori', 6, 2);
            $table->decimal('kadarGula', 5, 2);
            $table->string('nama');
            $table->string('gambar')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 9. Perhitungan Gula
        Schema::create('perhitungan_gula', function (Blueprint $table) {
            $table->id();
            $table->foreignId('makananID')->constrained('makanan')->onDelete('cascade');
            $table->foreignId('jurnalID')->constrained('jurnal')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perhitungan_gula');
        Schema::dropIfExists('makanan');
        Schema::dropIfExists('resep_favorit');
        Schema::dropIfExists('resep_makanan');
        Schema::dropIfExists('berita');
        Schema::dropIfExists('jurnal');
        Schema::dropIfExists('status_gula');
        Schema::dropIfExists('admin');
        Schema::dropIfExists('pengguna');
    }
};
