<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Storage;
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

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
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
        
        for ($i = $this->start; $i <= $this->end; $i += 1000) {
    
            $data = $this->generateData($i, $i + 999);

            $pdf = PDF::loadView('Test.template', compact('data'))->output();

            $batchFileName = "/public/reports/batch_".$i."_to_" . $i + 999 .".pdf";
            Storage::put($batchFileName, $pdf);
            // $pdfMerger->addRaw(Storage::get($batchFileName));
        }

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
