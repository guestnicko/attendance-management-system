<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function viewDashboard()
    {
        $studentCount = Student::all()->count();
        $graduateCount = Student::where('s_status', 'GRADUATED')->get()->count(); //Fetch all graduate counts
        
        // Get attendance records with proper joins and error handling
        try {
            $attendances = StudentAttendance::join('students', function ($join){
                $join->on('student_attendances.student_rfid', '=', 'students.s_rfid')
                    ->orOn('student_attendances.student_rfid', '=', 'students.s_studentID');
            })
            ->join('events', 'events.id', '=', 'student_attendances.event_id')
            ->select([
                'students.id',
                'students.s_fname',
                'students.s_lname',
                'students.s_mname',
                'students.s_suffix',
                'students.s_program',
                'students.s_lvl',
                'students.s_set',
                'students.s_status',
                'student_attendances.attend_checkIn',
                'student_attendances.attend_checkOut',
                'student_attendances.attend_afternoon_checkIn',
                'student_attendances.attend_afternoon_checkOut',
                'events.event_name',
                'events.date'
            ])
            ->orderBy('events.date', 'desc')
            ->orderBy('students.s_lname', 'asc')
            ->get();
        } catch (\Exception $e) {
            // Log error and provide fallback
            Log::error('Error fetching attendance data: ' . $e->getMessage());
            $attendances = collect(); // Empty collection as fallback
        }
        
        return view('dashboard', compact('studentCount', 'attendances', 'graduateCount'));
    }

    public function test(Request $request) {

    }
}
