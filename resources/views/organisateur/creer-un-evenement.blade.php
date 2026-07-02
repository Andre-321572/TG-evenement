@extends('layouts.Obase')
@section('title', '| Ajouter un événement')
@section('content')

<div class="container mx-auto px-4 py-8 max-w-6xl text-slate-800">
    
    <!-- Messages & Errors -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 mb-6 rounded-xl shadow-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span class="text-sm font-semibold">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">×</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 mb-6 rounded-xl shadow-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <span class="text-sm font-semibold">{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">×</button>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 mb-6 rounded-xl shadow-xs">
            <h6 class="font-bold text-sm mb-2">Veuillez corriger les erreurs suivantes :</h6>
            <ul class="list-disc list-inside space-y-1 text-xs font-semibold">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form start -->
    <form action="{{ route('organisateur.evenement_valider') }}" method="POST" id="event-create-form" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf
        
        <!-- Hidden input for Status (changed by header submit buttons) -->
        <input type="hidden" name="statut" id="statut-field" value="publier">

        <!-- Top Header & Submit Actions -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Outfit', sans-serif;">Créer un nouvel événement</h1>
                <p class="text-slate-500 text-sm font-medium mt-1">Remplissez les détails ci-dessous pour lancer votre prochain succès.</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="submitEventForm('en organisation')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold rounded-xl text-sm transition-all shadow-xs">
                    Sauvegarder en brouillon
                </button>
                <button type="button" onclick="submitEventForm('publier')" class="px-5 py-2.5 text-white bg-[#d9383a] hover:bg-[#c22e30] font-bold rounded-xl text-sm transition-all border-0 shadow-sm">
                    Publier l'événement
                </button>
            </div>
        </div>

        <!-- Layout grid: Left Form Column (2/3) & Right Sidebar (1/3) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Form Column -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- 1. Informations de base -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h5 class="text-base font-extrabold text-slate-900 mb-5 d-flex align-items-center gap-2" style="font-family: 'Outfit', sans-serif;">
                        <span class="text-red-500"><i class="fas fa-info-circle"></i></span> Informations de base
                    </h5>
                    
                    <div class="space-y-4">
                        <!-- Titre de l'événement -->
                        <div>
                            <label class="text-xs font-semibold text-slate-500 mb-1.5 block">Titre de l'événement *</label>
                            <input type="text" name="titre" value="{{ old('titre') }}" required placeholder="Ex: Festival de Jazz 2024"
                                   class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Catégorie -->
                            <div>
                                <label class="text-xs font-semibold text-slate-500 mb-1.5 block">Catégorie *</label>
                                <select name="categorie" required class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                                    <option disabled selected class="text-slate-400">Choisir une catégorie</option>
                                    <option value="conference et congrès" {{ old('categorie') == 'conference et congrès' ? 'selected' : '' }}>Conférence et congrès</option>
                                    <option value="vie nocturne" {{ old('categorie') == 'vie nocturne' ? 'selected' : '' }}>Vie nocturne</option>
                                    <option value="évènement sportive" {{ old('categorie') == 'évènement sportive' ? 'selected' : '' }}>Événement sportif</option>
                                    <option value="fête" {{ old('categorie') == 'fête' ? 'selected' : '' }}>Fête</option>
                                    <option value="concert et festivals de musique" {{ old('categorie') == 'concert et festivals de musique' ? 'selected' : '' }}>Concerts et festivals</option>
                                    <option value="santé" {{ old('categorie') == 'santé' ? 'selected' : '' }}>Santé</option>
                                    <option value="voyage et tourisme" {{ old('categorie') == 'voyage et tourisme' ? 'selected' : '' }}>Voyage et tourisme</option>
                                </select>
                            </div>

                            <!-- Tags (Mock/Future features) -->
                            <div>
                                <label class="text-xs font-semibold text-slate-500 mb-1.5 block">Tags</label>
                                <input type="text" name="tags" placeholder="Entrez des tags séparés par des virgules"
                                       class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                            </div>
                        </div>

                        <!-- Description détaillée with mock rich text toolbar -->
                        <div>
                            <label class="text-xs font-semibold text-slate-500 mb-1.5 block">Description détaillée</label>
                            <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50/50">
                                <!-- Mock Toolbar -->
                                <div class="bg-white border-b border-slate-100 py-2 px-3 flex gap-3 text-slate-400 text-xs font-semibold">
                                    <button type="button" class="hover:text-slate-800"><i class="fas fa-bold"></i></button>
                                    <button type="button" class="hover:text-slate-800"><i class="fas fa-italic"></i></button>
                                    <button type="button" class="hover:text-slate-800"><i class="fas fa-list-ul"></i></button>
                                    <button type="button" class="hover:text-slate-800"><i class="fas fa-link"></i></button>
                                </div>
                                <textarea name="description" rows="5" placeholder="Décrivez votre événement avec passion..."
                                          class="w-full bg-transparent border-0 text-slate-800 placeholder-slate-400 focus:ring-0 rounded-b-xl px-4 py-3 text-sm transition-all">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Date et Lieu -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h5 class="text-base font-extrabold text-slate-900 mb-5 d-flex align-items-center gap-2" style="font-family: 'Outfit', sans-serif;">
                        <span class="text-red-500"><i class="fas fa-map-marker-alt"></i></span> Date et Lieu
                    </h5>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Date de début -->
                            <div>
                                <label class="text-xs font-semibold text-slate-500 mb-1.5 block">Date de début *</label>
                                <input type="date" name="date" value="{{ old('date') }}" required
                                       class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                            </div>

                            <!-- Heure de début -->
                            <div>
                                <label class="text-xs font-semibold text-slate-500 mb-1.5 block">Heure de début *</label>
                                <input type="time" name="start_heure" value="{{ old('start_heure') }}" required
                                       class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                            </div>

                            <!-- Heure de fin -->
                            <div>
                                <label class="text-xs font-semibold text-slate-500 mb-1.5 block">Heure de fin *</label>
                                <input type="time" name="end_heure" value="{{ old('end_heure') }}" required
                                       class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                            </div>
                        </div>

                        <!-- Lieu de l'événement -->
                        <div>
                            <label class="text-xs font-semibold text-slate-500 mb-1.5 block">Lieu de l'événement *</label>
                            <div class="position-relative">
                                <i class="fas fa-search position-absolute start-0 top-50 translate-middle-y text-slate-400 ms-3"></i>
                                <input type="text" name="lieu" value="{{ old('lieu') }}" required placeholder="Adresse physique ou lien de réunion en ligne"
                                       class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl py-2.5 ps-9 pe-3 text-sm transition-all">
                            </div>
                        </div>

                        <!-- Lien Google Maps -->
                        <div>
                            <label class="text-xs font-semibold text-slate-500 mb-1.5 block">Lien Google Maps</label>
                            <input type="url" name="lien_google_map" value="{{ old('lien_google_map') }}" placeholder="https://maps.google.com/..."
                                   class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                        </div>

                        <!-- Map Placeholder -->
                        <div class="rounded-xl overflow-hidden border border-slate-100 shadow-inner position-relative bg-slate-100" style="height: 180px;">
                            <div class="position-absolute inset-0 d-flex flex-column align-items-center justify-content-center bg-slate-200/50 backdrop-blur-xs">
                                <i class="fas fa-map-marked-alt text-slate-400 fs-3 mb-2 animate-pulse"></i>
                                <span class="text-slate-500 text-xs font-semibold">Emplacement sélectionné</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Médias de l'événement -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h5 class="text-base font-extrabold text-slate-900 mb-5 d-flex align-items-center gap-2" style="font-family: 'Outfit', sans-serif;">
                        <span class="text-red-500"><i class="far fa-image"></i></span> Médias de l'événement
                    </h5>

                    <div class="space-y-4">
                        <!-- Bannière principale drag and drop UI -->
                        <div>
                            <label class="text-xs font-semibold text-slate-500 mb-2 block">Bannière principale (16:9) *</label>
                            <div class="border-2 border-dashed border-slate-200 hover:border-indigo-400 bg-slate-50/50 rounded-2xl py-6 px-4 text-center cursor-pointer transition-colors" onclick="document.getElementById('photo-input').click()">
                                <input type="file" id="photo-input" name="photo" accept="image/*" class="hidden" required onchange="updatePhotoPreview(this)">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-content-center shadow-sm mb-3">
                                        <i class="fas fa-upload text-sm"></i>
                                    </div>
                                    <span class="text-slate-700 text-sm font-semibold" id="photo-label">Glissez et déposez votre bannière ici</span>
                                    <span class="text-slate-400 text-xxs mt-1.5">Recommandé: 1920×1080px (max 10MB)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Vidéo promotionnelle -->
                        <div>
                            <label class="text-xs font-semibold text-slate-500 mb-1.5 block">Vidéo de présentation (optionnelle)</label>
                            <input type="file" name="video" accept="video/*" class="w-full bg-slate-50/50 border border-slate-200 text-slate-700 focus:outline-none rounded-xl px-4 py-2 text-xs transition-all">
                            <p class="text-slate-400 text-[10px] mt-1.5">Formats acceptés: MP4, MOV (Max: 100MB)</p>
                        </div>

                        <!-- Galerie Photos Preview Grid -->
                        <div>
                            <label class="text-xs font-semibold text-slate-500 mb-2 block">Galerie photos</label>
                            <div class="grid grid-cols-4 gap-3">
                                <div class="aspect-square border-2 border-dashed border-slate-200 hover:border-indigo-400 bg-slate-50/50 rounded-xl flex flex-col items-center justify-content-center cursor-pointer text-slate-400 transition-colors">
                                    <i class="fas fa-plus text-sm"></i>
                                </div>
                                <div class="aspect-square rounded-xl overflow-hidden">
                                    <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=150&q=80" alt="Thumb 1" class="w-100 h-100 object-cover">
                                </div>
                                <div class="aspect-square rounded-xl overflow-hidden">
                                    <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=150&q=80" alt="Thumb 2" class="w-100 h-100 object-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Informations organisateur -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h5 class="text-base font-extrabold text-slate-900 mb-5 d-flex align-items-center gap-2" style="font-family: 'Outfit', sans-serif;">
                        <span class="text-red-500"><i class="far fa-user-circle"></i></span> Informations organisateur
                    </h5>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-slate-500 mb-1.5 block">Nom complet *</label>
                            <input type="text" name="nom_proprietaire" value="{{ old('nom_proprietaire') }}" required placeholder="Ex: Jean Dupont"
                                   class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-500 mb-1.5 block">Téléphone</label>
                            <input type="tel" name="telephone" value="{{ old('telephone') }}" placeholder="Ex: +228 90 00 00 00"
                                   class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-500 mb-1.5 block">Email de contact</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Ex: contact@evenement.com"
                                   class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                        </div>
                    </div>
                </div>

                <!-- 5. Réseaux sociaux -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h5 class="text-base font-extrabold text-slate-900 mb-5 d-flex align-items-center gap-2" style="font-family: 'Outfit', sans-serif;">
                        <span class="text-red-500"><i class="fas fa-share-alt"></i></span> Réseaux sociaux
                    </h5>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-slate-500 mb-1.5 block">Lien Facebook</label>
                            <input type="url" name="facebook" value="{{ old('facebook') }}" placeholder="https://facebook.com/..."
                                   class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-500 mb-1.5 block">Lien WhatsApp</label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="Ex: +22890000000"
                                   class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-500 mb-1.5 block">Lien Twitter / X</label>
                            <input type="url" name="twitter" value="{{ old('twitter') }}" placeholder="https://twitter.com/..."
                                   class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Sidebar Column -->
            <div class="space-y-6">
                
                <!-- Billetterie placeholder preview -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h5 class="text-base font-extrabold text-slate-900 mb-4 d-flex align-items-center gap-2" style="font-family: 'Outfit', sans-serif;">
                        <span class="text-red-500"><i class="fas fa-ticket-alt"></i></span> Billetterie
                    </h5>

                    <!-- Active Ticket box -->
                    <div class="border border-slate-100 bg-slate-50/50 rounded-2xl p-3.5 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-xs font-bold text-slate-700">Billet Standard</span>
                            <button type="button" class="text-slate-400 hover:text-red-500 text-[10px] font-bold">Supprimer</button>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="text-[9px] font-bold text-slate-400 mb-1 block uppercase">Prix (FCFA)</label>
                                <input type="number" readonly value="25000" class="w-100 bg-white border border-slate-200 rounded-lg py-1 px-2.5 text-xs text-slate-700 focus:outline-none">
                            </div>
                            <div class="col-6">
                                <label class="text-[9px] font-bold text-slate-400 mb-1 block uppercase">Quantité</label>
                                <input type="number" readonly value="100" class="w-100 bg-white border border-slate-200 rounded-lg py-1 px-2.5 text-xs text-slate-700 focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Add billet button (placeholder tip) -->
                    <div class="border-2 border-dashed border-slate-200 bg-slate-50/10 hover:bg-slate-50 rounded-2xl p-3.5 text-center cursor-pointer transition-colors mb-4">
                        <div class="flex items-center justify-content-center gap-2 text-xs font-bold text-indigo-600">
                            <i class="fas fa-plus-circle text-sm"></i>
                            <span>Ajouter un type de billet</span>
                        </div>
                    </div>

                    <!-- Info Alert -->
                    <div class="bg-indigo-50/50 border border-indigo-100 rounded-xl p-3 text-indigo-800 text-xxs font-medium leading-relaxed">
                        💡 Une fois votre événement enregistré, vous pourrez configurer en détail toutes vos catégories de billets dans la gestion des billets.
                    </div>
                </div>

                <!-- Configuration Progress -->
                <div class="rounded-2xl p-5 text-white" style="background: linear-gradient(135deg, #131a38 0%, #1e295d 100%);">
                    <div class="flex justify-between items-center text-xs font-bold mb-3">
                        <span>Configuration</span>
                        <span>80%</span>
                    </div>
                    <div class="w-full bg-slate-700 rounded-full h-2">
                        <div class="bg-[#d9383a] h-2 rounded-full" style="width: 80%;"></div>
                    </div>
                </div>

                <!-- Parameters Switch Card -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h5 class="text-base font-extrabold text-slate-900 mb-4 d-flex align-items-center gap-2" style="font-family: 'Outfit', sans-serif;">
                        <span class="text-red-500"><i class="fas fa-cog"></i></span> Paramètres
                    </h5>

                    <div class="space-y-4">
                        <!-- Switch Visibilité -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-xs font-bold text-slate-700 d-block">Visibilité</span>
                                <small class="text-slate-400 text-[10px]">Public - visible par tous</small>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:height-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>

                        <!-- Switch Approbation -->
                        <div class="d-flex justify-content-between align-items-center border-t border-slate-100 pt-3">
                            <div>
                                <span class="text-xs font-bold text-slate-700 d-block">Approbation manuelle</span>
                                <small class="text-slate-400 text-[10px]">Validez chaque participant</small>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:height-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>

                        <!-- Personalisation avancée link -->
                        <div class="d-flex justify-content-between align-items-center border-t border-slate-100 pt-3 text-xs font-bold text-slate-600 hover:text-indigo-600 cursor-pointer">
                            <span>Personnalisation avancée</span>
                            <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>

<script>
function submitEventForm(statusValue) {
    document.getElementById('statut-field').value = statusValue;
    document.getElementById('event-create-form').submit();
}

function updatePhotoPreview(input) {
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        document.getElementById('photo-label').textContent = "Sélectionné : " + fileName;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Set tomorrow date validation
    const dateInput = document.querySelector('[name="date"]');
    if (dateInput) {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        dateInput.min = tomorrow.toISOString().split('T')[0];
    }
});
</script>

@endsection
