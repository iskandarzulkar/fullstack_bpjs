<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // $faker = Faker::create();

        // $batchSize = 10; // Number of records to insert per batch
        // $totalRecords = 10; // Total records to insert
        // $totalRecords = 200000000; // Total records to insert
        // $totalRecords = 1000.000.000; // Total records to insert

        // DB::transaction(function () use ($faker, $batchSize, $totalRecords) {
        //     for ($i = 0; $i < $totalRecords; $i += $batchSize) {
        //         $data = [];
        //         for ($j = 0; $j < $batchSize; $j++) {
        //             $data[] = [
        //                 'id_department' => $this->faker->randomElement(Department::all())['id'],
        //                 'firstname'     => fake()->name(35),
        //                 'lastname'      => fake()->name(35),
        //                 'email'         => fake()->unique()->safeEmail(),
        //                 'address'       => fake()->name(),
        //                 'created_at'    => now(),
        //                 'updated_at'    => now(),
        //             ];
        //         }
                
        //         // dd($data);
        //         // Use insert statement
        //         // DB::table('employees')->insert($data);

        //         return $data;
        //     }
        // });

       

        return [
            'id_department' =>  $this->faker->randomElement(Department::all())['id'],
            'firstname'     => fake()->name(35),
            'lastname'      => fake()->name(35),
            'email'         => fake()->unique()->safeEmail(),
            'address'       => fake()->name(),
        ];
    }
}
