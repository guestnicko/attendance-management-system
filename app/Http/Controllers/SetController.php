<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetController extends Controller
{
    //View Set page, and display all sets
    public function view(Request $request){

        $program = $request->get('program', 'BSIT');

        // Necessary query to get sets
        $query = Student::select('s_set', 's_program', DB::raw('COUNT(*) as total_students'))
            ->groupBy('s_set', 's_program')
            ->orderBy('s_program')
            ->orderBy('s_set');

        if(in_array($program, ['BSIT', 'BSIS'])){
            $query->where('s_program', $program);
        }

        $sets = $query->get();

        // Query to get count of all sets
        $countOfSets = Student::select(DB::raw('COUNT(DISTINCT s_set) AS countSets'))
        ->groupBy('s_program')
        ->orderBy('s_program')
        ->get();

        $totalSets = $countOfSets->sum('countSets');

        return view('pages.sets', compact('sets', 'totalSets'));
    }

}
