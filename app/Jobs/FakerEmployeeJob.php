<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

use Faker\Factory as Faker;
class FakerEmployeeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', '1G');
        ini_set('max_execution_time', 0);
        
        $batchSize      = 1000; 
        // $totalRecords   = 3000;
        $totalRecords   = 150000; 
        // $totalRecords = 200000000;
        $faker          = Faker::create();

        DB::transaction(function () use ($faker, $batchSize, $totalRecords) {
            for ($i = 0; $i < $totalRecords; $i += $batchSize) {
                $data = [];
                for ($j = 0; $j < $batchSize; $j++) {
                    $data[] = [
                        // 'id_department' => $faker->randomElement(Department::all())['id'],
                        'id_department' => rand(1,6),
                        'firstname'     => fake()->name(35),
                        'lastname'      => fake()->name(35),
                        'email'         => fake()->unique()->safeEmail(),
                        'address'       => fake()->name(),
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                }
                DB::table('employees')->insert($data);
            }
        });

    }
}
