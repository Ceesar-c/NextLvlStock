<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if(!$user || !Hash::check($data['password'], $user->password)){
            return response()->json([
                'message' => 'Usuario y/o contraseña incorrectos.',
            ], 401);
        }

        $user->load('role');

        if(!$user->is_active){
            return response()->json([
                'message' => 'Esta cuenta se encuentra desactivada. Contacta al administrador.',
            ], 403);
        }

        if (!$user->role || !$user->role->is_active) {
            return response()->json([
                'message' => 'El rol asignado a esta cuenta se encuentra inactivo. Contacta al administrador.',
            ], 403);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso.',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 200);
    }

    public function me(Request $request)
    {
        $user = $request->user()->load('role.permissions');

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_active' => $user->is_active,
                ],
                'role' => [
                    'id' => $user->role->id,
                    'name' => $user->role->name,
                ],
                'permissions' => $user->role->permissions->pluck('name')->values(),
            ],
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

         return response()->json([
            'message' => 'Sesión finalizada correctamente.',
        ], 200);
    }
}
