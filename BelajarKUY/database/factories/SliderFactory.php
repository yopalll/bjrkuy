<?php

namespace Database\Factories;

use App\Models\Slider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Slider>
 */
class SliderFactory extends Factory
{
    protected $model = Slider::class;

    public function definition(): array
    {
        return [
            'title' => fake()->catchPhrase(),
            'description' => fake()->sentence(12),
            'image' => 'sliders/slide-'.fake()->uuid().'.jpg',
            'button_text' => fake()->randomElement(['Lihat Sekarang', 'Mulai Belajar', 'Cek Promo']),
            'button_url' => '/courses',
            'status' => true,
            'sort_order' => fake()->numberBetween(0, 20),
        ];
    }
}
