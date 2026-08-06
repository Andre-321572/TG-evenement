@extends('layouts.Obase')
@section('title', '| Ajouter un événement')
@section('content')

<div class="container mx-auto px-4 py-8 max-w-4xl text-slate-800">
    
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

    <!-- Wizard Stepper -->
    @include('organisateur.include.wizard-stepper', ['step' => 1])

    <!-- Form start -->
    <form action="{{ route('organisateur.evenement_valider') }}" method="POST" id="event-create-form" enctype="multipart/form-data" class="needs-validation space-y-8" novalidate>
        @csrf
        
        <!-- Hidden input for Status (changed by submit buttons) -->
        <input type="hidden" name="statut" id="statut-field" value="publier">

        <!-- Top Header & Submit Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-100">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Outfit', sans-serif;">Étape 1 : Créer l'événement</h1>
                <p class="text-slate-500 text-sm font-medium mt-1">Renseignez les détails ci-dessous. Vous ajouterez ensuite les billets et les sponsors.</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="submitEventForm('en organisation')" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold rounded-xl text-sm transition-all shadow-xs">
                    Enregistrer en brouillon
                </button>
                <button type="button" onclick="submitEventForm('publier')" class="px-5 py-2.5 text-white bg-indigo-600 hover:bg-indigo-700 font-bold rounded-xl text-sm transition-all border-0 shadow-sm flex items-center gap-2">
                    <span>Suivant : Créer les billets</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </button>
            </div>
        </div>

        <!-- 1. Informations de base -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 space-y-6">
            <h5 class="text-base font-extrabold text-slate-900 d-flex align-items-center gap-2 pb-3 border-b border-slate-50" style="font-family: 'Outfit', sans-serif;">
                <span class="text-red-500"><i class="fas fa-info-circle"></i></span> 1. Informations de base
            </h5>
            
            <div class="space-y-4">
                <!-- Titre de l'événement -->
                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1.5 block">Titre de l'événement <span class="text-red-500">*</span></label>
                    <input type="text" name="titre" value="{{ old('titre') }}" required placeholder="Ex: Festival International de Musique de Lomé"
                           class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Catégorie -->
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1.5 block">Catégorie <span class="text-red-500">*</span></label>
                        <select name="categorie" required class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
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

                    <!-- Tags -->
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1.5 block">Tags (optionnels)</label>
                        <input type="text" name="tags" placeholder="Ex: musique, live, festival"
                               class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                    </div>
                </div>

                <!-- Description détaillée -->
                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1.5 block">Description de l'événement</label>
                    <div class="border border-slate-200 rounded-2xl overflow-hidden bg-slate-50/50">
                        <!-- Toolbar styling -->
                        <div class="bg-white border-b border-slate-200/60 py-2 px-3.5 flex gap-4 text-slate-400 text-xs font-semibold">
                            <button type="button" class="hover:text-slate-700 transition-colors"><i class="fas fa-bold"></i></button>
                            <button type="button" class="hover:text-slate-700 transition-colors"><i class="fas fa-italic"></i></button>
                            <button type="button" class="hover:text-slate-700 transition-colors"><i class="fas fa-list-ul"></i></button>
                            <button type="button" class="hover:text-slate-700 transition-colors"><i class="fas fa-link"></i></button>
                        </div>
                        <textarea name="description" rows="6" placeholder="Décrivez le programme, les artistes invités, et ce qui rend cet événement exceptionnel..."
                                  class="w-full bg-transparent border-0 text-slate-800 placeholder-slate-400 focus:ring-0 rounded-b-xl px-4 py-3 text-sm transition-all">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Planification & Lieu -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 space-y-6">
            <h5 class="text-base font-extrabold text-slate-900 d-flex align-items-center gap-2 pb-3 border-b border-slate-50" style="font-family: 'Outfit', sans-serif;">
                <span class="text-red-500"><i class="fas fa-map-marker-alt"></i></span> 2. Planification et Lieu
            </h5>

            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Date de début -->
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1.5 block">Date de début <span class="text-red-500">*</span></label>
                        <input type="date" name="date" value="{{ old('date') }}" required
                               class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                    </div>

                    <!-- Heure de début -->
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1.5 block">Heure de début <span class="text-red-500">*</span></label>
                        <input type="time" name="start_heure" value="{{ old('start_heure') }}" required
                               class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                    </div>

                    <!-- Heure de fin -->
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1.5 block">Heure de fin <span class="text-red-500">*</span></label>
                        <input type="time" name="end_heure" value="{{ old('end_heure') }}" required
                               class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                    </div>
                </div>

                <!-- Lieu de l'événement -->
                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1.5 block">Adresse ou Lieu <span class="text-red-500">*</span></label>
                    <div class="position-relative">
                        <i class="fas fa-map-pin position-absolute start-0 top-50 translate-middle-y text-slate-400 ms-3"></i>
                        <input type="text" name="lieu" value="{{ old('lieu') }}" required placeholder="Ex: Palais des Congrès, Lomé"
                               class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl py-2.5 ps-9 pe-3 text-sm transition-all">
                    </div>
                </div>

                <!-- Lien Google Maps -->
                <div>
                    <label class="text-xs font-bold text-slate-500 mb-1.5 block">Lien de localisation Google Maps</label>
                    <input type="url" name="lien_google_map" value="{{ old('lien_google_map') }}" placeholder="https://maps.google.com/..."
                           class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                </div>
            </div>
        </div>

        <!-- 3. Médias de l'événement -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 space-y-6">
            <h5 class="text-base font-extrabold text-slate-900 d-flex align-items-center gap-2 pb-3 border-b border-slate-50" style="font-family: 'Outfit', sans-serif;">
                <span class="text-red-500"><i class="far fa-image"></i></span> 3. Médias et Visuels
            </h5>

            <div class="space-y-5">
                <!-- Bannière principale drag and drop UI with Preview -->
                <div>
                    <label class="text-xs font-bold text-slate-500 mb-2.5 block">Bannière principale (Format recommandé 16:9) <span class="text-red-500">*</span></label>
                    
                    <div class="border-2 border-dashed border-slate-200 hover:border-indigo-400 bg-slate-50/50 rounded-2xl p-6 text-center cursor-pointer transition-colors relative flex flex-col items-center justify-center min-h-[180px]" onclick="document.getElementById('photo-input').click()">
                        <input type="file" id="photo-input" name="photo" accept="image/*" class="hidden" required onchange="updatePhotoPreview(this)">
                        
                        <!-- Upload state icon -->
                        <div id="upload-icon-wrapper" class="flex flex-col items-center">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm mb-3">
                                <i class="fas fa-image text-lg"></i>
                            </div>
                            <span class="text-slate-700 text-sm font-bold" id="photo-label">Sélectionner une photo de couverture</span>
                            <span class="text-slate-400 text-xxs mt-1.5">JPEG, PNG (max 10MB)</span>
                        </div>

                        <!-- Preview container -->
                        <div id="photo-preview-container" class="hidden absolute inset-0 w-full h-full rounded-2xl overflow-hidden bg-slate-950">
                            <img id="photo-preview" src="" class="w-full h-full object-cover opacity-80" alt="Bannière choisie">
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center text-xs font-bold text-white shadow-inner">
                                <i class="fas fa-sync-alt mr-2"></i> Changer de bannière
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Vidéo promotionnelle -->
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1.5 block">Vidéo promotionnelle (optionnelle)</label>
                        <input type="file" name="video" accept="video/*" class="w-full bg-slate-50/50 border border-slate-200 text-slate-700 focus:outline-none rounded-xl px-4 py-2 text-xs transition-all">
                        <p class="text-slate-400 text-[10px] mt-1.5">Formats acceptés: MP4 (Max: 100MB)</p>
                    </div>

                    <!-- Galerie Photos Preview Grid -->
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-2 block">Galerie photo de l'événement</label>
                        <div class="flex items-center gap-3">
                            <div class="w-14 h-14 border border-dashed border-slate-200 hover:border-indigo-400 bg-slate-50/50 rounded-xl flex flex-col items-center justify-content-center cursor-pointer text-slate-400 transition-colors">
                                <i class="fas fa-plus text-xs"></i>
                            </div>
                            <div class="w-14 h-14 rounded-xl overflow-hidden shadow-xs">
                                <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=100&q=80" alt="Thumb 1" class="w-100 h-100 object-cover">
                            </div>
                            <div class="w-14 h-14 rounded-xl overflow-hidden shadow-xs">
                                <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=100&q=80" alt="Thumb 2" class="w-100 h-100 object-cover">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Informations Contact & Réseaux Sociaux -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 space-y-6">
            <h5 class="text-base font-extrabold text-slate-900 d-flex align-items-center gap-2 pb-3 border-b border-slate-50" style="font-family: 'Outfit', sans-serif;">
                <span class="text-red-500"><i class="far fa-user-circle"></i></span> 4. Organisateur et Réseaux
            </h5>

            <div class="space-y-6">
                <!-- Coordonnées -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1.5 block">Nom de l'organisateur *</label>
                        <input type="text" name="nom_proprietaire" value="{{ old('nom_proprietaire') }}" required placeholder="Ex: Comité d'organisation"
                               class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1.5 block">Téléphone de contact</label>
                        <input type="tel" name="telephone" value="{{ old('telephone') }}" placeholder="Ex: +228 90 00 00 00"
                               class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1.5 block">Email de contact</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Ex: contact@evenement.com"
                               class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                    </div>
                </div>

                <!-- Réseaux sociaux -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-3 border-t border-slate-100/60">
                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1.5 block">Page Facebook (URL)</label>
                        <input type="url" name="facebook" value="{{ old('facebook') }}" placeholder="https://facebook.com/..."
                               class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1.5 block">Numéro WhatsApp</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="Ex: +22890000000"
                               class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-500 mb-1.5 block">Compte Twitter / X (URL)</label>
                        <input type="url" name="twitter" value="{{ old('twitter') }}" placeholder="https://twitter.com/..."
                               class="w-full bg-slate-50/50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 text-sm transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Actions -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
            <button type="button" onclick="submitEventForm('en organisation')" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold rounded-xl text-sm transition-all shadow-xs">
                Enregistrer en brouillon
            </button>
            <button type="button" onclick="submitEventForm('publier')" class="px-6 py-3 text-white bg-indigo-600 hover:bg-indigo-700 font-bold rounded-xl text-sm transition-all border-0 shadow-sm flex items-center gap-2">
                <span>Suivant : Créer les billets</span>
                <i class="fas fa-arrow-right text-xs"></i>
            </button>
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
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewContainer = document.getElementById('photo-preview-container');
            const previewImg = document.getElementById('photo-preview');
            const uploadIcon = document.getElementById('upload-icon-wrapper');
            
            previewImg.src = e.target.result;
            previewContainer.classList.remove('hidden');
            uploadIcon.classList.add('hidden');
            document.getElementById('photo-label').textContent = "Modifier la bannière";
        }
        reader.readAsDataURL(input.files[0]);
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
