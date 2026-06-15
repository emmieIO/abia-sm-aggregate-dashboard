<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use Illuminate\Http\Request;

class PenaltyController extends Controller
{
    public function __construct(public StudentService $studentService) {}

    public function index(Request $request)
    {
        $search = $request->input('search');
        $filters = [
            'school_id' => $request->input('school_id'),
        ];

        $penalties = $this->studentService->fetchPenalties(30, $search, $filters);
        $schools = $this->studentService->fetchSchools();

        return view('pages.penalties', compact('penalties', 'schools'));
    }
}
