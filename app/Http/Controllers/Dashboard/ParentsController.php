<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\ParentService;
use Illuminate\Http\Request;

class ParentsController extends Controller
{
    public function __construct(public ParentService $parentService)
    {
    }

    public function index()
    {
        $parents = $this->parentService->fetchParents(20, request('search'));
        // dd($parents);
        return view('pages.parents', compact('parents'));
    }
}
