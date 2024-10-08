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

class TestMergerPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $pdfFiles;
    public function __construct($pdfFiles)
    {
        $this->pdfFiles  = $pdfFiles;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $merger = new Merger;

        foreach ($pdfFiles as $index => $pdfFile) {

            $fileContent = Storage::get($pdfFile);
            $merger->addRaw($fileContent);

        }

        $mergedPdf      = $merger->merge();
        $mergedPdfPath  = Storage::disk('public')->put('/merger/merged_report_emp.pdf', $mergedPdf);
        
    }
}
