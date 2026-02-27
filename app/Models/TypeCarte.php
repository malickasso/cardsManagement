<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeCarte extends Model
{
    protected $table = 'type_carte';
    protected $primaryKey = 'id_type_carte';

    protected $fillable = [
        'uuid_type_carte',
        'cree_par_admin',
        'nom_type_carte',
        'description',
        'statut',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'cree_par_admin', 'id_admin');
    }
}
