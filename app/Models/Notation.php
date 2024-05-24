<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notation extends Model
{
    use HasFactory;
    protected $fillable = [
        'note',
        'produitId',
        'userId',

    ];
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produitId');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
