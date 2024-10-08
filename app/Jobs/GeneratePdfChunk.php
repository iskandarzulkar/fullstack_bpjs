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

    public function __construct($start, $end, $totalRecords, $sumRecord)
    {
        $this->start        = $start;
        $this->end          = $end;
        $this->totalRecords = $totalRecords;
        $this->sumRecord    = $sumRecord;
    }

    public function handle()
    {
        ini_set('memory_limit', '1G');
        ini_set('max_execution_time', 0);
        
        $cacheKey = 'pdf_merge_progress'; 
        Cache::put($cacheKey, 0); 

        for ($i = $this->start; $i <= $this->end; $i += 1000) {

            $data = $this->generateData($i, $i + 999);
            $from = $i;
            $to   = $i+999;

            $pdf = PDF::loadView('Export.employee_pdf', compact('data', 'from', 'to'))->output();

            $batchFileName = "/public/emp/batch_".$i."_to_" . $i + 999 .".pdf";
            Storage::put($batchFileName, $pdf);

            // Cache count
            $cacheKey = 'pdf_merge_progress'; 
            $progress = intval(($this->sumRecord / $this->totalRecords) * 100);
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
