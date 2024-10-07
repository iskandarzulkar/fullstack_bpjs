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
    protected $chunkCount;

    public function __construct($chunkCount)
    {
        $this->chunkCount = $chunkCount;
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

        $merger = new Merger();

        // for ($i = 0; $i < $this->chunkCount; $i++) {
        //     // $pdfMerger->addPDF(storage_path("/app/public/exports/exported_chunk_{$i}.pdf"), 'all');
        //     $batchFileName = "/exports/exported_chunk_{$i}.pdf";
        //     // $filePath = storage_path("app/pdfs/chunk_{$i}.pdf");
        //     // Storage::put($batchFileName);
        //     // $pdfMerger->addRaw(Storage::get($batchFileName));
        //     $pdfMerger->addFile(storage_path($batchFileName));
        // }
        
        // $mergedPdf = $pdfMerger->merge();

        // // Save the final merged PDF
        // Storage::put('/public/merge/final_merged_output.pdf', $mergedPdf);

        for ($i = 0; $i < $this->chunkCount; $i++) {
            $filePath = storage_path("app/public/exports/exported_chunk_{$i}.pdf");
            if (file_exists($filePath)) {
                $merger->addFile($filePath);
            } else {
                \Log::error("File not found for merging: " . $filePath);
            }
        }

        $outputPath = storage_path('app/public/merge/merged.pdf');
        Storage::put($outputPath);
        // $merger->merge()->save($outputPath);

        // Optionally clean up the chunk files if not needed
        for ($i = 0; $i < $this->chunkCount; $i++) {
            $filePath = storage_path("app/public/exports/exported_chunk_{$i}.pdf");
            if (file_exists($filePath)) {
                unlink($filePath); // Delete the chunk file
            }
        }

        \Log::info("Merged PDF saved to: " . $outputPath);


    }
}
