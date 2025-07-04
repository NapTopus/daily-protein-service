<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
     *              @OA\Property(property="password", type="string", example="your-password"),
     *              @OA\Property(property="password_confirmation", type="string", example="your-password")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="驗證失敗",
     *          @OA\JsonContent(ref="#/components/schemas/InputError")
     *      )
     *  )
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create($validated);
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
     *          description="驗證失敗",
     *          @OA\JsonContent(ref="#/components/schemas/InputError")
     *      )
     *  )
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!auth()->attempt($validated)) {
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
