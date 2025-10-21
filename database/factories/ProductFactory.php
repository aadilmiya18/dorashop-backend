<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = \App\Models\Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'slug' => Str::slug($this->faker->word),
            'price' => $this->faker->numberBetween(0,10000),
            'sku' => $this->faker->regexify,
            'stock' => $this->faker->numberBetween(0,100),
            'status' => $this->faker->boolean(70),
            'is_featured' => $this->faker->boolean(50),
            'short_description' => $this->faker->paragraph,
            'description' => $this->faker->text(500),
        ];
    }
}
