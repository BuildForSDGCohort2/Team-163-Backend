<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\UserServices;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $auth;
    private $user;
    private $image;

    public function __construct(AuthService $auth, UserServices $user)
    {
        $this->auth = $auth;
        $this->user = $user;
    }

    /**
     * Login a user.
     *
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (!$login = $this->auth->login($request->only(['email', 'password']))) {
            return $this->errorResponse(422, ['error' => 'Invalid email or password']);
        }

        return $this->successResponse(200, [
            'authentication' => $login,
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->auth->refresh();
    }

    /**
     * Logout an authenticated user.
     *
     * @return JsonResponse
     */
    public function logout()
    {
        $this->auth->logout();

        return $this->successResponse(201, ['message' => 'Logged out successfully']);
    }

    /**
     * Get the details of a logged in user.
     *
     * @return JsonResponse
     */
    public function details()
    {
        $user = $this->auth->details();

        return $this->successResponse(200, ['data' => $user]);
    }

    /**
     * Validate Login request.
     *
     * @param \Illuminate\Http\Request $request
     */
    private function validateLogin($request)
    {
        return $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    }

    /**
     * Update user resource.
     */
    public function update(Request $request)
    {
        $user = $this->auth->user();
        $this->user->update($request->all(), $user);

        return $this->successResponse(200, ['message' => 'Updated successfully']);
    }
}
