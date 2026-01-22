<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Partenaire extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'partenaire';
    protected $primaryKey = 'id_partenaire';
    public $timestamps = false;
    const CREATED_AT = 'date_creation';

    protected $fillable = [
        'uuid_partenaire',
        'id_grossiste',
        'raison_sociale',
        'nom_proprietaire',
        'prenom_proprietaire',
        'ifu',
        'rccm',
        'email',
        'telephone',
        'quartier',
        'description',
        'mot_de_passe',
        'solde',
        'statut_general',
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'date_creation' => 'datetime',
            'solde' => 'decimal:2',
            'mot_de_passe' => 'hashed',
        ];
    }

    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    public function getAuthPasswordName()
    {
        return 'mot_de_passe';
    }

    public function grossiste()
    {
        return $this->belongsTo(Grossiste::class, 'id_grossiste', 'id_grossiste');
    }
}
