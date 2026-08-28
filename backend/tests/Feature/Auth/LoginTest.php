<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $role = Role::create([
            'name' => 'Vendedor',
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Inicio de sesión exitoso.',
            ])
            ->assertJsonPath('data.user.id', $user->id)
            ->assertJsonPath('data.user.email', $user->email);

        $this->assertNotEmpty($response->json('data.token'));
    }
    
    public function test_user_cannot_login_when_role_is_inactive(): void
    {
        $role = Role::create([
            'name' => 'Vendedor',
            'is_active' => false,
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertStatus(403)
            ->assertJson([
                'message' => 'El rol asignado a esta cuenta se encuentra inactivo. Contacta al administrador.',
            ]);
    }

    public function test_inactive_user_cannot_login(): void
    {
        $role = Role::create([
            'name' => 'Vendedor',
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
            'is_active' => false,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertStatus(403)
            ->assertJson([
                'message' => 'Esta cuenta se encuentra desactivada. Contacta al administrador.',
            ]);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $role = Role::create([
            'name' => 'Vendedor',
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Usuario y/o contraseña incorrectos.',
            ]);
    }
}