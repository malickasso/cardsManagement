<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banque extends Model
{
    protected $table = 'banque';
    protected $primaryKey = 'id_banque';

    protected $fillable = [
        'uuid_banque',
        'nom_banque',
        'code_banque',
        'statut',
    ];
}
