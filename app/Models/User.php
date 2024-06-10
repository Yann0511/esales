<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Authorization\AuthorizationUserTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, AuthorizationUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenoms',
        'telephone',
        'adresse',
        'photo',
        'roleId',
        'emailVerifiedAt',
        'email',
        'password',
        'statut',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'emailVerifiedAt' => 'datetime',
    ];

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class, 'userId');
    }
    public function role()
    {
        return $this->belongsTo(Role::class, 'roleId');
    }

    public function commamdes()
    {
        return $this->hasMany(Commande::class, 'auteurId');
    }

    public function commamdesALivrer()
    {
        return $this->hasMany(Commande::class, 'livreurId');
    }

    public function panier()
    {
        return $this->hasOne(Panier::class);
    }

    public function historiques()
    {
        return $this->hasMany(LogActivity::class);
    }

    public function wishLists()
    {
        return $this->hasMany(WishList::class, 'userId');
    }

    public function adresses()
    {
        return $this->hasMany(Adresse::class, 'userId');
    }
}
