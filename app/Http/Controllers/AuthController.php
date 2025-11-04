<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;

class AuthController extends Controller
{
    /**
     *  @OA\Post(
     *      path="/api/register",
     *      summary="用戶註冊",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email", "password", "password_confirmation"},
     *              type="object",
     *              @OA\Property(property="name", type="string", example="your-name"),
     *              @OA\Property(property="email", type="string", example="example@your-mail.com"),
     *              @OA\Property(property="password", type="string", example="your-password123", description="至少 8 個字元，至少 1 個大寫字母、1 個小寫字母、1 個數字"),
     *              @OA\Property(property="passwordConfirmation", type="string", example="your-password123")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          ref="#/components/responses/InvalidInput"
     *      )
     *  )
     */
    public function register(RegisterRequest $request)
    {
        User::create($request->validated());
    }

    /**
     *  @OA\Post(
     *      path="/api/login",
     *      summary="用戶登入",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              type="object",
     *              @OA\Property(property="email", type="string", example="example@your-mail.com"),
     *              @OA\Property(property="password", type="string", example="your-password"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="token", type="string", example="7|654FPqe0oZzujUfiCl8VYnsD09cnEhXxNbcrvwVG722155b9"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          ref="#/components/responses/InvalidInput"
     *      )
     *  )
     */
    public function login(LoginRequest $request)
    {
        if (!auth()->attempt($request->validated())) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user = auth()->user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
        ]);
    }
}
