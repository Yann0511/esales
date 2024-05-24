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
        return $this->belongsToMany(Produit::class, "panier_produits", "panierId", "produitId");
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
