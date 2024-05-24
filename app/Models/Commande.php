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
}
