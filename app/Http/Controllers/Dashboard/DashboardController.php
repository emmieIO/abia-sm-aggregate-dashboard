<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        public DashboardService $dashboardService
        )
    {
    }

    public function index()
    {
        $age_distribution = $this->dashboardService->fetchStudentAgeDistribution();
        $gender_distribution = $this->dashboardService->fetchgenderDistribution();
        $school_count = $this->dashboardService->getNumberofSchools();
        $student_count = $this->dashboardService->getNumberofStudents();
        $staff_count = $this->dashboardService->getNumberofStaffs();
        $parent_count = $this->dashboardService->getNumberofParents();
        $top_students = $this->dashboardService->fetchTopFiveTopPerformingStudents();
        $least_students = $this->dashboardService->fetchTopFiveLowPerformingStudents();
        $subjects = $this->dashboardService->fetchSubjects();
        // dd($top_math_students);
        return view('pages.dashboard', compact(
            'school_count',
            'student_count',
            'staff_count',
            'parent_count',
            'gender_distribution', 
            'age_distribution',
            'top_students',
            'least_students',
            'subjects'
        ));
    }

    public function fetchTopStudentsBySubject(Request $request)
    {
        $subject = $request->input('subject', 'graphic Design');
        $top_students = $this->dashboardService->fetchTopFiveTopPerformingStudents($subject);
        return response()->json($top_students);
    }

    public function fetchLowStudentsBySubject(Request $request)
    {
        $subject = $request->input('subject', 'graphic Design');
        $low_students = $this->dashboardService->fetchTopFiveLowPerformingStudents($subject);
        return response()->json($low_students);
    }
}
