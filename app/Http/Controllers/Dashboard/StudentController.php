<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct(public StudentService $studentService)
    {
    }
    public function index(Request $request)
    {
        $query = $request->input('search');
        $students = $this->studentService->fetchStudents(20, $query);

        return view('pages.students', compact('students'));
    }


}
