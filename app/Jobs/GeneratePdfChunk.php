<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;

use App\Models\Emp;

class GeneratePdfChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $start;
    protected $chunkSize;
    protected $chunkNumber;

    public function __construct($start, $chunkSize, $chunkNumber)
    {
        $this->start        = $start;
        $this->chunkSize    = $chunkSize;
        $this->chunkNumber  = $chunkNumber;
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
        
        $data = Emp::skip($this->start)->take($this->chunkSize)->get();

        if ($data->isEmpty()) {
            return; 
        }

        $pdf = PDF::loadView('Export.employee_pdf', ['employee' => $data]);

        $filePath = storage_path("app/public/exports/exported_chunk_{$this->chunkNumber}.pdf");
        $pdf->save($filePath);
    }
}
