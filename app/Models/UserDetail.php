<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class UserDetail extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $table = 'users_details';
    protected $primaryKey = 'id_user_detail';

    protected $fillable = [
        'uuid_user',
        'type_user',
        'raison_sociale',
        'nom_proprietaire',
        'prenom_proprietaire',
        'ifu',
        'rccm',
        'telephone',
        'email',
        'quartier',
        'description',
        'mot_de_passe',
        'cree_par_admin',
        'id_grossiste',
        'solde',
        'statut_general',
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    protected $casts = [
        'solde' => 'decimal:2',
        'date_creation' => 'datetime',
    ];

    // Désactiver les timestamps automatiques de Laravel
    public $timestamps = false;

    const CREATED_AT = 'date_creation';
    const UPDATED_AT = null;

    // Override pour utiliser 'mot_de_passe'
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    // Scopes pour filtrer par type
    public function scopeGrossiste($query)
    {
        return $query->where('type_user', 'GROSSISTE');
    }

    public function scopePartenaire($query)
    {
        return $query->where('type_user', 'PARTENAIRE');
    }

    // Vérification du type
    public function isGrossiste()
    {
        return $this->type_user === 'GROSSISTE';
    }

    public function isPartenaire()
    {
        return $this->type_user === 'PARTENAIRE';
    }

    // Relations
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'cree_par_admin', 'id_admin');
    }

    public function parentGrossiste()
    {
        return $this->belongsTo(UserDetail::class, 'id_grossiste', 'id_user_detail');
    }

    public function partenaires()
    {
        return $this->hasMany(UserDetail::class, 'id_grossiste', 'id_user_detail')
                    ->where('type_user', 'PARTENAIRE');
    }

    // Guard name pour Spatie Permission
    protected $guard_name = 'web';
}
