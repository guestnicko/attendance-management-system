<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FinesController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentAttendanceController;
use App\Http\Controllers\StudentController;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminCodeController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\ImportController;
use App\Http\Resources\Attendance;
use App\Models\User;
use FontLib\Table\Type\name;
use Illuminate\Http\Request as Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin-code', function () {
    return view('auth.admin-code');
})->name('admin.code');

Route::post('/admin-code', [AdminCodeController::class, 'verify'])->name('admin.code.verify');

Route::get('/register', function () {
    if (!session('admin_code_verified')) {
        return redirect()->route('admin.code');
    }
    return view('auth.register');
})->name('register');

Route::middleware('auth')->group(function () {



    // DASHBOARD RELATED ROUTES
    Route::get('/dashboard', [DashboardController::class, 'viewDashboard'])->name('dashboard');


    // PROFILE RELATED ROUTES
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // LOGS RELATED ROUTES
    Route::get('/logs', [LogController::class, 'viewLogs'])->name('logs');
    Route::post('/logs/clear-logs', [LogController::class, 'clearLogs'])->name('logs.clear-logs');

    // STUDENT LOGS - API => VIA CATEGORY
    Route::get('/logs/category', [LogController::class, 'filterByCategory'])->name('fetchViaCategory');

    // STUDENT RELATED ROUTES
    Route::post('/addStudent', [StudentController::class, 'create'])->name('addStudent');
    Route::get('/students', [StudentController::class, 'view'])->name('students');
    Route::delete('/deleteStudent', [StudentController::class, 'delete'])->name('deleteStudent');
    Route::patch('/updateStudent', [StudentController::class, 'update'])->name('updateStudent');
    Route::patch('/updateManyStudent', [StudentController::class, 'updateMany'])->name('multiStudentEdit');
    Route::delete('/deleteManyStudent', [StudentController::class, 'manyDelete'])->name('multiStudentDelete');

    // STUDENT - API => SEARCH AND FILTER
    Route::get('/students/filter', [StudentController::class, 'filter'])->name('fetchStudentViaSearch');
    Route::get('/students/category', [StudentController::class, 'filterByCategory'])->name('fetchStudentsViaCategory');

    // LOG -> API => SEARCH AND FILTER
    Route::get('/logs/filter', [LogController::class, 'filter'])->name('fetchLogViaSearch');
    Route::get('/logs/category', [LogController::class, 'filterByCategory'])->name('fetchLogViaCategory');
    Route::get("/logs/event", [LogController::class, "filterByEvent"])->name("fetchLogViaEvent");

    // FINES => VIA SEARCH AND FILTER
    Route::get('/fines/filter', [FineController::class, 'filter'])->name('fetchFinesViaSearch');
    Route::get('/fines/category', [FineController::class, 'filterByCategory'])->name('fetchFinesViaCategory');
    Route::get("/fines/events", [FineController::class, "filterByEvent"])->name("fetchFinesViaEvent");


    // STUDENT - API => VIA CATEGORY


    // ATTENDANCE RELATED ROUTES
    Route::get('/attendance', [StudentAttendanceController::class, 'view'])->name('attendance');
    Route::post('/student-attendance', [StudentAttendanceController::class, 'recordAttendance'])->name('attendanceStudent');
    Route::get('/studentAttendace/recent', [StudentAttendanceController::class, 'retrieveRecentLogs'])->name('getAttendanceRecent');
    Route::get('/student_Attendance', [StudentAttendanceController::class, 'formatAttendance'])->name('try');    // Route::post

    // EVENTS RELATED ROUTES
    Route::post('/addEvent', [EventController::class, 'create'])->name('addEvent');
    Route::get('/events', [EventController::class, 'view'])->name('events');
    Route::delete('/deleteEvent', [EventController::class, 'delete'])->name('deleteEvent');
    Route::patch('/updateEvent', [EventController::class, 'update'])->name('updateEvent'); // Add this line
    Route::patch('/events/{event}/complete', [EventController::class, 'completeEvent'])->name('events.complete');
    Route::post('/events/{id}/complete', [EventController::class, 'completeEvent'])->name('events.complete');

    //IMPORT RELATED ROUTES
    // Route::get('/pages/excel-import', [ImportController::class, 'index'])->name('pages.excel-import');
    Route::post('/import-student', [ImportController::class, 'import'])->name('importStudent');

    // Fine Settings Routes
    Route::get('/fines', [FineController::class, 'view'])->name('fines.view');
    Route::put('/fines/settings', [FinesController::class, 'updateSettings'])->name('fines.settings.update');
    Route::post("/fines/clear", [FineController::class, "clearLogs"])->name("fines.clear");

    // EXPORT RELATED ROUTES
    Route::post("/fines/export-file", [FineController::class, "exportFile"])->name("fines.export");
    Route::post('/logs/export-file', [LogController::class, 'exportFile'])->name('logs.export');
});


require __DIR__ . '/auth.php';



// THE CODE BELOW IS USED FOR DEVELOPMENT AND TESTING  PURPOSES ONLY
// API TESTING OR DEVELOPMENT
Route::get('/api/test/1', function () {
    $data = new Attendance(User::all());
    return view('test.test_1', compact('data'));
})->name('api_test_1');

Route::get('/api/test/2', function () {
    // $response = Http::get('https://jsonplaceholder.typicode.com/posts');
    // $data = $response->json(); // Convert response to array
    return response()->json([
        "message" => "WOrking",
    ]);
});

Route::post('/api/test/3', function (Request $request) {

    return response()->json([
        "message" => "POST created successfully",
        "data" => $request->name

    ]);
})->name('postAPI');
