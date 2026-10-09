<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
        ]);

        \App\Models\Kategori::insert([
            ['ket_kategori' => 'Fasilitas Kelas'],
            ['ket_kategori' => 'Kebersihan Lingkungan'],
            ['ket_kategori' => 'Fasilitas Bengkel/Lab'],
            ['ket_kategori' => 'Keamanan'],
        ]);

        \App\Models\Siswa::insert([
            [
                'nis' => 24524,
                'kelas' => 'XII RPL 1'
            ],
            [
                'nis' => 24624,
                'kelas' => 'XI RPL 1'
            ],
            [
                'nis' => 24724,
                'kelas' => 'XII TKJ 1'
            ],
            [ 
                'nis' => 24824,
                'kelas' => 'XI TSM 2'
            ]
        ]);


    }
}
