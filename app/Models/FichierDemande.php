<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FichierDemande extends Model
{
    protected $fillable = [
        'demande_id',
        'chemin',
    ];

    public function demande()
    {

        return $this->belongsTo(Demande::class);
    }
}
