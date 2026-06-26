@extends('layouts.Obase')
@section('title', '| Détail de l\'événement')
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

    <!-- Title and Meta Cards -->
    <div class="glass-card rounded-2xl p-6 md:p-8 relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-32 h-32 rounded-full bg-accentIndigo/10 blur-2xl"></div>
        
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6 relative z-10">
            <div class="space-y-4">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-400 bg-indigo-500/5 px-2.5 py-1 rounded-lg border border-indigo-500/10">
                    {{ $evenement->categorie }}
                </span>
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

            <!-- Edit Button -->
            <a href="{{ route('organisateur.update_form', $evenement->id) }}" class="px-5 py-2.5 bg-white/5 hover:bg-white/10 text-white font-semibold rounded-xl border border-white/10 flex items-center justify-center space-x-2 transition-all">
                <i data-feather="edit-3" class="w-4 h-4"></i>
                <span>Modifier</span>
            </a>
        </div>
    </div>

    <!-- Media & Description Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Media Showcase -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Event Photo -->
            <div class="glass-card rounded-2xl overflow-hidden border border-white/5">
                <img src="{{ asset('storage/evenement/photo/' . $evenement->photo) }}" class="w-full h-64 object-cover" alt="Image de l'événement">
            </div>

            <!-- Event Video -->
            @if ($evenement->video)
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
                        <span class="text-xxs text-gray-500 block uppercase font-bold">Nom complet</span>
                        <span class="font-semibold text-white mt-1 block">{{ $evenement->nom_proprietaire ?? 'Non défini' }}</span>
                    </div>
                    <div class="bg-white/5 p-3 rounded-xl border border-white/5">
                        <span class="text-xxs text-gray-500 block uppercase font-bold">Téléphone</span>
                        <span class="font-semibold text-white mt-1 block">{{ $evenement->telephone ?? 'Non défini' }}</span>
                    </div>
                    <div class="bg-white/5 p-3 rounded-xl border border-white/5">
                        <span class="text-xxs text-gray-500 block uppercase font-bold">Email</span>
                        <span class="font-semibold text-white mt-1 block">{{ $evenement->email ?? 'Non défini' }}</span>
                    </div>
                    @if($evenement->whatsapp)
                        <div class="bg-white/5 p-3 rounded-xl border border-white/5">
                            <span class="text-xxs text-gray-500 block uppercase font-bold">WhatsApp</span>
                            <span class="font-semibold text-white mt-1 block">{{ $evenement->whatsapp }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
