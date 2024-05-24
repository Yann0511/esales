<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'montan',
        'statut',
        'commandeId',
    ];
    public function commande()
    {
        return $this->belongsTo(Commande::class, 'commandeId');
    }
}
