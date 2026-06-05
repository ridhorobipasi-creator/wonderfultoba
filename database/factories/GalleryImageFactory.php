<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class GalleryImageFactory extends Factory {
    public function definition(): array {
        return [
            'caption' => $this->faker->sentence(),
            'category' => 'tour',
            'imageUrl' => 'test.jpg',
            'isActive' => true,
        ];
    }
}