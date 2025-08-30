<?php

namespace App\Http\Controllers;

use Log;
use Throwable;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Imports\StudentImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    // Import the Student
    public function import(Request $request)
    {
        try {
            // Validate the uploaded file
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv', //Adjusted by Panzerweb: includes .csv
            ]);

            // Get the uploaded file
            $file = $request->file('file');
            // Process the Excel file
            Excel::import(new StudentImport, $file->store('files'));

            return redirect()->route('dashboard')->with('success', "Data Imported Successfully");
        } catch (Throwable $error) {
            dd($error);

            // much better if there is a duplicate entry then the system will just skip that entry: => should be done in import controlle
            if ($error->getCode() == 23000) { //23000 is Integrity Constraint error
                return redirect()->route('dashboard')->with('error', $error->getMessage()); //For Duplicate Entries
            } else {
                return redirect()->route('dashboard')->with('error', $error->getMessage());
            }
        }
    }
}
