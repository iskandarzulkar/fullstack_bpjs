<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Jobs\CreateExportFile;

use App\Jobs\ExportCsvJob;
use App\Jobs\ExportPdfJob;


use Faker\Factory as Faker;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('Employee/index');
    }

    public function getDataEmploye(Request $request)
    {
        $page       = $request->input('page', 1);
        $cacheKey   = 'posts.page.' . $page;

        $dataEmploye = Cache::remember($cacheKey, 60, function () {
            return  DB::table('employees')
                    ->join('departments',  'employees.id_department', '=', 'departments.id')
                    ->select('employees.*', 'departments.name')
                    ->paginate(50);
        });

        return response()->json($dataEmploye);
    }

    public function createFakerData()
    {

        $faker = Faker::create();
        $batchSize = 1000; 
        $totalRecords = 150000; 
        // $totalRecords = 200000000;

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

        return "Success";
    }

    // public function exportCsv()
    // {
    //     $chunkSize  = 10000;

    //     $countData  = Cache::remember('count-employe', 60, function () {
    //         return Employee::count();    
    //     }); 

    //     $numberOfChunk = ceil($countData / $chunkSize);

    //     $folder = now()->toDateString() . '-' . str_replace(':', '-', now()->toTimeString());
        
    //     $batches = [
    //         new CreateExportFile($chunkSize, $folder)
    //     ];

    //     dd($countData);

    //     $dataEmploye = Cache::remember('all-employe', 60, function () {
    //         return  DB::table('employees')
    //                 ->join('departments',  'employees.id_department', '=', 'departments.id')
    //                 ->select('employees.*', 'departments.name')
    //                 ->get();
    //     });

    //     dd($dataEmploye);

    // }

    public function exportCsv()
    {
        $dataEmploye = Employee::all();
        dd($dataEmploye);
        // dd(new ExportCsvJob());
        // $job = new ExportCsvJob();

        // dispatch($job);

        // die();
        // return response()->json(['message' => 'CSV export is being processed.']);
    }

    public function exportPdf()
    {
        $job = new ExportPdfJob();
        dispatch($job);
        
        return response()->json(['message' => 'PDF export is being processed.']);
    }
}
