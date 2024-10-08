<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use PDF;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Models\Emp;

class GeneratePdfChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $start;
    protected $end;
    protected $totalRecords;
    protected $sumRecord;

    public function __construct($start, $end, $totalRecords)
    {
        $this->start        = $start;
        $this->end          = $end;
        $this->totalRecords = $totalRecords;
    }

    public function handle()
    {
        ini_set('memory_limit', '2G');
        ini_set('max_execution_time', 0);
        
        $cacheKey = 'pdf_merge_progress'; 
        Cache::put($cacheKey, 0); 

        for ($i = $this->start; $i <= $this->end; $i += 1000) {

            $data = $this->generateData($this->start, $this->end);
            $from = $this->start;
            $to   = $this->end;

            $pdf = PDF::loadView('Export.employee_pdf', compact('data', 'from', 'to'))->output();

            $batchFileName = "/public/emp/batch_".$this->start."_to_" . $this->end .".pdf";
            Storage::put($batchFileName, $pdf);

            // Cache count
            $cacheKey = 'pdf_merge_progress'; 
            $progress = intval(($this->start / $this->totalRecords) * 100);
            Cache::put($cacheKey, $progress);
        }

        Cache::put($cacheKey, 100);

    }

    protected function generateData($start, $end)
    {
        $data = DB::table('emp')
            ->skip($start)
            ->take($end)
            ->get();

        return $data;
    }
}
