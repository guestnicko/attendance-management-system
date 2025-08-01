<?php

namespace App\Exports;

use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentAttendanceExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $students;
    private $wholeDay;

    public function collection()
    {
        return $this->students;
    }

    public function setCollection($request)
    {
        $this->students = $request;

        if ($request != null && $request->first() != null) {
            $this->wholeDay = $request->first()->isWholeDay;
        }
    }

    public function headings(): array
    {
        if ($this->wholeDay == "true") {
            return ["Student ID", "Last Name", "First Name", "Program", "Set", "Year Level", "Morning Check In", "Morning Check Out", "Afternoon Check In", "Afternoon Check Out", "Event Name", "isWholeDay", "Date"];
        } else {
            return ["Student ID", "Last Name", "First Name", "Program", "Set", "Year Level", "Check In", "Check Out", "Event Name", "Date"];
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],

            ] // Make headers bold
        ];
    }
}
