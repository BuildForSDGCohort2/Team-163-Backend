<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\UserServices;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $authService;
    private $userService;

    public function __construct(AuthService $auth, UserServices $user)
    {
        $this->authService = $auth;
        $this->userService = $user;
    }

    /**
     * Login a user.
     *
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (!$login = $this->authService->login($request->only(['email', 'password']))) {
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
        return $this->authService->refresh();
    }

    /**
     * Logout an authenticated user.
     *
     * @return JsonResponse
     */
    public function logout()
    {
        $this->authService->logout();

        return $this->successResponse(201, ['message' => 'Logged out successfully']);
    }

    /**
     * Get the details of a logged in user.
     *
     * @return JsonResponse
     */
    public function details()
    {
        $user = $this->authService->details();

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
        $user = $this->authService->user();
        $this->user->update($request->all(), $user);

        return $this->successResponse(200, ['message' => 'Updated successfully']);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|unique:users',
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $data = $request->all();
        $data['role_id'] = 2;

        try {
            return $this->authService->register($data);

            return response()->json(['success' => true, 'message' => 'Registration successful'], 201);
        } catch (\Throwable $e) {
            throw $e;

            return response()->json(['success' => false, 'message' => 'An error occured'], 500);
        }
    }
}
