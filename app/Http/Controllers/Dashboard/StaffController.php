<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\TeacherService;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function __construct(public TeacherService $teacherService)
    {
    }
    public function index()
    {
        $staffs = $this->teacherService->fetchStaffs(20, request('search'));
        // dd($staffs);
        return view('pages.teachers', compact('staffs'));
    }
}
