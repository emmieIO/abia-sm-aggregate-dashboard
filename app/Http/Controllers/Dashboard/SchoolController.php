<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\SchoolService;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function __construct(public SchoolService $schoolService)
    {}


    public function index()
    {
        $schools = $this->schoolService->fetchSchools(20, request('search'));
        // dd($schools);
        return view('pages.schools.index', compact('schools'));
    }
}
