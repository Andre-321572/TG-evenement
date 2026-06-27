<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'categorie', 'categorie_id', 'titre', 'date', 'start_heure', 'end_heure', 'lieu',
        'lien_google_map', 'description', 'photo', 'video', 'statut',
        'nom_proprietaire', 'telephone', 'email', 'facebook', 'whatsapp',
        'twiter', 'user_id'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }
    
    public function billets()
    {
        return $this->hasMany(Billet::class);
    }
    
    public function users_()
    {
        return $this->belongsToMany(Participant::class, 'reservations')
                    ->withPivot('place_reserver', 'status')
                    ->withTimestamps();
    }
    
    public function participants()
    {
        return $this->belongsToMany(Participant::class, 'participer')
                    ->withPivot()
                    ->withTimestamps();
    }
    
    public function reservations()
    {
        return $this->belongsTo(Reservation::class);
    }
    
    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }
    
    public function sponsors()
    {
        return $this->hasMany(Sponsor::class);
    }

    // Scopes pour les filtres
    public function scopeSearch($query, $search)
    {
        return $query->where('titre', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('lieu', 'like', '%' . $search . '%');
    }
    
    public function scopeByLieu($query, $lieu)
    {
        return $query->where('lieu', 'like', '%' . $lieu . '%');
    }
    
    public function scopeByCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }
    
    public function scopeByDateRange($query, $dateStart = null, $dateEnd = null)
    {
        if ($dateStart) {
            $query->where('date', '>=', $dateStart);
        }
        if ($dateEnd) {
            $query->where('date', '<=', $dateEnd);
        }
        return $query;
    }
    
    public function scopePublie($query)
    {
        return $query->where('statut', 'publier');
    }

    // Méthodes statiques manquantes
    public static function getCategories()
    {
        // Récupérer toutes les catégories distinctes depuis la base de données
        return self::select('categorie')
                   ->distinct()
                   ->whereNotNull('categorie')
                   ->orderBy('categorie')
                   ->pluck('categorie')
                   ->toArray();
    }
    
    public static function getLieux()
    {
        // Récupérer tous les lieux distincts depuis la base de données
        return self::select('lieu')
                   ->distinct()
                   ->whereNotNull('lieu')
                   ->orderBy('lieu')
                   ->pluck('lieu')
                   ->toArray();
    }
    
    // Alternative avec des valeurs prédéfinies si vous préférez
    public static function getCategoriesPredefinies()
    {
        return [
            'Concert',
            'Conférence', 
            'Festival',
            'Sport',
            'Théâtre',
            'Exposition',
            'Formation',
            'Networking',
            'Autre'
        ];
    }
    
    public static function getLieuxPredefinis()
    {
        return [
            'Lomé',
            'Kara',
            'Sokodé',
            'Kpalimé',
            'Atakpamé',
            'Dapaong',
            'Tsévié',
            'Aného',
            'Autre'
        ];
    }

    public static function statut()
    {
        return [
            'statut' => 'required|in:en organisation,publier,passé'
        ];
    }
    
    // Accesseurs pour les URLs des médias
    public function getPhotoUrlAttribute()
    {
        if ($this->photo && $this->photo !== 'null') {
            return asset('storage/evenement/photo/' . $this->photo);
        }
        return asset('images/default-event.jpg'); // Image par défaut
    }
    
    public function getVideoUrlAttribute()
    {
        if ($this->video && $this->video !== 'null') {
            return asset('storage/evenement/videos/' . $this->video);
        }
        return null;
    }
}