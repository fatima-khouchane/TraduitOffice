<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'cin',
        'telephone',
        'date_debut',
        'date_fin',
        'documents',
        'prix_total',
        'langue_origine',
        'langue_souhaitee',
        'remarque',
    ];

    protected $casts = [
        'documents' => 'array',
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];
    public function fichiers()
    {
        return $this->hasMany(FichierDemande::class);
    }
}
