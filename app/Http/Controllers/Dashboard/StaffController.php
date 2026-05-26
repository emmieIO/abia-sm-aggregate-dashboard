<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\TeacherService;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function __construct(public TeacherService $teacherService) {}

    public function index(Request $request)
    {
        $search = $request->input('search');
        $filters = [
            'school_id' => $request->input('school_id'),
            'depart_id' => $request->input('depart_id'),
            'designation' => $request->input('designation'),
            'sex' => $request->input('sex'),
        ];

        $staffs = $this->teacherService->fetchStaffs(20, $search, $filters);
        $schools = $this->teacherService->fetchSchools();
        $departments = $this->teacherService->fetchDepartments();
        $designations = $this->teacherService->fetchDesignations();

        return view('pages.teachers', compact('staffs', 'schools', 'departments', 'designations'));
    }
}
