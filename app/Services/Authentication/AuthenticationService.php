<?php

namespace App\Services\Authentication;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthenticationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function authenticateUser(string $username, string $password)
    {
        // Implementation for user authentication
        try {
            if(Auth::attempt([
                'username' => $username,
                'password' => $password
            ])){
                session()->regenerate();
                return Auth::user();
            }
            return null;
        } catch (\Exception $th) {
            Log::error('Authentication failed: ' . $th->getMessage());
            return null;
        }
    }

    public function logoutUser()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return true;
    }
}
