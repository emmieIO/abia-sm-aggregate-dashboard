<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\SchoolService;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function __construct(public SchoolService $schoolService) {}

    public function index(Request $request)
    {
        $search = $request->input('search');
        $filters = [
            'lga' => $request->input('lga'),
            'status' => $request->input('status'),
        ];

        $schools = $this->schoolService->fetchSchools(20, $search, $filters);
        $lgas = $this->schoolService->fetchLgas();

        return view('pages.schools.index', compact('schools', 'lgas'));
    }
}
