<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppelOffre extends Model
{
    protected $table = 'appels_d_offres'; 

    protected $fillable = [
        'entreprise_id',
        'langue',
        'etat_offre',
        'wilaya',
        'numero_offre',
        'titre_offre',
        'type_offre',
        'niveau',
        'date_publication',
        'date_limite',
        'date_prorogation',
        'description',
        'document_pdf',
        'etat',
    ];
    public function entreprise()
{
    return $this->belongsTo(Entreprise::class, 'entreprise_id'); // Assure-toi que la clé étrangère est correcte
}

}

