<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    function createUrlSlug($urlString)
    {
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $urlString);
        return $slug;
    }
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(5);
        $slug = $this->createUrlSlug($title);

        return [
            'title' => $title,
            'slug' => $slug,
            'description' => $this->faker->paragraph(20),
            'short_description' => $this->faker->sentence(10),
            'category' => $this->faker->word(),
            'price' => $this->faker->randomFloat(2, 5, 500),
            'sku' => $this->faker->word(),
            'brand' => $this->faker->word(),
            'quantity' => $this->faker->randomDigit(1, 100),
            'discount_price' => $this->faker->optional()->randomFloat(2, 2, 200),
            'images' => $this->faker->randomElement(['https://themes.rslahmed.dev/rafcart/assets/images/product3.jpg', 'https://themes.rslahmed.dev/rafcart/assets/images/product2.jpg', 'https://themes.rslahmed.dev/rafcart/assets/images/product1.jpg', 'https://themes.rslahmed.dev/rafcart/assets/images/product4.jpg', 'https://themes.rslahmed.dev/rafcart/assets/images/product5.jpg'], $count = rand(1, 3)),
            'sizes' => $this->faker->randomElements(['m', 'l', 'xl', 'xxl', '2xl'], $count = rand(1, 3)),
        ];
    }
}
