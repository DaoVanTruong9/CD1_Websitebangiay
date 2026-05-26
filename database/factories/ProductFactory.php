<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Nike Air',
            'brand' => 'Nike',
            'price' => 2000000,
            'size' => '40',
            'image' => 'nike.jpg',
            'description' => 'Giày test'
        ];
    }
}