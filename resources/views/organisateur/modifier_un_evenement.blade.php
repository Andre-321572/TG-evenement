@extends('layouts.Obase')
@section('title', '| Modifier l\'événement')
@section('content')

<div class="container mx-auto px-6 py-8 max-w-4xl">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">Modifier l'Événement</h1>
        <p class="text-gray-400 text-sm">Modifiez les informations de votre événement ci-dessous</p>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 mb-6 rounded-xl shadow-lg flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white transition-colors">×</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 mb-6 rounded-xl shadow-lg flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-white transition-colors">×</button>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 mb-6 rounded-xl shadow-lg">
            <h6 class="font-bold mb-2">Veuillez corriger les erreurs suivantes :</h6>
            <ul class="list-disc list-inside space-y-1 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Form -->
    <form action="{{ route('organisateur.ev-update', $evenement->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation space-y-6" novalidate>
        @csrf
        @method('PUT')

        <div class="glass-card rounded-2xl border border-white/5 p-6 md:p-8 space-y-8">
            <!-- Section 1: Basic Info -->
            <div>
                <h4 class="text-lg font-bold text-white mb-4 border-b border-white/5 pb-2">Informations de base</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="text-sm font-semibold text-gray-300 mb-1.5 block">
                            Catégorie <span class="text-red-400">*</span>
                        </label>
                        <select name="categorie" class="w-full bg-white/5 border border-white/10 text-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-all @error('categorie') border-red-500 @enderror" required>
                            <option disabled class="bg-[#0f111a] text-gray-400">Choisir une catégorie</option>
                            <option class="bg-[#0f111a]" value="conference et congrès" {{ old('categorie', $evenement->categorie) == 'conference et congrès' ? 'selected' : '' }}>Conférence et congrès</option>
                            <option class="bg-[#0f111a]" value="vie nocturne" {{ old('categorie', $evenement->categorie) == 'vie nocturne' ? 'selected' : '' }}>Vie nocturne</option>
                            <option class="bg-[#0f111a]" value="évènement sportive" {{ old('categorie', $evenement->categorie) == 'évènement sportive' ? 'selected' : '' }}>Événement sportif</option>
                            <option class="bg-[#0f111a]" value="fête" {{ old('categorie', $evenement->categorie) == 'fête' ? 'selected' : '' }}>Fête</option>
                            <option class="bg-[#0f111a]" value="concert et festivals de musique" {{ old('categorie', $evenement->categorie) == 'concert et festivals de musique' ? 'selected' : '' }}>Concerts et festivals</option>
                            <option class="bg-[#0f111a]" value="santé" {{ old('categorie', $evenement->categorie) == 'santé' ? 'selected' : '' }}>Santé</option>
                            <option class="bg-[#0f111a]" value="voyage et tourisme" {{ old('categorie', $evenement->categorie) == 'voyage et tourisme' ? 'selected' : '' }}>Voyage et tourisme</option>
                        </select>
                        @error('categorie')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-300 mb-1.5 block">
                            Titre de l'événement <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="titre" value="{{ old('titre', $evenement->titre) }}" placeholder="Nom de l'événement" class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-all @error('titre') border-red-500 @enderror" required>
                        @error('titre')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-300 mb-1.5 block">
                            Date <span class="text-red-400">*</span>
                        </label>
                        <input type="date" name="date" value="{{ old('date', $evenement->date) }}" class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-all @error('date') border-red-500 @enderror" required>
                        @error('date')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-300 mb-1.5 block">
                                Début <span class="text-red-400">*</span>
                            </label>
                            <input type="time" name="start_heure" value="{{ old('start_heure', $evenement->start_heure) }}" class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-all @error('start_heure') border-red-500 @enderror" required>
                            @error('start_heure')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-300 mb-1.5 block">
                                Fin <span class="text-red-400">*</span>
                            </label>
                            <input type="time" name="end_heure" value="{{ old('end_heure', $evenement->end_heure) }}" class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-all @error('end_heure') border-red-500 @enderror" required>
                            @error('end_heure')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Location & Media -->
            <div>
                <h4 class="text-lg font-bold text-white mb-4 border-b border-white/5 pb-2">Lieu et médias</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="text-sm font-semibold text-gray-300 mb-1.5 block">
                            Lieu <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="lieu" value="{{ old('lieu', $evenement->lieu) }}" placeholder="Adresse physique" class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-all @error('lieu') border-red-500 @enderror" required>
                        @error('lieu')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-300 mb-1.5 block">
                            Lien Google Maps
                        </label>
                        <input type="url" name="lien_google_map" value="{{ old('lien_google_map', $evenement->lien_google_map) }}" placeholder="https://maps.google.com/..." class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-all @error('lien_google_map') border-red-500 @enderror">
                        @error('lien_google_map')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-300 mb-1.5 block">
                            Photo de l'événement
                        </label>
                        <input type="file" name="photo" accept="image/*" class="w-full bg-white/5 border border-white/10 text-gray-300 focus:outline-none rounded-xl px-4 py-2 transition-all">
                        <p class="text-xxs text-gray-500 mt-1.5">Formats acceptés: JPEG, PNG, JPG, GIF, SVG (Max: 10MB)</p>
                        @if($evenement->photo)
                            <div class="mt-3 relative w-32 rounded-xl overflow-hidden border border-white/10">
                                <img src="{{ asset('storage/evenement/photo/' . $evenement->photo) }}" class="w-full h-24 object-cover" alt="Preview">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center text-[10px] text-white">Actuelle</div>
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-300 mb-1.5 block">
                            Vidéo promotionnelle
                        </label>
                        <input type="file" name="video" accept="video/*" class="w-full bg-white/5 border border-white/10 text-gray-300 focus:outline-none rounded-xl px-4 py-2 transition-all">
                        <p class="text-xxs text-gray-500 mt-1.5">Formats acceptés: MP4, MOV, OGG, QT (Max: 100MB)</p>
                        @if($evenement->video)
                            <div class="mt-3 w-48 rounded-xl overflow-hidden border border-white/10 p-2 bg-white/5">
                                <video controls class="w-full h-24 object-cover">
                                    <source src="{{ asset('storage/evenement/videos/' . $evenement->video) }}" type="video/mp4">
                                </video>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Section 3: Organizer Info -->
            <div>
                <h4 class="text-lg font-bold text-white mb-4 border-b border-white/5 pb-2">Informations organisateur</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="text-sm font-semibold text-gray-300 mb-1.5 block">
                            Nom et Prénom <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="nom_proprietaire" value="{{ old('nom_proprietaire', $evenement->nom_proprietaire) }}" placeholder="Responsable principal" class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-all @error('nom_proprietaire') border-red-500 @enderror" required>
                        @error('nom_proprietaire')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-300 mb-1.5 block">
                            Téléphone
                        </label>
                        <input type="tel" name="telephone" value="{{ old('telephone', $evenement->telephone) }}" placeholder="Contact mobile" class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-all @error('telephone') border-red-500 @enderror">
                        @error('telephone')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-300 mb-1.5 block">
                            Email de contact
                        </label>
                        <input type="email" name="email" value="{{ old('email', $evenement->email) }}" placeholder="adresse@domaine.com" class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-all @error('email') border-red-500 @enderror">
                        @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Section 4: Social links -->
            <div>
                <h4 class="text-lg font-bold text-white mb-4 border-b border-white/5 pb-2">Réseaux sociaux</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="text-sm font-semibold text-gray-300 mb-1.5 block">Lien Facebook</label>
                        <input type="url" name="facebook" value="{{ old('facebook', $evenement->facebook) }}" placeholder="https://facebook.com/..." class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-all">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-300 mb-1.5 block">Lien WhatsApp</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $evenement->whatsapp) }}" placeholder="Numéro WhatsApp" class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-all">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-300 mb-1.5 block">Lien Twitter</label>
                        <input type="url" name="twitter" value="{{ old('twitter', $evenement->twiter) }}" placeholder="https://twitter.com/..." class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-all">
                    </div>
                </div>
            </div>

            <!-- Section 5: Description & Status -->
            <div>
                <h4 class="text-lg font-bold text-white mb-4 border-b border-white/5 pb-2">Description & Statut</h4>
                <div class="space-y-5">
                    <div>
                        <label class="text-sm font-semibold text-gray-300 mb-1.5 block">Description détaillée</label>
                        <textarea name="description" rows="5" placeholder="Saisissez une description attractive pour votre événement..." class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-all">{{ old('description', $evenement->description) }}</textarea>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-300 mb-3 block">Option de publication <span class="text-red-400">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="border border-white/10 hover:border-white/20 bg-white/5 hover:bg-white/[0.08] rounded-xl p-4 flex items-start space-x-3 cursor-pointer transition-all">
                                <input type="radio" name="statut" value="en organisation" class="mt-1" {{ old('statut', $evenement->statut) == 'en organisation' ? 'checked' : '' }} required>
                                <div>
                                    <strong class="block text-sm text-white">Brouillon</strong>
                                    <span class="text-xxs text-gray-400">Enregistrer pour compléter plus tard</span>
                                </div>
                            </label>
                            <label class="border border-white/10 hover:border-white/20 bg-white/5 hover:bg-white/[0.08] rounded-xl p-4 flex items-start space-x-3 cursor-pointer transition-all">
                                <input type="radio" name="statut" value="publier" class="mt-1" {{ old('statut', $evenement->statut) == 'publier' ? 'checked' : '' }} required>
                                <div>
                                    <strong class="block text-sm text-white">Publier immédiatement</strong>
                                    <span class="text-xxs text-gray-400">Visible directement sur le site public</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <input type="hidden" name="user_id" value="{{ $evenement->user_id }}">
            <div class="pt-6 border-t border-white/5 flex justify-end">
                <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-colors duration-200">
                    Sauvegarder les modifications
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
