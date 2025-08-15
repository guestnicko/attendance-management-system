<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetController extends Controller
{
    //View Set page, and display all sets
    public function view(){
        $sets = Student::select('s_set', 's_program')->distinct()->get();
        return view('pages.sets', compact('sets'));
    }

}
