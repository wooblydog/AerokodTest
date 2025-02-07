<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use App\Traits\GeneratesExternalId;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use GeneratesExternalId;

    public function register(UserRegisterRequest $request): Response
    {
        User::query()->create([
            'external_id' => $this->generateExternalId(User::class),
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
        ]);

        return response(['message' => 'Пользователь зарегистрирован'], Response::HTTP_CREATED);
    }

    public function login(UserLoginRequest $request): Response
    {
        $user = User::query()->where('email', $request->email)->first();

        if (is_null($user) || !Hash::check($request->password, $user->password)) {
            return response(['message' => 'Учетные данные неверны'], Response::HTTP_BAD_REQUEST);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response(['token' => $token], Response::HTTP_OK);
    }
}
