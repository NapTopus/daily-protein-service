<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\RefreshToken;
use App\Models\User;
use Str;

class AuthController extends Controller
{
    /**
     *  @OA\Post(
     *      path="/api/auth/register",
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
     *      path="/api/auth/login",
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
     *              @OA\Property(property="authToken", type="string", example="7|654FPqe0oZzujUfiCl8VYnsD09cnEhXxNbcrvwVG722155b9"),
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

        $accessToken  = $user->createToken('auth_token')->plainTextToken;
        $refreshToken = Str::random(64);
        RefreshToken::create([
            'user_id'    => $user->id,
            'token'      => hash('sha256', $refreshToken),
            'expires_at' => now()->addDays(7)
        ]);

        $SEVEN_DAYS = 60 * 24 * 7;
        $path       = '/';
        $domain     = null;
        $secure     = env('APP_DEBUG') ? false : true;
        $httpOnly   = true;
        return response()
            ->json(['authToken' => $accessToken])
            ->cookie('refreshToken', $refreshToken, $SEVEN_DAYS, $path, $domain, $secure, $httpOnly);
    }

    /**
     *  @OA\Get(
     *      path="/api/auth/refresh",
     *      summary="透過 cookie 的 refresh token 拿 auth token",
     *      tags={"Auth"},
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="authToken", type="string", example="7|654FPqe0oZzujUfiCl8VYnsD09cnEhXxNbcrvwVG722155b9"),
     *          )
     *      ),
     *  )
     */
    public function refresh()
    {
        $refreshTokenFromCookie = request()->cookie('refreshToken');

        if (!$refreshTokenFromCookie) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $refreshToken = RefreshToken::where('token', hash('sha256', $refreshTokenFromCookie))
            ->where('revoked', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$refreshToken) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user        = $refreshToken->user;
        $accessToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['authToken' => $accessToken]);
    }
}
