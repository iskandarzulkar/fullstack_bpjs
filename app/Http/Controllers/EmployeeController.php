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
use App\Jobs\FakerEmployeeJob;




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
