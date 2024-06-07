<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanierProduit extends Model
{
    use HasFactory;

    protected $fillable = [
        'panierId',
        'produitId',
        'quantite',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produitId');
    }
}
