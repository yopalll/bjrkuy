<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Seed 8 kategori utama + 3-5 sub-kategori per kategori.
     */
    public function run(): void
    {
        $categories = [
            'Web Development' => ['Laravel', 'React', 'Vue.js', 'Next.js', 'Node.js'],
            'Mobile Development' => ['Flutter', 'React Native', 'Android Native', 'iOS Native'],
            'Data Science' => ['Python', 'Machine Learning', 'Deep Learning', 'Data Analysis'],
            'UI/UX Design' => ['Figma', 'Adobe XD', 'Design Thinking', 'Prototyping'],
            'DevOps' => ['Docker', 'Kubernetes', 'CI/CD', 'AWS'],
            'Cybersecurity' => ['Ethical Hacking', 'Network Security', 'Cryptography'],
            'Bisnis & Marketing' => ['Digital Marketing', 'SEO', 'Copywriting', 'Content Marketing'],
            'Personal Development' => ['Public Speaking', 'Time Management', 'Leadership'],
        ];

        foreach ($categories as $name => $subs) {
            $category = Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'image' => 'categories/'.Str::slug($name).'.png',
                'status' => true,
            ]);

            foreach ($subs as $sub) {
                SubCategory::create([
                    'category_id' => $category->id,
                    'name' => $sub,
                    'slug' => Str::slug($sub).'-'.$category->id,
                ]);
            }
        }
    }
}
