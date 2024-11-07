<?php

namespace App\Http\Controllers;

use App\Models\Users\Users;
use App\Libraries\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email|unique:users,email',
            'nama'     => 'required|string',
            'umur'     => 'required|integer|min:1',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return JsonResponse::Failed($validator->errors()->first());
        }

        try {
            $user = new Users();
            $user->email = $request->email;
            $user->nama = $request->nama;
            $user->umur = $request->umur;
            $user->password = Hash::make($request->password);
            $user->role = 3;
            $user->save();

            $token = JWTAuth::fromUser($user);

            return JsonResponse::Success(
                [
                    'email' => $user->email,
                    'token' => $token,
                ],
                'Registration successful'
            );
        } catch (JWTException $e) {
            return JsonResponse::BadDynamic('Could not create token', 500);
        }

        return JsonResponse::Failed('Registration Failed');
    }

    /**
     * Login
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return JsonResponse::Failed($validator->errors()->first());
        }

        try {
            $user = Users::where('email', $request->email)->first();
            if (!$user) {
                return JsonResponse::NotFound('User not found');
            }

            if ($user && Hash::check($request->password, $user->password)) {
                $token = JWTAuth::fromUser($user);
                return JsonResponse::Success(
                    [
                        'email' => $user->email,
                        'token' => $token,
                    ],
                    'Login successful'
                );
            }
        } catch (JWTException $e) {
            return JsonResponse::BadDynamic('Could not create token', 500);
        }

        return JsonResponse::BadDynamic('Invalid Credentials', 400);
    }

    /**
     * Logout
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return JsonResponse::Success([], 'Successfully logged out');
    }
}