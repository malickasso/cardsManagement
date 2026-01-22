<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    /**
     * The guard name for authentication.
     *
     * @var string
     */
    protected $guard = 'admin';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid_admin',
        'nom',
        'prenom',
        'email',
        'telephone',
        'mot_de_passe',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'mot_de_passe',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'mot_de_passe' => 'hashed',
            'date_creation' => 'datetime',
            'date_modification' => 'datetime',
        ];
    }

    /**
     * Disable Laravel's default timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the password for the user instance.
     */
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    // Relation avec les grossistes créés
    public function grossistesCreated()
    {
        return $this->hasMany(UserDetail::class, 'cree_par_admin', 'id_admin')
                    ->where('type_user', 'GROSSISTE');
    }

    // Guard name pour Spatie Permission
    protected $guard_name = 'admin';
}
