<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['demande_id', 'contenu'];

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }
}
