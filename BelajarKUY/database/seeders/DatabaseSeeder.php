<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Orchestrate semua seeder dalam urutan yang benar (FK dependencies).
     *
     * Urutan:
     *   1. UserSeeder       — accounts demo (admin, instructors, students)
     *   2. CategorySeeder   — master data kategori & sub-kategori
     *   3. CourseSeeder     — kursus beserta goals, sections, lectures
     *   4. TransactionSeeder— wishlist, cart, coupon, payment, order, enrollment, review
     *   5. CmsSeeder        — slider, info box, partner, site info
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            CourseSeeder::class,
            TransactionSeeder::class,
            CmsSeeder::class,
        ]);
    }
}
