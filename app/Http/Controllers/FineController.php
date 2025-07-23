<?php

namespace App\Http\Controllers;

use App\Exports\FinesExport;
use App\Models\Event;
use App\Models\Fine;
use App\Models\Student;
use App\Models\StudentAttendance;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;
use Illuminate\Support\Collection as Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

class FineController extends Controller
{
    private const FINE_AMOUNT = 25.00;

    public function view()
    {
        // RETRIEVE ALL STUDENT
        $logs = DB::table('students')
            ->leftJoin('fines', 'students.id', '=', 'fines.student_id')
            ->leftJoin('events', 'events.id', '=', 'fines.event_id')
            ->select('students.*', 'fines.*', 'events.event_name')
            ->paginate(15);
        // $logs = Fine::with("student", "event")->get();

        $events = Event::select('*')->orderBy('created_at')->get();
        $pageCount = $logs->lastPage();
        return view('pages.fines', compact('logs', 'events', "pageCount"));
    }

    public function calculateEventFines(Event $event)
    {

        // Check if student has attendance record for this event
        $logs = DB::table('students')
            ->leftJoin('student_attendances', 'students.id', '=', 'student_attendances.id_student')
            ->select('*', 'students.id')
            ->get();

        // Calculate missed actions and fines
        foreach ($logs as $attendance) {

            if ($event->isWholeDay == "true") {
                $missedActions = $this->calculateMissedActionsWholeDay($attendance);
            } else {
                $missedActions = $this->calculateMissedActions($attendance);
            }
            $missedCount = array_sum(array_map(fn($v) => $v ? 1 : 0, $missedActions));

            if ($missedCount > 0) {
                $totalFines = $missedCount * self::FINE_AMOUNT;
                // Create or update fine record
                Fine::updateOrCreate(
                    [
                        'event_id' => $event->id,
                        'student_id' => $attendance->id
                    ],
                    [
                        'student_id' => $attendance->id,
                        'event_id' => $event->id,
                        'fines_amount' => self::FINE_AMOUNT,
                        'morning_checkIn_missed' => $missedActions['morning_checkIn_missed'],
                        'morning_checkOut_missed' => $missedActions['morning_checkOut_missed'],
                        'afternoon_checkIn_missed' => $missedActions['afternoon_checkIn_missed'],
                        'afternoon_checkOut_missed' => $missedActions['afternoon_checkOut_missed'],
                        'total_fines' => $totalFines
                    ]
                );
            }
        }
    }

    private function calculateMissedActions(?stdClass $attendance): array
    {


        if (!$attendance) {
            return [
                'morning_checkIn_missed' => true,
                'morning_checkOut_missed' => true,
                'afternoon_checkIn_missed' => false,
                'afternoon_checkOut_missed' => false
            ];
        }


        return [
            'morning_checkIn_missed' => !$attendance->attend_checkIn,
            'morning_checkOut_missed' => !$attendance->attend_checkOut,
            'afternoon_checkIn_missed' => false,
            'afternoon_checkOut_missed' => false
        ];
    }
    private function calculateMissedActionsWholeDay(?stdClass $attendance): array
    {


        if (!$attendance) {
            return [
                'morning_checkIn_missed' => true,
                'morning_checkOut_missed' => true,
                'afternoon_checkIn_missed' => true,
                'afternoon_checkOut_missed' => true
            ];
        }


        return [
            'morning_checkIn_missed' => !$attendance->attend_checkIn,
            'morning_checkOut_missed' => !$attendance->attend_checkOut,
            'afternoon_checkIn_missed' => !$attendance->attend_afternoon_checkIn,
            'afternoon_checkOut_missed' => !$attendance->attend_afternoon_checkOut
        ];
    }


    public function filter(Request $request)
    {
        $students = Student::leftJoin('fines', 'students.id', '=', 'fines.student_id')
            ->leftJoin('events', 'events.id', '=', 'fines.event_id')
            ->select('students.*', 'fines.*', 'events.event_name')
            ->whereAny(['s_fname', 's_studentID', 's_lname'], 'like', $request->query('search') . '%')
            ->paginate(15)
            ->withQueryString();

        if (empty($students->first())) {
            return response()->json([
                'message' => 'Student not found',
                'students' => null,
            ]);
        }

        return response()->json([
            'message' => 'Working fine',
            'students' => $students,
        ]);
    }

    public function filterByCategory(Request $request)
    {
        $students = Student::leftJoin('fines', 'students.id', '=', 'fines.student_id')
            ->leftJoin('events', 'events.id', '=', 'fines.event_id')
            ->select('students.*', 'fines.*', 'events.event_name');

        if ($request->query('set')) {
            $set = explode(',', $request->query('set'));
            $students = $students->whereIn('s_set', $set);
        }
        if ($request->query('lvl')) {
            $lvl = explode(',', $request->query('lvl'));
            $students = $students->whereIn('s_lvl', $lvl);
        }
        if ($request->query('program')) {
            $program = explode(',', $request->query('program'));
            $students = $students->whereIn('s_program', $program);
        }
        if ($request->query('status')) {
            $status = explode(',', $request->query('status'));
            $students = $students->whereIn('s_status', $status);
        }
        if ($request->query('event_id')) {
            $students = $students->where('event_id', $request->query('event_id'));
        }

        $students = $students->paginate(15)->withQueryString();

        if (empty($students->first())) {
            return response()->json([
                'message' => 'Student not found',
                'students' => null,
                'query' => $request->query(),
            ]);
        }

        return response()->json([
            'message' => 'Working fine',
            'students' => $students,
            'query' => $request->query(),
        ]);
    }

