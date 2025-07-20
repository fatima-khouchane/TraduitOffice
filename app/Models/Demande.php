<?php

namespace App\Models;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    protected $fillable = [
        'nom_titulaire',
        'nom_demandeur',
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
        'translator_id',
        'user_id',
        'is_online',
        'adresse'

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
    public function translator()
    {
        return $this->belongsTo(User::class, 'translator_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }


}
