<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organizer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MultiTenantSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Superadmin
        User::firstOrCreate(
            ['email' => 'superadmin@amikom.ac.id'],
            [
                'name'     => 'Super Admin Amikom',
                'password' => Hash::make('password123'),
                'role'     => 'superadmin',
            ]
        );

        // 2. Akun Organizer 1 (HMSSI Amikom)
        $userHima = User::firstOrCreate(
            ['email' => 'hmssi@amikom.ac.id'],
            [
                'name'     => 'HMSSI Amikom',
                'password' => Hash::make('password123'),
                'role'     => 'organizer',
            ]
        );

        Organizer::firstOrCreate(
            ['user_id' => $userHima->id],
            [
                'name'        => 'Himpunan Mahasiswa Sistem Informasi',
                'description' => 'Organisasi Mahasiswa Prodi Sistem Informasi Amikom',
                'is_verified' => true,
            ]
        );

        // 3. Akun Organizer 2 (BEM Amikom)
        $userBem = User::firstOrCreate(
            ['email' => 'bem@amikom.ac.id'],
            [
                'name'     => 'BEM Amikom',
                'password' => Hash::make('password123'),
                'role'     => 'organizer',
            ]
        );

        Organizer::firstOrCreate(
            ['user_id' => $userBem->id],
            [
                'name'        => 'Badan Eksekutif Mahasiswa',
                'description' => 'BEM Universitas Amikom Yogyakarta',
                'is_verified' => true,
            ]
        );
    }
}