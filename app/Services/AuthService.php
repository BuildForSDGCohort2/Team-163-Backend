<?php

namespace App\Services;

use App\Traits\ModelTraits;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /*
     * The Base Repository Implementation
     */
    use ModelTraits;

    /**
     * Create a new User.
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    public function register($data)
    {
        $user = new User($data);
        $user->uuid = $this->generateUniqueData('User', 'uuid');
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }

    /**
     * Login User.
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    public function login($data)
    {
        if (!$token = Auth::attempt($data)) {
            return false;
        }

        return $this->respondWithToken($token);
    }

    /**
     * Logout user.
     *
     * @return Response
     */
    public function logout()
    {
        return Auth::logout();
    }

    /**
     * Get the user object of an authenticated user.
     */
    public function user()
    {
        return Auth::user();
    }

    /**
     * Update users  table.
     *
     * @param \App\User $user
     */
    public function update(array $data, $user)
    {
        $user->update($data);
    }

    /**
     * Verify password match.
     *
     * @param string $password
     * @param string $email
     */
    public function verifyPassword($password, $email)
    {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return true;
        }

        return false;
    }

    /**
     * Retrieve Details of logged in user.
     *
     * @return object
     */
    public function details()
    {
        $user = Auth::user();

        // return $user->makeHidden(['created_at', 'updated_at']);
    }

    /**
     * Verify if a user with an email address and password exists.
     *
     * @return boolean
     */
    public function verifyUser(array $data)
    {
        return Auth::attempt([
            'email' => $data['old_email'],
            'password' => $data['password'],
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        // return $this->respondWithToken(Auth::guard()->refresh());
    }

    /**
     * return user token and user details when login success.
     *
     * @return array
     */
    private function respondWithToken($token)
    {
        return [
            'token' => $token,
            'token_type' => 'Bearer',
            // 'expires_in' => env('JWT_TTL'),
            'email' => Auth::user()->email,
            'uuid' => Auth::user()->uuid,
            'dashboard_type' => Auth::user()->dashboard_type,
            'verified' => is_null(Auth::user()->email_verified_at) ? false : true,
            'role' => Auth::user()->role->name,
        ];
    }
}
