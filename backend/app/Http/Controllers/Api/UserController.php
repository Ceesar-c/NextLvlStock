<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role')->get();

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $user = User::create($data);

        $user->load('role');

        return response()->json([
            'message' => 'Usuario creado correctamente.',
            'data' => new UserResource($user),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('role.permissions');

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if (
            isset($data['is_active']) &&
            !$data['is_active'] &&
            $request->user()->id === $user->id
        ) {
            return response()->json([
                'message' => 'No puedes desactivar tu propia cuenta.',
            ], 403);
        }

        if (
            isset($data['role_id']) &&
            $data['role_id'] != $user->role_id &&
            !$request->user()->hasPermission('users.change_role')
        ) {
            return response()->json([
                'message' => 'No tienes permiso para cambiar el rol de un usuario.',
            ], 403);
        }

        $user->update($data);

        $user->load('role.permissions');

        return response()->json([
            'message' => 'El usuario ha sido actualizado correctamente.',
            'data' => new UserResource($user),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return response()->json([
                'message' => 'No puedes desactivar tu propia cuenta.',
            ], 403);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'El usuario ya se encuentra inactivo.',
            ], 409);
        }

        if ($user->role?->isAdmin()) {
            $activeAdmins = User::where('is_active', true)
                ->whereHas('role', fn ($query) => $query->where('name', Role::ADMIN))
                ->count();

            if ($activeAdmins <= 1) {
                return response()->json([
                    'message' => 'No puedes desactivar al último Administrador activo del sistema.',
                ], 403);
            }
        }

        $user->update([
            'is_active' => false,
        ]);

        $user->load('role.permissions');

        return response()->json([
            'message' => 'El usuario ha sido desactivado correctamente.',
            'data' => new UserResource($user),
        ], 200);
    }

    public function activate(User $user)
    {
        if ($user->is_active) {
            return response()->json([
                'message' => 'El usuario ya se encuentra activo.',
            ], 409);
        }

        $user->update([
            'is_active' => true,
        ]);

        $user->load('role.permissions');

        return response()->json([
            'message' => 'El usuario ha sido activado correctamente.',
            'data' => new UserResource($user),
        ], 200);
    }
}
