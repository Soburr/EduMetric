<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Test;
use App\Models\User;

class LandingController extends Controller
{
    public function index()
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalClasses  = SchoolClass::count();
        $totalTests    = Test::count();

        return view('landing', compact(
            'totalStudents',
            'totalTeachers',
            'totalClasses',
            'totalTests'
        ));
    }
}