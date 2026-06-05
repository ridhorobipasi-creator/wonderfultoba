<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class BlogFactory extends Factory {
    public function definition(): array {
        return [
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'excerpt' => $this->faker->sentence(),
            'author' => $this->faker->name(),
            'content' => $this->faker->paragraphs(3, true),
            'status' => 'published',
            'category' => 'Tips',
        ];
    }
}