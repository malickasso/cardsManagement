<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carte extends Model
{
    protected $table = 'carte';
    protected $primaryKey = 'id_carte';

    protected $fillable = [
        'uuid_carte',
        'cree_par_admin',
        'numero_carte',
        'id_type_carte',
        'id_banque',
        'id_grossiste',
        'date_expiration',
        'statut_carte',
        'date_enregistrement',
    ];

    public function typeCarte()
    {
        return $this->belongsTo(TypeCarte::class, 'id_type_carte', 'id_type_carte');
    }

    public function banque()
    {
        return $this->belongsTo(Banque::class, 'id_banque', 'id_banque');
    }

    public function grossiste()
    {
        return $this->belongsTo(UserDetail::class, 'id_grossiste', 'id_user_detail');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'cree_par_admin', 'id_admin');
    }
}
