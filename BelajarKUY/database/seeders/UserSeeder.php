<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed users: 1 admin, 3 instructors, 10 students.
     * Password default untuk semua akun demo: `password`.
     */
    public function run(): void
    {
        // --- Admin ---
        User::factory()->admin()->create([
            'name' => 'Admin BelajarKUY',
            'email' => 'admin@belajarkuy.test',
            'password' => Hash::make('password'),
        ]);

        // --- Instructors ---
        User::factory()->instructor()->create([
            'name' => 'Ray Nathan',
            'email' => 'ray@belajarkuy.test',
            'password' => Hash::make('password'),
            'bio' => 'Senior Full-Stack Developer dengan pengalaman 10+ tahun.',
        ]);

        User::factory()->instructor()->create([
            'name' => 'Yosua Valentino',
            'email' => 'yosua@belajarkuy.test',
            'password' => Hash::make('password'),
            'bio' => 'UI/UX Designer & Frontend Engineer.',
        ]);

        User::factory()->instructor()->count(3)->create();

        // --- Students ---
        User::factory()->student()->create([
            'name' => 'Test Student',
            'email' => 'student@belajarkuy.test',
            'password' => Hash::make('password'),
        ]);

        User::factory()->student()->count(10)->create();
    }
}
