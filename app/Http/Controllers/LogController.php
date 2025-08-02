<?php

namespace App\Http\Controllers;

use App\Exports\StudentAttendanceExport;
use App\Models\Event;
use App\Models\Log;
use App\Models\StudentAttendance;
use App\Models\Fine;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

use function Pest\Laravel\json;

class LogController extends Controller
{
    public function viewLogs()
    {
        $logs = StudentAttendance::leftJoin('students', 'students.id', '=', 'student_attendances.id_student')
            ->join('events', 'events.id', '=', 'student_attendances.event_id')
            ->paginate(15);


        // Get fines with related student and event data
        $fines = Fine::with(['student', 'event'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pageCount = $logs->lastPage();
        $events = Event::select('*')->orderBy('created_at')->get();
        return view('pages.logs', compact('logs', 'fines', 'events', 'pageCount'));
    }

    public function exportFile(Request $request)
    {
        $request->validate([
            "event_id" => ['required'],
            "file_type" => ['required'],
            "prepared_by" => ['required']
        ]);


        if ($request->file_type == "pdf") {
            return $this->generatePDF($request);
        }

        if ($request->file_type == "excel") {
            return $this->generateExcel($request);
        }


        return back()->withErrors(["failed" => "Export is successful"]);
    }

    protected function generatePDF(Request $request)
    {
        $event = Event::findOrfail($request->event_id);

        // RETRIEVE ALL PRESENT AND ABSENT STUDENT THEN UNION THEM
        $students = StudentAttendance::select('*', 'student_attendances.created_at')
            ->leftJoin('students', 'students.id', '=', 'student_attendances.id_student')
            ->join('events', 'events.id', '=', 'student_attendances.event_id');

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
        if (empty($logs->first())) {
            return back()->with(["empty" => "No logs found"]);
        }
        $wholeDay = $event->isWholeDay;
        if ($wholeDay != "false" && $wholeDay) {
            $pdf = PDF::loadView('reports.attendance-wholeDay', compact('logs', 'event', "request"));
        } else {
            $pdf = PDF::loadView('reports.attendance', compact('logs', 'event', "request"));
        }
        return $pdf->download("sample.pdf");
    }
    protected function generateExcel(Request $request)
    {
        $request->validate([
            "event_id" => ['required']
        ]);
        $event = Event::findOrfail($request->event_id);
        $wholeDay = $event->isWholeDay;
        // RETRIEVE ALL PRESENT AND ABSENT STUDENT THEN UNION THEM

        if ($wholeDay != "false" && $wholeDay) {
            $students = StudentAttendance::select([
                'students.s_studentID',
                's_lname',
                's_fname',
                's_program',
                's_set',
                's_lvl',
                'attend_checkIn',
                'attend_checkOut',
                'attend_afternoon_checkIn',
                'attend_afternoon_checkOut',
                'event_name',
                "isWholeDay",
                'student_attendances.created_at'
            ])
                ->leftJoin('students', 'students.id', '=', 'student_attendances.id_student')
                ->join('events', 'events.id', '=', 'student_attendances.event_id');
        } else {
            $students = StudentAttendance::select([
                'students.s_studentID',
                's_lname',
                's_fname',
                's_program',
                's_set',
                's_lvl',
                'attend_checkIn',
                'attend_checkOut',

                'event_name',
                'student_attendances.created_at'
            ])
                ->leftJoin('students', 'students.id', '=', 'student_attendances.id_student')
                ->join('events', 'events.id', '=', 'student_attendances.event_id');
        }
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
        if (empty($students->first())) {
            return back()->with(["empty" => "No logs found"]);
        }
        $logs = new StudentAttendanceExport;
        $logs->setCollection($students);
        return Excel::download($logs, $event->event_name . "_student_attendance_report.xlsx");
    }

    protected function generateCSV(Request $request)
    {
        $request->validate([
            "event_id" => ['required']
        ]);

        $csv = Csv::class;
    }

    public function filterByCategory(Request $request)
    {

        $students = StudentAttendance::select('*', 'student_attendances.created_at')
            ->leftJoin('students', 'students.id', '=', 'student_attendances.id_student')
            ->join('events', 'events.id', '=', 'student_attendances.event_id');

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
    public function filter(Request $request)
    {
        $students = StudentAttendance::select('*', 'student_attendances.created_at')
            ->leftJoin('students', 'students.id', '=', 'student_attendances.id_student')
            ->join('events', 'events.id', '=', 'student_attendances.event_id')
            ->whereAny(['s_fname', 's_studentID', 's_mname', 's_lname'], 'like', $request->query('search') . '%')
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

    public function filterByEvent(Request $request)
    {
        if ($request->query("event_id") == null) {
            $students = StudentAttendance::select('*', 'student_attendances.created_at')
                ->leftJoin('students', 'students.id', '=', 'student_attendances.id_student')
                ->join('events', 'events.id', '=', 'student_attendances.event_id')
                ->paginate(15)
                ->withQueryString();

            return response()->json([
                "message" => "Working fine",
                "students" => $students
            ]);
        }
        $students = StudentAttendance::select('*', 'student_attendances.created_at')
            ->leftJoin('students', 'students.id', '=', 'student_attendances.id_student')
            ->join('events', 'events.id', '=', 'student_attendances.event_id')
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
            "message" => "Working fine",
            "students" => $students
        ]);
    }
    public function clearLogs(Request $request)
    {
        $request->validate([
            "event_id" => ["required"]
        ]);
        StudentAttendance::where("event_id", $request->event_id)->delete();

        return back()->with(["success" => "Logs cleared succesfully"]);
    }
}
