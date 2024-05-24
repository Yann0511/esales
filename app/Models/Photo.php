<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
    protected $fillable = [
        'chemin',
        'photoable_type',
        'photoable_id',
    ];
    public function commentaire()
    {
        return $this->belongsTo(Commentaire::class, 'commentaireId');
    }
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produitId');
    }

    public function photoable()
    {
        return $this->morphTo('photoable');
    }

    
}
