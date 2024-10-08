<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Jobs\FakerEmployeeJob;

use App\Jobs\GeneratePdfChunk;
use App\Jobs\MergePdfChunks;

use App\Jobs\TestGeneratePdf;
use App\Jobs\TestMergerPdf;

use App\Models\Emp;
use App\Models\Hit;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;

use iio\libmergepdf\Merger;

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
        $files      = Storage::disk('public')->files('/emp');
        $fileCount  = count($files);

        foreach ($files as $file) {
            Storage::disk('public')->delete($file);
        }

        // $totalRecords   = 1000;
        // $chunkSize      = 200;
        $chunkSize       = 1000;
        $totalRecords    = Employee::count();
        $numberOfChunk   = ceil($totalRecords /$chunkSize);

        for ($i = 0; $i < $totalRecords; $i += $chunkSize) {
            $sumRecord = ($i+ + $chunkSize);
            GeneratePdfChunk::dispatch($i, $i + $chunkSize - 1, $totalRecords, $sumRecord);
        }
        
        $totalFiles = $totalRecords / $chunkSize;
        if($fileCount <= $totalFiles ){
            
            // Storage file pdf on folder
            $folderPath = '/public/emp';
            $pdfFiles   = Storage::files($folderPath);
            
            // Filter only PDF files
            $pdfFiles = array_filter($pdfFiles, function ($file) {
                return pathinfo($file, PATHINFO_EXTENSION) === 'pdf';
            });

            MergePdfChunks::dispatch($pdfFiles);
            
            return response()->json(['message' => 'PDFs Merged Successfully', 'download_url' => storage_path('app/public/merger/merged_report.pdf')]);
        }

        // return response()->json(['message' => 'PDF export is being processed.']);
    }

    public function textGenereatePdf()
    {
        $files      = Storage::disk('public')->files('/reports');
        $fileCount  = count($files);

        foreach ($files as $file) {
            Storage::disk('public')->delete($file);
        }

        $totalRecords   = 1000;
        $chunkSize      = 200;

        // $progress = intval(($this->totalRecords / $records) * 100);
        
        for ($i = 0; $i < $totalRecords; $i += $chunkSize) {
            $sumRecord = ($i+ + $chunkSize);
            TestGeneratePdf::dispatch($i, $i + $chunkSize - 1, $totalRecords, $sumRecord);
            // TestGeneratePdf::dispatch($i, $i + $chunkSize - 1, $totalRecords, $sumRecord);
        }

        $totalFiles = $totalRecords / $chunkSize;

        if($fileCount <= $totalFiles ){
            
            // Storage file pdf on folder
            $folderPath = '/public/reports';
            $pdfFiles   = Storage::files($folderPath);
            
            // Filter only PDF files
            $pdfFiles = array_filter($pdfFiles, function ($file) {
                return pathinfo($file, PATHINFO_EXTENSION) === 'pdf';
            });

            TestMergerPdf::dispatch($pdfFiles);
            
            return response()->json(['message' => 'PDFs Merged Successfully', 'download_url' => storage_path('app/public/merger/merged_report.pdf')]);
        }

        // return response()->download(storage_path('app/public/merger/merged_report.pdf'));

    }

    public function getProgressGenerateTest()
    {
        $progress = Cache::get('pdf_merge_progress', 0);
        return response()->json(['progress' => $progress]);
    }
}
