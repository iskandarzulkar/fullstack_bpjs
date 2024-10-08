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
use App\Models\Emp;

class MergePdfChunks implements ShouldQueue
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
        ini_set('memory_limit', '1G');
        ini_set('max_execution_time', 0);

        $merger = new Merger;

        // Add each PDF file to the merger
        foreach ($this->pdfFiles as $index => $pdfFile) {
            $fileContent = Storage::get($pdfFile);
            $merger->addRaw($fileContent);
        }

        $mergedPdf      = $merger->merge();
        $mergedPdfPath  = Storage::disk('public')->put('/merger/merged_report_emp.pdf', $mergedPdf);
    }
}
