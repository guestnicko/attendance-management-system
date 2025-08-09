<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StudentImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Ensure the program is either BSIS or BSIT
        // $validPrograms = ['BSIS', 'BSIT'];
        // $program = strtoupper(trim($row[6])); // Convert to uppercase and trim spaces

        // Default to 'BSIT' if invalid value is found (or handle it differently)
        // if (!in_array($program, $validPrograms)) {
        //     $program = 'BSIT'; // Change this behavior if needed
        // }

        // the code for determining if there is a duplicate entry
        $studentID = $row[0];
        if (!$studentID) {
            return new Student([
                "s_rfid" => NULL,
                "s_studentID" => $row[0] ? $row[0] : NULL,
                "s_fname" => $row[1],
                "s_lname" => $row[2],
                "s_mname" => $row[3],
                "s_suffix" => $row[4],
                "s_program" => $row[5],
                "s_lvl" => $row[6],
                "s_set" => $row[7],
                "s_image" => NULL,
                "s_status" => "TO BE UPDATED",
            ]);
        }

        $doesExist = Student::select('id as doesExist',)->where('s_studentID', $studentID)->get();

        if ($doesExist->count() > 0) {
            return [];
        }

        return new Student([
            "s_rfid" => NULL,
            "s_studentID" => $row[0] ? $row[0] : NULL,
            "s_fname" => $row[1],
            "s_lname" => $row[2],
            "s_mname" => $row[3],
            "s_suffix" => $row[4],
            "s_program" => $row[5],
            "s_lvl" => $row[6],
            "s_set" => $row[7],
            "s_image" => NULL,
            "s_status" => "TO BE UPDATED",
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
