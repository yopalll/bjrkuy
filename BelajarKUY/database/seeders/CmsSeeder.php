<?php

namespace Database\Seeders;

use App\Models\InfoBox;
use App\Models\Partner;
use App\Models\SiteInfo;
use App\Models\Slider;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    /**
     * Seed CMS content: sliders, info boxes, partners, site info.
     */
    public function run(): void
    {
        // --- Sliders ---
        $sliders = [
            [
                'title' => 'Belajar Skill Baru di BelajarKUY',
                'description' => 'Ribuan kursus online dari instruktur terbaik Indonesia.',
                'image' => 'sliders/slide-1.jpg',
                'button_text' => 'Mulai Belajar',
                'button_url' => '/courses',
            ],
            [
                'title' => 'Diskon Hingga 50% untuk Pelajar',
                'description' => 'Manfaatkan promo spesial untuk mendapat materi premium.',
                'image' => 'sliders/slide-2.jpg',
                'button_text' => 'Lihat Promo',
                'button_url' => '/courses?promo=1',
            ],
            [
                'title' => 'Jadi Instruktur di BelajarKUY',
                'description' => 'Bagikan ilmu-mu dan dapatkan penghasilan tambahan.',
                'image' => 'sliders/slide-3.jpg',
                'button_text' => 'Daftar Instruktur',
                'button_url' => '/register?role=instructor',
            ],
        ];

        foreach ($sliders as $i => $slider) {
            Slider::create(array_merge($slider, [
                'status' => true,
                'sort_order' => $i + 1,
            ]));
        }

        // --- Info Boxes ---
        $infoBoxes = [
            ['title' => 'Instruktur Berpengalaman', 'description' => 'Belajar dari praktisi industri.', 'icon' => 'users'],
            ['title' => 'Materi Lengkap', 'description' => 'Video, dokumen, dan project nyata.', 'icon' => 'book-open'],
            ['title' => 'Sertifikat Resmi', 'description' => 'Dapat sertifikat setelah menyelesaikan kursus.', 'icon' => 'award'],
            ['title' => 'Akses Seumur Hidup', 'description' => 'Belajar kapan saja, di mana saja.', 'icon' => 'clock'],
        ];

        foreach ($infoBoxes as $i => $box) {
            InfoBox::create(array_merge($box, ['sort_order' => $i + 1]));
        }

        // --- Partners ---
        $partners = [
            'Traveloka', 'Tokopedia', 'Gojek', 'Shopee', 'Blibli', 'Bukalapak', 'BCA Digital',
        ];

        foreach ($partners as $i => $name) {
            Partner::create([
                'name' => $name,
                'image' => 'partners/'.strtolower($name).'.png',
                'status' => true,
                'sort_order' => $i + 1,
            ]);
        }

        // --- Site Info (key-value config) ---
        $siteInfo = [
            'site_name' => 'BelajarKUY',
            'site_tagline' => 'Belajar Online Kapan Saja',
            'site_email' => 'halo@belajarkuy.id',
            'site_phone' => '+62 811 0000 0001',
            'site_address' => 'Jakarta Selatan, Indonesia',
            'facebook_url' => 'https://facebook.com/belajarkuy',
            'instagram_url' => 'https://instagram.com/belajarkuy',
            'twitter_url' => 'https://twitter.com/belajarkuy',
            'youtube_url' => 'https://youtube.com/@belajarkuy',
            'footer_description' => 'BelajarKUY adalah platform e-learning terkemuka di Indonesia.',
        ];

        foreach ($siteInfo as $key => $value) {
            SiteInfo::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
