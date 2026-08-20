<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const ADMIN = 'Administrador';

    protected $fillable = [
        'name',
        'is_active',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function isAdmin(): bool
    {
        return $this->name === self::ADMIN;
    }
}
