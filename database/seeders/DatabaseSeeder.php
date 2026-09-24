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

        \App\Models\Siswa::create([
            'nis' => 24524,
            'kelas' => 'XII RPL 1',
        ]);


    }
}
