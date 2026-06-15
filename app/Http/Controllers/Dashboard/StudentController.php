<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct(public StudentService $studentService) {}

    public function school(Request $request, string $school)
    {
        $selectedSchool = $this->studentService->fetchSchool($school);
        abort_unless($selectedSchool, 404);

        $search = $request->input('search');
        $classId = $request->input('class_id');
        $filters = [
            'school_id' => $school,
            'class_id' => $classId,
            'sex' => $request->input('sex'),
        ];

        $schools = collect([$selectedSchool]);
        $classes = $this->studentService->fetchClasses($school);
        $classSummaries = $this->studentService->fetchClassSummaries($school);
        $selectedClass = $classes->firstWhere('id', (int) $classId);
        $requiresClassSelection = true;
        $students = $classId
            ? $this->studentService->fetchStudents(20, $search, $filters)
            : null;

        return view('pages.students', compact('students', 'schools', 'classes', 'selectedSchool', 'classSummaries', 'selectedClass', 'requiresClassSelection'));
    }
}
