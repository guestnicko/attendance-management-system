<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    /** @use HasFactory<\Database\Factories\StudentAttendanceFactory> */
    use HasFactory;

    protected $fillable = [
        "attend_checkIn",
        "attend_checkOut",
        "attend_afternoon_checkIn",
        "attend_afternoon_checkOut",
        "event_id",
        "student_rfid",
        "morning_attendance",
        "afternoon_attendance",
        "id_student",
    ];

    protected $casts = [
        'morning_attendance' => 'boolean',
        'afternoon_attendance' => 'boolean'
    ];
}
