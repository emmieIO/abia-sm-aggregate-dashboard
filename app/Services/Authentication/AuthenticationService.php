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

    public function authenticateUser(string $login, string $password)
    {
        // Implementation for user authentication
        try {
            $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            if(Auth::attempt([
                $field => $login,
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

    public function updatePassword($user, string $newPassword)
    {
        return $user->update([
            'password' => $newPassword
        ]);
    }
}
