<?php

namespace App\Models;
use Illuminate\Support\Facades\Storage;
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
        'status',
    ];

    protected $casts = [
        'documents' => 'array',
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($demande) {
            foreach ($demande->fichiers as $fichier) {
                Storage::disk('public')->delete($fichier->chemin);
                $fichier->delete();
            }
        });
    }

    public function fichiers()
    {
        return $this->hasMany(FichierDemande::class);
    }
}
