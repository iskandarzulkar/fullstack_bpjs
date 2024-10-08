<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use iio\libmergepdf\Merger;
use PDF;

class TestGeneratePdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
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

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', '1G');
        ini_set('max_execution_time', 0);

        $pdfMerger = new Merger();
        
        $cacheKey = 'pdf_merge_progress'; 
        Cache::put($cacheKey, 0); 

        for ($i = $this->start; $i <= $this->end; $i += $this->totalRecords) {

            $data = $this->generateData($this->start, $this->end);

            $pdf = PDF::loadView('Test.template', compact('data'))->output();

            $batchFileName = "/public/reports/batch_".$this->start."_to_" . $this->end .".pdf";
            Storage::put($batchFileName, $pdf);

            // Cache count
            $cacheKey = 'pdf_merge_progress'; 
            $progress = intval(($this->start / $this->totalRecords) * 100);
            Cache::put($cacheKey, $progress);

            // $pdfMerger->addRaw(Storage::get($batchFileName));
        }
        Cache::put($cacheKey, 100);

        // $mergedPdf = $pdfMerger->merge();
        // Storage::put('/public/reports/final_merged_output.pdf', $mergedPdf);
        
    }

    protected function generateData($start, $end)
    {
        $data = [];
        for ($i = $start; $i <= $end; $i++) {
            $data[] = [
                'id' => $i,
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com'
            ];
        }
        return $data;
    }
}
