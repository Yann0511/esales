<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;
    protected $fillable = [
        'contenu',
        'produitId',
        'userId',
    ];
    public function produit()
    {
        return $this->belongsTo(Produit::class,  'produitId');
    }
    public function photo()
    {
        return $this->hasMany(Photo::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
