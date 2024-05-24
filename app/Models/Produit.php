<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'prix',
        'qte',
        'categorieId',

    ];
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorieId');
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    public function note()
    {
        $somme =  $this->belongsTo(Notation::class, 'produitId')->sum('note');
        $count =  $this->belongsTo(Notation::class, 'produitId')->count();

        return $somme/$count ;
    }

    public function photos()
    {
        return $this->morphMany(Photo::class,'photoable');
    }

}
