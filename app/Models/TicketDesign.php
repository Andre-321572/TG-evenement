<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketDesign extends Model
{
    use HasFactory;

    protected $table = 'ticket_designs';

    protected $fillable = [
        'evenement_id',
        // Logo
        'logo',
        // Fond
        'fond_type',          // 'couleur' | 'degrade' | 'image'
        'fond_couleur1',      // couleur principale du fond (hex)
        'fond_couleur2',      // couleur secondaire (dégradé)
        'fond_image',         // nom du fichier image de fond
        'fond_opacite',       // opacité du fond (0-100)
        // Typographie
        'police',             // ex: 'Outfit', 'Montserrat', 'Playfair Display'
        'couleur_titre',      // couleur du titre de l'événement
        'couleur_texte',      // couleur du texte secondaire
        // Template de base
        'template',           // 'classique' | 'moderne' | 'festif'
    ];

    protected $casts = [
        'fond_opacite' => 'integer',
    ];

    /**
     * Relation avec l'événement
     */
    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }

    /**
     * URL du logo
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo
            ? asset('storage/ticket_designs/logos/' . $this->logo)
            : null;
    }

    /**
     * URL de l'image de fond
     */
    public function getFondImageUrlAttribute()
    {
        return $this->fond_image
            ? asset('storage/ticket_designs/fonds/' . $this->fond_image)
            : null;
    }

    /**
     * CSS généré pour le fond du ticket
     */
    public function getFondCssAttribute()
    {
        switch ($this->fond_type) {
            case 'degrade':
                return "background: linear-gradient(135deg, {$this->fond_couleur1}, {$this->fond_couleur2});";
            case 'image':
                return $this->fond_image
                    ? "background-image: url('{$this->fond_image_url}'); background-size: cover; background-position: center;"
                    : "background: {$this->fond_couleur1};";
            default: // couleur
                return "background: {$this->fond_couleur1};";
        }
    }
}
