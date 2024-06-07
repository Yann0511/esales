<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    use HasFactory;
    protected $fillable = [
        'userId'
    ];

    public function produits()
    {
        return $this->hasMany(PanierProduit::class, "panierId");
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
