<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => $request->is_admin,
            'password' => bcrypt($request->password)
        ]);
        $token = $user->createToken('auth-token')->plainTextToken;
        $user->token = $token;

        $resource = new UserResource($user);
        return $resource->response()->setStatusCode(201);
    }

    public function login(LoginRequest $request)
    {

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'message' => 'As credenciais informadas são inválidas.'
            ], 401);
        }

        $user = $request->user();
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function validateToken(Request $request)
    {
        if ($token = $request->bearerToken()) {
            $user = auth('sanctum')->user();
            $user->token = $token;
            return new UserResource($user);
        }
    }


    public function logout()
    {
        /** @var User $user */
        $user = Auth()->user();
        $user->tokens()->delete();

        return response(['message' => 'Logout realizado com sucesso.'], 200);
    }
}
