<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class CreateExportFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public $chunkSize,
        public $folder
    )
    {
       
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $employee = Employee::query()
            ->take($this->chunkSize)
            ->get();

            
        Storage::disk('local')->makeDirectory($this->folder);
            
        dd($emplyee);

        dd($this->chunkSize);
    }
}
