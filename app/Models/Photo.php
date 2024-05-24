<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
    protected $fillable = [
        'chemin',
        'CommentaireId',
        'ProduitId',
    ];
    public function commentaire()
    {
        return $this->belongsTo(Commentaire::class, 'CommentaireId');
    }
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'ProduitId');
    }
}
