<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class PackageFactory extends Factory {
    public function definition(): array {
        return [
            'name' => $this->faker->sentence(3),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->paragraph(),
            'shortDescription' => $this->faker->sentence(),
            'status' => 'active',
            'isFeatured' => false,
            'images' => [],
            'includes' => [],
            'excludes' => [],
            'price' => 100000,
            'duration' => '1 Hari',
        ];
    }
}