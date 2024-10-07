<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Jobs\FakerEmployeeJob;
use App\Jobs\GeneratePdfChunk;
use App\Jobs\MergePdfChunks;

use App\Jobs\TestGeneratePdf;

use App\Models\Hit;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;

use PDF;

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
        $stratTime    = microtime(true);
        $job          = new FakerEmployeeJob();
        $this->dispatch($job);
        $endTime      = microtime(true);
        $timediff     = $endTime -$stratTime;

        return response()->json(['success' => true, 'time' => sprintf('%0.2f', $timediff)]);
    }

    public function exportPdf()
    {
        // $endpoint = '/Export-PDF';

        // Hit::updateOrCreate(
        //     ['endpoint' => $endpoint],
        //     ['count' => \DB::raw('count + 1')]
        // );

        $chunkSize      = 1000;
        $totalRecord    = Employee::count();
        $numberOfChunk  = ceil($totalRecord /$chunkSize);
 
        for ($i=0; $i < $numberOfChunk ; $i += $chunkSize) { 
            GeneratePdfChunk::dispatch($i * $chunkSize, $chunkSize, $i);
        }

        // for ($i=0; $i < $numberOfChunk ; $i += $chunkSize) { 
        //     $filePath = storage_path("app/public/exports/exported_chunk_{$i}.pdf");
        //     if (file_exists($filePath)) {
        //         // $merger->addFile($filePath);
        //         MergePdfChunks::dispatch($i * $chunkSize, $chunkSize, $i);
        //     } else {
        //         \Log::error("File not found for merging: " . $filePath);
        //     }
        // }
        
        
        return response()->json(['message' => 'PDF export is being processed.']);
    }

    public function textGenereatePdf()
    {
        $files       = Storage::disk('public')->files('/reports');
        $fileCount  = count($files);

        foreach ($files as $file) {
            Storage::disk('public')->delete($file);
        }

        $totalRecords   = 1000;
        $chunkSize      = 200;

        for ($i = 0; $i < $totalRecords; $i += $chunkSize) {
            TestGeneratePdf::dispatch($i, $i + $chunkSize - 1);
        }

        return response()->json(['message' => 'PDF generation and merging has started.']);

    }

    // public function textGenereatePdf()
    // {
    //     ini_set('memory_limit', '1G');
    //     ini_set('max_execution_time', 0);
        
    //     $data = []; // Fetch or generate your 200 million records here

    //     // For demonstration, let's assume we only need the structure here
    //     for ($i = 1; $i <= 10000; $i++) {
    //         $data[] = (object)[
    //             'id' => $i,
    //             'name' => 'User ' . $i,
    //             'email' => 'user' . $i . '@example.com'
    //         ];
            
    //         // Dispatch the job every certain amount of data to avoid memory issues
    //         if (count($data) >= 100) {
    //             TestGeneratePdf::dispatch($data);
    //             $data = []; // Reset the array
    //         }
    //     }

    //     // Handle any remaining data
    //     if (!empty($data)) {
    //         TestGeneratePdf::dispatch($data);
    //     }

    //     return response()->json(['message' => 'PDF generation started']);
    // }
}
