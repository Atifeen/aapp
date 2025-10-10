<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticationService
{
    /**
     * Attempt to authenticate a user.
     *
     * @param array $credentials
     * @return bool
     */
    public function authenticate(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return false;
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return false;
        }

        Auth::login($user, isset($credentials['remember']) && $credentials['remember'] === 'on');

        return true;
    }

    /**
     * Get the post-authentication redirect path based on user role.
     *
     * @return string
     */
    public function getRedirectPath()
    {
        if (Auth::user()->role === 'admin') {
            return '/admin/dashboard';
        }
        
        return '/student/dashboard';
    }
}