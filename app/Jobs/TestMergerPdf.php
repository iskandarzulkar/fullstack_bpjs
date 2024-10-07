<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class TestMergerPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pdfFiles = Storage::disk('public')->files('/reports');

        // Add each PDF chunk to the merger
        foreach ($pdfFiles as $pdfFile) {
            $filePath = storage_path('app/reports/' . $pdfFile);
            $merger->addFile($filePath);
        }

        // Merge and store the final PDF
        $mergedPdf = $merger->merge();

        // Save the merged PDF to a file
        Storage::disk('public')->put('/merger/pdf_chunks/merged_report.pdf', $mergedPdf);
    }
}
