<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Services\StudentService;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function __construct(
        public StudentService $studentService,
        public DashboardService $dashboardService
    ) {}

    public function index(Request $request)
    {
        $search = $request->input('search');
        $session_id = $request->input('session_id');
        $schoolId = $request->input('school_id');

        $alumni = $this->studentService->fetchAlumni(30, $search, $session_id, $schoolId);
        $sessions = $this->dashboardService->fetchSessions();
        $schools = $this->studentService->fetchSchools();

        return view('pages.alumni', compact('alumni', 'sessions', 'schools'));
    }
}
