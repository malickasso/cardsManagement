<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banque extends Model
{
    protected $table = 'banque';
    protected $primaryKey = 'id_banque';

    protected $fillable = [
        'uuid_banque',
        'cree_par_admin',
        'nom_banque',
        'code_banque',
        'statut',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'cree_par_admin', 'id_admin');
    }
}
