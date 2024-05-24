<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'adresse',
        'numero',
        'montant',
        'statut',
        'auteurId',
        'livreurId'
    ];

    public function auteur()
    {
        return $this->belongsTo(User::class, 'auteurId');
    }

    public function livreur()
    {
        return $this->belongsTo(User::class, 'livreurId');
    }

    public function produits()
    {
        return $this->belongsToMany(Produit::class, "commande_produits", "commandeId", "produitId");
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function transaction()
    {
        return $this->transactions->where('statut', 1)->first();
    }
}
