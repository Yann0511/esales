<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'slug'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($role) {
            $role->slug = strtolower(str_replace(' ', '.', $role->nom));
        });
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, "permission_roles", "roleId", "permissionId");
    }
}
