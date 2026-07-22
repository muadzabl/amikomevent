<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah 'role' HANYA jika kolomnya belum ada di tabel users
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['superadmin', 'organizer', 'customer'])
                      ->default('customer')
                      ->after('email');
            });
        }

        // 2. Buat tabel 'organizers' jika belum ada
        if (!Schema::hasTable('organizers')) {
            Schema::create('organizers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('name'); // Nama HIMA/UKM
                $table->string('logo')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_verified')->default(false); // Kelayakan oleh Superadmin
                $table->timestamps();
            });
        }

        // 3. Tambah foreign key 'organizer_id' di tabel events jika belum ada
        if (!Schema::hasColumn('events', 'organizer_id')) {
            Schema::table('events', function (Blueprint $table) {
                $table->foreignId('organizer_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('organizers')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'organizer_id')) {
                $table->dropForeign(['organizer_id']);
                $table->dropColumn('organizer_id');
            }
        });

        Schema::dropIfExists('organizers');

        // Jangan hapus 'role' jika awalnya memang sudah ada
    }
};