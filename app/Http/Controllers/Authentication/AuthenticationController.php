<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\UpdatePasswordRequest;
use App\Services\Authentication\AuthenticationService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AuthenticationController extends Controller
{
    public function __construct(public AuthenticationService $authenticationService)
    {
    }
    public function showLogin()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if (
            $this->authenticationService->authenticateUser(
                $request->login,
                $request->password
            )
        ) {
            return redirect()->route('dashboard.index');
        }
        return redirect()->back()
            ->withInput($request->only('login'))
            ->withErrors(['login' => 'The provided credentials are incorrect. Please try again.']);

    }

    public function logout(Request $request)
    {
        $this->authenticationService->logoutUser();

        return redirect()->route('login.show');
    }

    public function editPassword()
    {
        return view('pages.auth.change-password');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $this->authenticationService->updatePassword(
            $request->user(),
            $request->password
        );

        return back()->with('status', 'password-updated');
    }
}
