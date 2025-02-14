<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Fine;
use App\Models\FineSettings;
use App\Models\Student;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentAttendanceController extends Controller
{
    public function view()
    {
        date_default_timezone_set('Asia/Manila');
        $time = $time = date("H:i");
        $event = Event::where('date', '=', date('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->get()
            ->first();

        if ($event) {
            $this->processAbsentStudents($event, $time);
        }

        $pending = Event::where('date', '=', date('Y-m-d'))
            ->where(function (Builder $query) {
                $query->orWhere(function (Builder $query) {
                    $time = $time = date("H:i");
                    $query->where('checkIn_start', '<', $time)
                        ->where('checkIn_end', '>', $time);
                })
                    ->orWhere(function (Builder $query) {
                        $time = $time = date("H:i");
                        $query->where('checkOut_start', '<', $time)
                            ->where('checkOut_end', '>', $time);
                    });
            })
            ->get();



        if (empty($event)) {
            $event = null;
            return view('pages.attendance', compact('event'));
        }
        if ($time > $event->checkOut_end || $time < $event->checkIn_start || ($time > $event->checkIn_end && $time < $event->checkOut_start)) {
            $event = null;
        }


        if (empty($pending->first())) {
            $pending = null;
        }

        $students = $this->recent();
        return view('pages.attendance', compact('event', 'students', 'pending'));
    }

    protected function processAbsentStudents($event, $currentTime)
    {
        try {
            // Clean and parse time values
            $currentTime = Carbon::createFromFormat('H:i', $currentTime)->format('H:i:00');
            $checkInEnd = Carbon::createFromFormat('H:i', substr($event->checkIn_end, 0, 5))->format('H:i:00');
            $checkOutEnd = Carbon::createFromFormat('H:i', substr($event->checkOut_end, 0, 5))->format('H:i:00');

            // Only process if these times exist in the event
            if (isset($event->afternoon_checkIn_end) && isset($event->afternoon_checkOut_end)) {
                $afternoonCheckInEnd = Carbon::createFromFormat('H:i', substr($event->afternoon_checkIn_end, 0, 5))->format('H:i:00');
                $afternoonCheckOutEnd = Carbon::creasteFromFormat('H:i', substr($event->afternoon_checkOut_end, 0, 5))->format('H:i:00');
            }

            // Don't process if no periods have ended yet
            $currentTime = Carbon::createFromFormat('H:i:s', $currentTime);
            $checkInEnd = Carbon::createFromFormat('H:i:s', $checkInEnd);

            if ($currentTime->lt($checkInEnd)) {
                return;
            }

            // Rest of the function remains the same
            $settings = FineSettings::firstOrCreate(['id' => 1], [
                'fine_amount' => 25.00
            ]);

            $allStudents = Student::all();

            foreach ($allStudents as $student) {
                $attendance = StudentAttendance::where('student_rfid', $student->s_rfid)
                    ->where('event_id', $event->id)
                    ->first();

                $fine = Fine::firstOrCreate(
                    [
                        'student_id' => $student->id,
                        'event_id' => $event->id
                    ],
                    [
                        'absences' => 0,
                        'fine_amount' => $settings->fine_amount,
                        'total_fines' => 0,
                        'morning_checkin' => true,
                        'morning_checkout' => true,
                        'afternoon_checkin' => true,
                        'afternoon_checkout' => true
                    ]
                );

                // Reset counters
                $fine->absences = 0;

                // Only check morning check-in if that period has ended
                if ($currentTime->gt($checkInEnd)) {
                    if (!$attendance || !$attendance->attend_checkIn) {
                        $fine->morning_checkin = false;
                        $fine->absences++;
                    } else {
                        $fine->morning_checkin = true;
                    }
                }

                // Only check morning check-out if that period has ended
                if ($currentTime->gt($checkOutEnd)) {
                    if (!$attendance || !$attendance->attend_checkOut) {
                        $fine->morning_checkout = false;
                        $fine->absences++;
                    } else {
                        $fine->morning_checkout = true;
                    }
                }

                // Only check afternoon check-in if that period has ended
                if (isset($afternoonCheckInEnd) && $currentTime->gt($afternoonCheckInEnd)) {
                    if (!$attendance || !$attendance->attend_afternoon_checkIn) {
                        $fine->afternoon_checkin = false;
                        $fine->absences++;
                    } else {
                        $fine->afternoon_checkin = true;
                    }
                }

                // Only check afternoon check-out if that period has ended
                if (isset($afternoonCheckOutEnd) && $currentTime->gt($afternoonCheckOutEnd)) {
                    if (!$attendance || !$attendance->attend_afternoon_checkOut) {
                        $fine->afternoon_checkout = false;
                        $fine->absences++;
                    } else {
                        $fine->afternoon_checkout = true;
                    }
                }

                // Calculate total fines
                $fine->total_fines = $fine->absences * $settings->fine_amount;
                $fine->save();
            }
        } catch (\Exception $e) {
            Log::error('Error processing absences: ' . $e->getMessage());
            return;
        }
    }

    public function recordAttendance(Request $request)
    {
        // FIRST VALIDATE REQUEST FORM
        $request->validate([
            "s_rfid" => ['required'],
        ]);


        // CHECK IF STUDENT EXIST IN THE MASTERLIST
        if (empty(Student::whereAny(['s_rfid', 's_studentID'], $request->s_rfid)->get()->first())) {
            return response()->json([
                "message" => "I am sorry but the student does not exist in the masterlist",
                "isRecorded" => false,
                "doesntExist" => true,
            ]);
        }


        // INITIALIZE VARIABLES, ETC
        date_default_timezone_set('Asia/Manila');
        $time = date("H:i");
        $currentTimestamp = now();
        $currentTime = date('H:i:s');
        $event = Event::find($request->event_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->first();

        $student = StudentAttendance::where('student_rfid', $request->s_rfid)
            ->where('event_id', $request->event_id)
            ->get()
            ->first();

        // DETERMINE IF IT IS ALREADY PAST THE SET CHECK IN AND CHECK OUT TIME
        if ($time < $event->checkIn_start || ($time > $event->checkIn_end && $time < $event->checkOut_start) || $time > $event->checkOut_end) {
            return response()->json([
                "message" => "I am sorry, but your attendance can only be recorded during the set time frame",
                "isRecorded" => false
            ]);
        }

        $currentTime = date('H:i');


        if ($time > $event->checkIn_start && $time < $event->checkIn_end && !empty(StudentAttendance::where('event_id',$event->id )->where("student_rfid", $request->s_rfid)->get()->first())
        ) {
            return response()->json([
                "message" =>"Student's attendance is already recorded",
                "isRecorded" => false,
                "AlreadyRecorded"=>true
            ]);
        }

        if ($time > $event->checkOut_start && $time < $event->checkOut_end && !empty(StudentAttendance::where('event_id',$event->id )->where("student_rfid", $request->s_rfid)->whereNotNull('attend_checkOut')->get()->first())) {
            return response()->json([
                "message" =>"Student's attendance is already recorded",
                "isRecorded" => false,
                "AlreadyRecorded"=>true
            ]);
        }


        if ($time > $event->checkIn_start && $time < $event->checkIn_end) {
            $attendance = StudentAttendance::create([
                'student_rfid' => $request->s_rfid,
                'event_id' => $request->event_id,
                "attend_checkIn"=> $currentTime
            ]);
        }


        if ($time > $event->checkOut_start && $time < $event->checkOut_end && empty(StudentAttendance::where('event_id',$event->id )->where("student_rfid", $request->s_rfid)->get()->first())) {
            $attendance = StudentAttendance::create([
                'student_rfid' => $request->s_rfid,
                'event_id' => $request->event_id,
                "attend_checkOut"=> $currentTime
            ]);
        }
        if ($time > $event->checkOut_start && $time < $event->checkOut_end && !empty(StudentAttendance::where('event_id',$event->id )->where("student_rfid", $request->s_rfid)->get()->first())) {
            StudentAttendance::where('event_id',$event->id )
            ->where("student_rfid", $request->s_rfid)
            ->update([
                "attend_checkOut"=> $currentTime
            ]);
        }

        return response()->json([
            "message" => "Attendance recorded successfully!",
            "isRecorded" => true,
        ]);

    // CODE BELOW IS FOR FUTURE USE

        $settings = FineSettings::firstOrCreate(
            ['id' => 1],
            [
                'fine_amount' => 25.00,
                'morning_checkin' => true,
                'morning_checkout' => true,
                'afternoon_checkin' => true,
                'afternoon_checkout' => true
            ]
        );



        $fine = Fine::where('student_id', $student->id)
            ->where('event_id', $event->id)
            ->first();

        if ($fine) {
            // Morning check-in
            if ($time > $event->checkIn_start && $time < $event->checkIn_end) {
                $attendance->attend_checkIn = $currentTime;
                $attendance->morning_attendance = true;
                $attendance->save();

                if (!$fine->morning_checkin) {
                    $fine->morning_checkin = true;
                    $fine->absences -= 1;
                    $fine->total_fines = $fine->absences * $settings->fine_amount;
                    $fine->save();
                }
            }

            // Morning check-out
            if ($time > $event->checkOut_start && $time < $event->checkOut_end) {
                $attendance->attend_checkOut = $currentTime;
                $attendance->morning_attendance = true;
                $attendance->save();

                if (!$fine->morning_checkout) {
                    $fine->morning_checkout = true;
                    $fine->absences -= 1;
                    $fine->total_fines = $fine->absences * $settings->fine_amount;
                    $fine->save();
                }
            }

            // Afternoon check-in
            if ($time > $event->afternoon_checkIn_start && $time < $event->afternoon_checkIn_end) {
                $attendance->attend_afternoon_checkIn = $currentTime;
                $attendance->afternoon_attendance = true;
                $attendance->save();

                if (!$fine->afternoon_checkin) {
                    $fine->afternoon_checkin = true;
                    $fine->absences -= 1;
                    $fine->total_fines = $fine->absences * $settings->fine_amount;
                    $fine->save();
                }
            }

            // Afternoon check-out
            if ($time > $event->afternoon_checkOut_start && $time < $event->afternoon_checkOut_end) {
                $attendance->attend_afternoon_checkOut = $currentTime;
                $attendance->afternoon_attendance = true;
                $attendance->save();

                if (!$fine->afternoon_checkout) {
                    $fine->afternoon_checkout = true;
                    $fine->absences -= 1;
                    $fine->total_fines = $fine->absences * $settings->fine_amount;
                    $fine->save();
                }
            }


        }


    }

    public function recent()
    {
        date_default_timezone_set('Asia/Manila');
        $time = $time = date("H:i");
        $event = Event::where('date', '=', date('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->get()
            ->first();

        $students = StudentAttendance::select('*', 'student_attendances.created_at')->join('students', function($join){
            $join->on('student_attendances.student_rfid', '=', 'students.s_rfid')
            ->orOn('student_attendances.student_rfid', '=', 'students.s_studentID');
        });

        if (($time < $event->checkIn_end && $time > $event->checkIn_start)) {
            $students = $students
                ->where('event_id', $event->id)
                ->get();
        }

        if ($time < $event->checkOut_end && $time > $event->checkOut_start) {

            $students = $students->whereNotNull('attend_checkOut')
                ->where('event_id', $event->id)
                ->get();
        }

        if ($time > $event->checkOut_end || $time < $event->checkIn_start || ($time > $event->checkIn_end && $time < $event->checkOut_start)) {
            $event = null;
            $students = null;
        }

        return $students;
    }

    public function formatAttendance(){
        // FIRST GET STUDENT
        // CHECK IF STUDENT ATTENDED IN THE EVENT
        // COMBINE DUPLICATES OF SAME EVENT
        //
        $attendance = StudentAttendance::where('event_id', 2)->where( function (Builder $query){
            $query->where('student_rfid', "2023-00069")
            ->orWhere('student_rfid', "0002193309");
        })->get();

        $group = $attendance->groupBy(['student_rfid', 'event_id']);

        $student = Student::whereAny(['s_rfid', 's_studentID'], '')->get()->first();

        dd($group);

        dd($student);
    }
}
