<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(5),
            'description' => $this->faker->realText(200),
            'year' => $this->faker->year(),
        ];
    }

    public function withUserAndPublisher($userId = null, $publisherId = null)
    {
        return $this->state([
            'user_id' => $userId ?? $this->user()->id,
            'publisher_id' => $publisherId,
        ]);
    }
}