    public function filterByEvent(Request $request)
    {
        if ($request->query("event_id") == null) {
            $students = Student::leftJoin('fines', 'students.id', '=', 'fines.student_id')
                ->leftJoin('events', 'events.id', '=', 'fines.event_id')
                ->select('students.*', 'fines.*', 'events.event_name')
                ->paginate(15)
                ->withQueryString();

            return response()->json([
                'message' => 'Working fine',
                'students' => $students,
                'query' => $request->query(),
            ]);
        }

        $students = Student::leftJoin('fines', 'students.id', '=', 'fines.student_id')
            ->leftJoin('events', 'events.id', '=', 'fines.event_id')
            ->select('students.*', 'fines.*', 'events.event_name')
            ->where("event_id", $request->query('event_id'))
            ->paginate(15)
            ->withQueryString();


        if (empty($students->first())) {
            return response()->json([
                'message' => 'Student not found',
                'students' => null,
            ]);
        }

        return response()->json([
            'message' => 'Working fine',
            'students' => $students,
            'query' => $request->query(),
        ]);
    }
    public function clearLogs(Request $request)
    {
        dd($request);
        return back()->with(["success" => "Fines Log Cleared Successfully"]);
    }
    public function exportFile(Request $request)
    {

        $request->validate([
            "event_id" => ['required'],
            "file_type" => ['required']
        ]);

        if ($request->file_type == "pdf") {
            return $this->generatePDF($request);
        }

        if ($request->file_type == "excel") {
            return $this->generateExcel($request);
        }
        dd("something went wrong");
    }
    protected function generatePDF(Request $request)
    {

        $event = Event::findOrfail($request->event_id);

        // RETRIEVE ALL PRESENT AND ABSENT STUDENT THEN UNION THEM
        $students = Student::leftJoin('fines', 'students.id', '=', 'fines.student_id')
            ->leftJoin('events', 'events.id', '=', 'fines.event_id')
            ->select('students.*', 'fines.*', 'events.event_name');

        if ($request->set) {
            $set = explode(',', $request->set);
            $students = $students->whereIn('s_set', $set);
        }
        if ($request->lvl) {
            $lvl = explode(',', $request->lvl);
            $students = $students->whereIn('s_lvl', $lvl);
        }
        if ($request->program) {
            $program = explode(',', $request->program);
            $students = $students->whereIn('s_program', $program);
        }
        if ($request->status) {
            $status = explode(',', $request->status);
            $students = $students->whereIn('s_status', $status);
        }
        if ($request->event_id) {
            $students = $students->where('event_id', $request->event_id);
        }

        $logs = $students->get();
        $pdf = PDF::loadView('reports.fines', compact('logs', 'event'));

        return $pdf->download("sample_fines.pdf");
    }
    protected function generateExcel(Request $request)
    {
        $request->validate([
            "event_id" => ['required']
        ]);
        $event = Event::findOrfail($request->event_id);
        // RETRIEVE ALL PRESENT AND ABSENT STUDENT THEN UNION THEM
        $students = Fine::select([
            'students.s_studentID',
            's_lname',
            's_fname',
            's_program',
            's_set',
            's_lvl',
            "fines_amount",
            "fines.total_fines",
            'event_name',
            'fines.created_at'
        ])
            ->leftJoin('students', 'students.id', '=', 'fines.student_id')
            ->join('events', 'events.id', '=', 'fines.event_id');

        if ($request->event_id) {
            $students = $students->where('event_id', $request->event_id);
        }
        if ($request->set) {
            $set = explode(',', $request->set);
            $students = $students->whereIn('s_set', $set);
        }
        if ($request->lvl) {
            $lvl = explode(',', $request->lvl);
            $students = $students->whereIn('s_lvl', $lvl);
        }
        if ($request->program) {
            $program = explode(',', $request->program);
            $students = $students->whereIn('s_program', $program);
        }
        if ($request->status) {
            $status = explode(',', $request->status);
            $students = $students->whereIn('s_status', $status);
        }
        if ($request->event_id) {
            $students = $students->where('event_id', $request->event_id);
        }

        $students = $students->get();

        if (empty($students)) {
            return back()->with(["empty" => "No logs found"]);
        }

        $logs = new FinesExport;
        $logs->setCollection($students);
        return Excel::download($logs, $event->event_name . "_student_fines_report.xlsx");
    }
}
