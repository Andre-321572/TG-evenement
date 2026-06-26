@extends('layouts.Obase')
@section('title', '| Détail de l\'événement - ' . $evenement->titre)
@section('content')

<div class="container mx-auto px-6 py-8 max-w-5xl space-y-6">
    <!-- Back Header -->
    <div class="flex items-center justify-between pb-4 border-b border-white/5">
        <a href="{{ route('organisateur.evenement-en-cours') }}" class="inline-flex items-center space-x-2 text-sm text-gray-400 hover:text-white transition-colors">
            <i data-feather="arrow-left" class="w-4 h-4"></i>
            <span>Retour aux événements</span>
        </a>
        <span class="text-xs text-gray-500">ID Événement: #{{ $evenement->id }}</span>
    </div>

    <!-- Title and Status -->
    <div class="glass-card rounded-2xl p-6 md:p-8 relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-32 h-32 rounded-full bg-accentIndigo/10 blur-2xl"></div>
        
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6 relative z-10">
            <div class="space-y-4">
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-400 bg-indigo-500/5 px-2.5 py-1 rounded-lg border border-indigo-500/10">
                        {{ $evenement->categorie }}
                    </span>
                    @if($evenement->statut === 'publier')
                        <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-3 py-1 rounded-full text-xs font-bold flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                            En ligne
                        </span>
                    @elseif($evenement->statut === 'en organisation')
                        <span class="bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 px-3 py-1 rounded-full text-xs font-bold flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 mr-1.5"></span>
                            Brouillon
                        </span>
                    @else
                        <span class="bg-gray-500/10 text-gray-400 border border-gray-500/20 px-3 py-1 rounded-full text-xs font-bold flex items-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1.5"></span>
                            Archivé
                        </span>
                    @endif
                </div>
                <h1 class="text-3xl font-extrabold text-white leading-tight">
                    {{ $evenement->titre }}
                </h1>
                
                <div class="flex flex-wrap gap-4 text-sm text-gray-400">
                    <div class="flex items-center bg-white/5 px-3.5 py-1.5 rounded-xl border border-white/5">
                        <i data-feather="calendar" class="w-4 h-4 mr-2 text-indigo-400"></i>
                        <span>{{ \Carbon\Carbon::parse($evenement->date)->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center bg-white/5 px-3.5 py-1.5 rounded-xl border border-white/5">
                        <i data-feather="clock" class="w-4 h-4 mr-2 text-indigo-400"></i>
                        <span>{{ \Carbon\Carbon::parse($evenement->start_heure)->format('H:i') }} - {{ \Carbon\Carbon::parse($evenement->end_heure)->format('H:i') }}</span>
                    </div>
                    <div class="flex items-center bg-white/5 px-3.5 py-1.5 rounded-xl border border-white/5">
                        <i data-feather="map-pin" class="w-4 h-4 mr-2 text-indigo-400"></i>
                        <span>{{ $evenement->lieu }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                @if($evenement->statut !== 'publier')
                    <a href="{{ route('organisateur.publier', $evenement->id) }}" class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-xl flex items-center justify-center space-x-2 transition-all hover:-translate-y-0.5">
                        <i data-feather="globe" class="w-4 h-4"></i>
                        <span>Publier</span>
                    </a>
                @endif
                <a href="{{ route('organisateur.update_form', $evenement->id) }}" class="px-5 py-2.5 bg-white/5 hover:bg-white/10 text-white font-semibold rounded-xl border border-white/10 flex items-center justify-center space-x-2 transition-all">
                    <i data-feather="edit-3" class="w-4 h-4"></i>
                    <span>Modifier</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Media & Description Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Media Showcase -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Event Photo -->
            @if($evenement->photo)
                <div class="glass-card rounded-2xl overflow-hidden border border-white/5">
                    <img src="{{ asset('storage/evenement/photo/' . $evenement->photo) }}" class="w-full h-64 object-cover" alt="Image de l'événement">
                </div>
            @else
                <div class="glass-card rounded-2xl border border-white/5 h-64 flex items-center justify-center">
                    <div class="text-center text-gray-500">
                        <i data-feather="image" class="w-12 h-12 mx-auto mb-2"></i>
                        <p class="text-sm">Aucune image</p>
                    </div>
                </div>
            @endif

            <!-- Event Video -->
            @if($evenement->video)
                <div class="glass-card rounded-2xl overflow-hidden border border-white/5 p-4">
                    <h4 class="text-sm font-bold text-white mb-3 flex items-center">
                        <i data-feather="video" class="w-4 h-4 mr-2 text-indigo-400"></i>
                        Vidéo de présentation
                    </h4>
                    <video controls class="w-full h-40 object-cover rounded-xl bg-black/40">
                        <source src="{{ asset('storage/evenement/videos/' . $evenement->video) }}" type="video/mp4">
                    </video>
                </div>
            @endif

            <!-- Billets Stats -->
            @if(isset($evenement->billets) && $evenement->billets->count() > 0)
                <div class="glass-card rounded-2xl p-6 border border-white/5">
                    <h4 class="text-sm font-bold text-white mb-4 flex items-center">
                        <i data-feather="tag" class="w-4 h-4 mr-2 text-indigo-400"></i>
                        Billets
                    </h4>
                    <div class="space-y-3">
                        @foreach($evenement->billets as $billet)
                            <div class="flex items-center justify-between bg-white/5 rounded-xl p-3 border border-white/5">
                                <span class="text-sm text-gray-300">{{ $billet->type ?? 'Standard' }}</span>
                                <span class="text-sm font-bold text-white">{{ number_format($billet->prix ?? 0) }} FCFA</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Details Column -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Description -->
            <div class="glass-card rounded-2xl p-6 md:p-8 space-y-4">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i data-feather="align-left" class="w-5 h-5 mr-2 text-indigo-400"></i>
                    Description de l'événement
                </h3>
                <p class="text-gray-300 text-sm leading-relaxed whitespace-pre-line">
                    {{ $evenement->description ?? 'Aucune description disponible pour cet événement.' }}
                </p>
            </div>

            <!-- Organizer Info Widget -->
            <div class="glass-card rounded-2xl p-6 space-y-4">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i data-feather="user" class="w-5 h-5 mr-2 text-indigo-400"></i>
                    Coordonnées de l'organisateur
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-300">
                    <div class="bg-white/5 p-3 rounded-xl border border-white/5">
                        <span class="text-[10px] text-gray-500 block uppercase font-bold">Nom complet</span>
                        <span class="font-semibold text-white mt-1 block">{{ $evenement->nom_proprietaire ?? 'Non défini' }}</span>
                    </div>
                    <div class="bg-white/5 p-3 rounded-xl border border-white/5">
                        <span class="text-[10px] text-gray-500 block uppercase font-bold">Téléphone</span>
                        <span class="font-semibold text-white mt-1 block">{{ $evenement->telephone ?? 'Non défini' }}</span>
                    </div>
                    <div class="bg-white/5 p-3 rounded-xl border border-white/5">
                        <span class="text-[10px] text-gray-500 block uppercase font-bold">Email</span>
                        <span class="font-semibold text-white mt-1 block">{{ $evenement->email ?? 'Non défini' }}</span>
                    </div>
                    @if($evenement->whatsapp)
                        <div class="bg-white/5 p-3 rounded-xl border border-white/5">
                            <span class="text-[10px] text-gray-500 block uppercase font-bold">WhatsApp</span>
                            <span class="font-semibold text-white mt-1 block">{{ $evenement->whatsapp }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Social links -->
            @if($evenement->facebook || $evenement->twiter || $evenement->whatsapp)
                <div class="glass-card rounded-2xl p-6 space-y-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i data-feather="share-2" class="w-5 h-5 mr-2 text-indigo-400"></i>
                        Réseaux sociaux
                    </h3>
                    <div class="flex flex-wrap gap-3">
                        @if($evenement->facebook)
                            <a href="{{ $evenement->facebook }}" target="_blank" class="flex items-center gap-2 bg-blue-600/10 hover:bg-blue-600/20 text-blue-400 px-4 py-2 rounded-xl text-sm font-semibold border border-blue-600/20 transition-all">
                                <i class="fab fa-facebook"></i>
                                Facebook
                            </a>
                        @endif
                        @if($evenement->twiter)
                            <a href="{{ $evenement->twiter }}" target="_blank" class="flex items-center gap-2 bg-sky-500/10 hover:bg-sky-500/20 text-sky-400 px-4 py-2 rounded-xl text-sm font-semibold border border-sky-500/20 transition-all">
                                <i class="fab fa-twitter"></i>
                                Twitter
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Danger Zone -->
            <div class="glass-card rounded-2xl p-6 border border-red-500/10">
                <h3 class="text-lg font-bold text-red-400 flex items-center mb-4">
                    <i data-feather="alert-triangle" class="w-5 h-5 mr-2"></i>
                    Zone dangereuse
                </h3>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-300 font-semibold">Supprimer cet événement</p>
                        <p class="text-xs text-gray-500 mt-0.5">Cette action est irréversible.</p>
                    </div>
                    <a href="{{ route('organisateur.supprimer', $evenement->id) }}" 
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cet événement ?')"
                       class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 font-semibold rounded-xl border border-red-500/20 text-sm transition-all flex items-center gap-2">
                        <i data-feather="trash-2" class="w-4 h-4"></i>
                        Supprimer
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
