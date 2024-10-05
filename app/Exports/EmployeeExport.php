<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeExport implements FromQuery, WithHeadings
{
    public function query()
    {
        return Employee::query(); // Adjust as needed
    }

    public function headings(): array
    {
        return [
            'Id',
            'Id Department',
            'Firstname',
            'Lastname',
            'email',
            'address',
            // Add other headings as needed
        ];
    }
}
