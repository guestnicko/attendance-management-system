<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Fine;
use App\Models\Student;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function create(Request $request)
    {
        date_default_timezone_set('Asia/Manila');

        $fields = $request->validate([
            "event_name" => ['required'],
            "date" => ['required', 'date'],
            "checkIn_start" => ['required', "date_format:H:i"],
            "checkIn_end" => ['required', "date_format:H:i", "after:checkIn_start"],
            "checkOut_start" => ['required', "date_format:H:i", "after:checkIn_end"],
            "checkOut_end" => ['required', "date_format:H:i", "after:checkOut_start"],
            "fines_amount" => ["required", "integer"]
        ]);

        if (strtotime($fields['date']) < strtotime(date("M d, Y"))) {
            return back()->withErrors(["date" => "Past dates cannot be selected in creating an event"]);
        }

        if ($request->wholeDay) {

            $wholeDay = Event::where('date', '=', $request->date)
                ->whereNotNull('afternoon_checkIn_start')
                ->orderBy('created_at', 'desc')
                ->get()
                ->first();

            if (!empty($wholeDay)) {
                return back()->withErrors(['failed' => "There is already a whole day event created for this date"]);
            }

            $request->validate([
                "wholeDay" => ['required'],
                "afternoon_checkIn_start" => ['required', "date_format:H:i", "after:checkOut_end"],
                "afternoon_checkIn_end" => ['required', "date_format:H:i", "after:afternoon_checkIn_start"],
                "afternoon_checkOut_start" => ['required', "date_format:H:i", "after:afternoon_checkIn_end"],
                "afternoon_checkOut_end" => ['required', "date_format:H:i", "after:afternoon_checkOut_start"],
            ]);


            // Create event with all required fields
            Event::create([
                'event_name' => $fields['event_name'],
                'checkIn_start' => $fields['checkIn_start'],
                'checkIn_end' => $fields['checkIn_end'],
                'checkOut_start' => $fields['checkOut_start'],
                'checkOut_end' => $fields['checkOut_end'],
                "afternoon_checkIn_start" => $request->afternoon_checkIn_start,
                "afternoon_checkIn_end" => $request->afternoon_checkIn_end,
                "afternoon_checkOut_start" => $request->afternoon_checkOut_start,
                "afternoon_checkOut_end" => $request->afternoon_checkOut_end,
                'date' => $fields['date'],
                "fines_amount" => $request->fines_amount,

                'admin_id' => Auth::id(), // Get the current authenticated user's ID
                "isWholeDay" => "true"
            ]);

            return redirect()->route('dashboard')->with(["success" => "Event created successfully"]);
        }

        // Create event with all required fields
        Event::create([
            "fines_amount" => $request->fines_amount,
            'event_name' => $fields['event_name'],
            'checkIn_start' => $fields['checkIn_start'],
            'checkIn_end' => $fields['checkIn_end'],
            'checkOut_start' => $fields['checkOut_start'],
            'checkOut_end' => $fields['checkOut_end'],
            'date' => $fields['date'],
            'admin_id' => Auth::id() // Get the current authenticated user's ID
        ]);

        return redirect()->route('dashboard')->with(["success" => "Event created successfully"]);
    }

    public function view()
    {
        $pendingEvents = Event::where('event_status', "pending")->get();
        $completedEvents = Event::where('event_status', "completed")->get();
        $deletedEvents = Event::onlyTrashed()->get();

        // Debug: Log the counts
        Log::info('Events loaded:', [
            'pending' => $pendingEvents->count(),
            'completed' => $completedEvents->count(),
            'deleted' => $deletedEvents->count()
        ]);

        return view('pages.events', compact('pendingEvents', 'completedEvents', "deletedEvents"));
    }

    public function delete(Request $request)
    {
        $request->validate([
            "id" => ['required']
        ]);

        Event::find($request->id)->delete();
        return back()->with(["success" => "Event deleted successfully"]);
    }

    public function update(Request $request)
    {

        $request->validate([
            "event_name" => ['required'],
            "date" => ['required', 'date'],
            "checkIn_start" => ['required'],
            "checkIn_end" => ['required', "after:checkIn_start"],
            "checkOut_start" => ['required', "after:checkIn_end"],
            "checkOut_end" => ['required', "after:checkOut_start"],
            "fines_amount" => ["required", "integer"]

        ]);

        $event = Event::find($request->id);

        if (!$event) {
            return back()->with('error', 'Event not found');
        }

        if ($request->wholeDay) {
            $field =  $request->validate([
                "wholeDay" => ['required'],
                "afternoon_checkIn_start" => ['required', "after:checkOut_end"],
                "afternoon_checkIn_end" => ['required', "after:afternoon_checkIn_start"],
                "afternoon_checkOut_start" => ['required', "after:afternoon_checkIn_end"],
                "afternoon_checkOut_end" => ['required', "after:afternoon_checkOut_start"],
            ]);

            $event->update($field);
        }

        $event->update([
            'event_name' => $request->event_name,
            'date' => $request->date,
            'checkIn_start' => $request->checkIn_start,
            'checkIn_end' => $request->checkIn_end,
            'checkOut_start' => $request->checkOut_start,
            'checkOut_end' => $request->checkOut_end,
            "fines_amount" => $request->fines_amount
        ]);

        return back()->with('success', 'Event updated successfully');
    }

    // Add this method to check fines in real-time
    public function checkCurrentFines(Event $event)
    {
        $currentTime = now()->format('H:i');

        // Only calculate fines if we're past the check-out end time
        if ($currentTime > $event->checkOut_end) {
            app(FineController::class)->calculateEventFines($event);
        }

        return back()->with('success', 'Fines calculated successfully');
    }

    // Update the completeEvent method
    public function completeEvent($id)
    {
        // FIND EVENT
        $event = Event::where("id", $id);
        // PROCESS ALL ABSENT STUDENT AND INCLUDE THE FINES
        $temp = $event->get();
        if ($temp->count() > 0) {
            app(FineController::class)->calculateEventFines($temp->first());
            $event = $event->update(['event_status' => 'completed']); // Status value is now properly quoted
        } else {
            return back()->withErrors(["failed" => "Event doesn't exist. Try again"]);
        }
        return redirect()->route('logs')->with('success', 'Event completed and fines calculated successfully');
    }
}
