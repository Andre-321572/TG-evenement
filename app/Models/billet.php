<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Billet extends Model
{
    use HasFactory;

    protected $table = 'billets'; // Nom de la table explicite

    protected $fillable = [
        'type',
        'prix',
        'quantite',
        'quantite_totale',
        'quantite_disponible',
        'quantite_vendue',
        'evenement_id',
        'description',
        'statut'
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'quantite' => 'integer',
        'quantite_totale' => 'integer',
        'quantite_disponible' => 'integer',
        'quantite_vendue' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec le modèle Evenement
     */
    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }

    /**
     * Relation avec les réservations/ventes
     */
    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }

    /**
     * Accesseur pour vérifier si le billet est disponible
     */
    public function getEstDisponibleAttribute()
    {
        return $this->quantite_disponible > 0;
    }

    /**
     * Accesseur pour le pourcentage de vente
     */
    public function getPourcentageVenteAttribute()
    {
        if ($this->quantite_totale == 0) {
            return 0;
        }
        return round(($this->quantite_vendue / $this->quantite_totale) * 100, 2);
    }

    /**
     * Accesseur pour le chiffre d'affaires du billet
     */
    public function getChiffreAffairesAttribute()
    {
        return $this->quantite_vendue * $this->prix;
    }

    /**
     * Accesseur pour le statut formaté
     */
    public function getStatutFormateAttribute()
    {
        if ($this->quantite_disponible == 0) {
            return 'Épuisé';
        } elseif ($this->quantite_disponible <= ($this->quantite_totale * 0.1)) {
            return 'Stock faible';
        } else {
            return 'Disponible';
        }
    }

    /**
     * Accesseur pour la classe CSS du statut
     */
    public function getStatutClassAttribute()
    {
        if ($this->quantite_disponible == 0) {
            return 'bg-danger';
        } elseif ($this->quantite_disponible <= ($this->quantite_totale * 0.1)) {
            return 'bg-warning';
        } else {
            return 'bg-success';
        }
    }

    /**
     * Scope pour les billets disponibles
     */
    public function scopeDisponibles($query)
    {
        return $query->where('quantite_disponible', '>', 0);
    }

    /**
     * Scope pour les billets épuisés
     */
    public function scopeEpuises($query)
    {
        return $query->where('quantite_disponible', '<=', 0);
    }

    /**
     * Scope pour filtrer par type de billet
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope pour filtrer par prix
     */
    public function scopeParPrix($query, $prixMin = null, $prixMax = null)
    {
        if ($prixMin !== null) {
            $query->where('prix', '>=', $prixMin);
        }
        if ($prixMax !== null) {
            $query->where('prix', '<=', $prixMax);
        }
        return $query;
    }

    /**
     * Scope pour filtrer par période d'événement
     */
    public function scopeParPeriodeEvenement($query, $dateDebut = null, $dateFin = null)
    {
        return $query->whereHas('evenement', function($q) use ($dateDebut, $dateFin) {
            if ($dateDebut) {
                $q->where('date_debut', '>=', $dateDebut);
            }
            if ($dateFin) {
                $q->where('date_fin', '<=', $dateFin);
            }
        });
    }

    /**
     * Scope pour les billets d'événements à venir
     */
    public function scopeEvenementsAVenir($query)
    {
        return $query->whereHas('evenement', function($q) {
            $q->where('date_debut', '>', Carbon::now());
        });
    }

    /**
     * Scope pour les billets d'événements passés
     */
    public function scopeEvenementsPasses($query)
    {
        return $query->whereHas('evenement', function($q) {
            $q->where('date_fin', '<', Carbon::now());
        });
    }

    /**
     * Scope pour les billets d'événements en cours
     */
    public function scopeEvenementsEnCours($query)
    {
        return $query->whereHas('evenement', function($q) {
            $now = Carbon::now();
            $q->where('date_debut', '<=', $now)
              ->where('date_fin', '>=', $now);
        });
    }

    /**
     * Mutateur pour synchroniser les quantités lors de la création
     */
    public function setQuantiteAttribute($value)
    {
        $this->attributes['quantite'] = $value;
        
        // Si c'est une nouvelle création, synchroniser les quantités
        if (!$this->exists) {
            $this->attributes['quantite_totale'] = $value;
            $this->attributes['quantite_disponible'] = $value;
            $this->attributes['quantite_vendue'] = 0;
        }
    }

    /**
     * Méthode pour vendre des billets
     */
    public function vendre($quantite)
    {
        if ($this->quantite_disponible >= $quantite) {
            $this->quantite_disponible -= $quantite;
            $this->quantite_vendue += $quantite;
            $this->save();
            
            return true;
        }
        
        return false;
    }

    /**
     * Méthode pour annuler une vente
     */
    public function annulerVente($quantite)
    {
        if ($this->quantite_vendue >= $quantite) {
            $this->quantite_disponible += $quantite;
            $this->quantite_vendue -= $quantite;
            $this->save();
            
            return true;
        }
        
        return false;
    }

    /**
     * Méthode statique pour obtenir les types de billets disponibles
     */
    public static function getTypesDisponibles()
    {
        return [
            'STANDARD' => 'Standard',
            'VIP' => 'VIP',
            'VVIP' => 'VVIP',
            'GRATUIT' => 'Gratuit',
            'ETUDIANT' => 'Étudiant'
        ];
    }

    /**
     * Boot method pour les événements du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Événement avant sauvegarde pour valider les quantités
        static::saving(function ($billet) {
            // S'assurer que les quantités sont cohérentes
            if ($billet->quantite_vendue + $billet->quantite_disponible > $billet->quantite_totale) {
                throw new \Exception('Les quantités vendues et disponibles ne peuvent pas dépasser la quantité totale');
            }
        });
    }
}