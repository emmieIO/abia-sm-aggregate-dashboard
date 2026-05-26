<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\ParentService;
use Illuminate\Http\Request;

class ParentsController extends Controller
{
    public function __construct(public ParentService $parentService) {}

    public function index(Request $request)
    {
        $search = $request->input('search');
        $filters = [
            'school_id' => $request->input('school_id'),
        ];

        $parents = $this->parentService->fetchParents(20, $search, $filters);
        $schools = $this->parentService->fetchSchools();

        return view('pages.parents', compact('parents', 'schools'));
    }
}
