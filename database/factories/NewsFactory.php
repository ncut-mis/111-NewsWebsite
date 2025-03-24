<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\Reporter;
use App\Models\Editor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\news>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'category_id' => Category::factory(),
            'reporter_id' => Reporter::factory(),
            'editor_id' => Editor::factory(),
            'title' => $this->faker->sentence,
            'status' => $this->faker->numberBetween(0, 1),
            'web_version' => $this->faker->url,
            'word_version' => $this->faker->url,
        ];
    }
}
