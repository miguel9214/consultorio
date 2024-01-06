<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type_document'=>'cc',
            'document'=>$this->faker->numberBetween(1065000000,1065999999),
            'first_name'=>$this->faker->name(),
            'last_name'=>$this->faker->lastName(),
            'sex'=>'masculino',
            'phone'=>$this->faker->numberBetween(3172560000,3234560000),
            'birthdate'=>$this->faker->date(),
            'address'=>$this->faker->address(),
            'city'=>$this->faker->city(),
            'state'=>$this->faker->name('cc'),
            'neighborhood'=>$this->faker->streetName(),
            'created_by_user'=>1,
            'updated_by_user'=>1,
        ];
    }
}
