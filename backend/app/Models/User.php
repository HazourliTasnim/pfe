<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [ 'email',
    'password',
    'role',
    'status',
    'civilite',
    'nom',
    'prenom',
    'telephone', 
    'fonction',
    'entreprise_id',];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getStatusLabelAttribute()
{
    return match ($this->status) {
        'pending' => 'En attente',
        'active' => 'Actif',
        'suspended' => 'Suspendu',
        default => 'Inconnu',
    };
}

    public function changerStatut($statut)
    {
        $statutsValides = ['en attente', 'actif', 'suspendu'];
    
        if (!in_array($statut, $statutsValides)) {
            return response()->json(['error' => 'Statut invalide.'], 400);
        }
    
        if ($this->status !== $statut) {
            $this->status = $statut;
            $this->save();
    
            return response()->json([
                'success' => true,
                'message' => "Le statut a été mis à jour en '$statut'."
            ], 200);
        }
    
        return response()->json([
            'success' => false,
            'message' => "Aucune modification : l'utilisateur est déjà '$statut'."
        ], 200);
    }
    

}