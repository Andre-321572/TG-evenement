@extends('layouts.Obase')
@section('title', "| Brouillons en attente de publication")
@section('content')

<div class="container mx-auto px-6 py-8 max-w-7xl">
    <!-- Notifications -->
    @if(session('success'))
        <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl shadow-lg flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-emerald-400"></i>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 px-6 py-4 rounded-2xl shadow-lg flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-red-400"></i>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-white transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-white">Brouillons & En organisation</h1>
        <p class="text-gray-400 mt-1">Gérez vos événements en cours de préparation avant leur publication officielle.</p>
        <div class="mt-4">
            <span class="bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 px-4 py-1.5 rounded-full text-xs font-bold">
                {{ $evenementsEnAttente->count() }} brouillon(s)
            </span>
        </div>
    </div>

    @if ($evenementsEnAttente->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($evenementsEnAttente as $ev)
                <div class="glass-card group rounded-2xl overflow-hidden hover:translate-y-[-4px] transition-all duration-300 flex flex-col justify-between">
                    
                    <!-- Image Area -->
                    <div class="relative h-48 bg-black/40 overflow-hidden">
                        @if($ev->photo)
                            <img src="{{ asset('storage/evenement/photo/' . $ev->photo) }}"
                                 alt="{{ $ev->titre }}"
                                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="absolute inset-0 bg-white/[0.02] flex items-center justify-center">
                                <i data-feather="image" class="w-12 h-12 text-gray-600"></i>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0a0b10] via-transparent to-transparent"></div>
                        <div class="absolute top-4 left-4">
                            <span class="bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 px-3 py-1 rounded-full text-xs font-bold backdrop-blur-md">
                                Brouillon
                            </span>
                        </div>
                    </div>

                    <!-- Details Area -->
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-indigo-400 bg-indigo-500/5 px-2.5 py-1 rounded-lg border border-indigo-500/10">
                                {{ $ev->categorie }}
                            </span>
                            <h3 class="text-lg font-bold text-white mt-3 truncate">{{ $ev->titre }}</h3>
                            
                            <div class="mt-4 space-y-2">
                                <div class="flex items-center text-sm text-gray-400">
                                    <i data-feather="calendar" class="w-4 h-4 mr-2 text-indigo-400"></i>
                                    <span>{{ \Carbon\Carbon::parse($ev->date)->format('d M Y') }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-400">
                                    <i data-feather="map-pin" class="w-4 h-4 mr-2 text-indigo-400"></i>
                                    <span class="truncate">{{ $ev->lieu }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Controls -->
                        <div class="mt-6 pt-4 border-t border-white/5 space-y-3">
                            <!-- Quick Publish -->
                            <a href="{{ route('organisateur.publier', $ev->id) }}" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl flex items-center justify-center space-x-1.5 transition-colors duration-200">
                                <i data-feather="globe" class="w-3.5 h-3.5"></i>
                                <span>Publier maintenant</span>
                            </a>

                            <div class="flex items-center justify-between text-xs pt-1">
                                <a href="{{ route('organisateur.update_form', $ev->id) }}" class="text-indigo-400 hover:text-indigo-300 font-semibold flex items-center space-x-1">
                                    <i data-feather="edit-2" class="w-3 h-3"></i>
                                    <span>Compléter</span>
                                </a>
                                <a href="{{ route('organisateur.supprimer', $ev->id) }}" onclick="return confirm('Supprimer ce brouillon ?')" class="text-red-400 hover:text-red-300 font-semibold flex items-center space-x-1">
                                    <i data-feather="trash-2" class="w-3 h-3"></i>
                                    <span>Supprimer</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16 glass-card rounded-2xl p-8 max-w-lg mx-auto">
            <div class="w-20 h-20 rounded-full bg-indigo-500/5 border border-indigo-500/10 flex items-center justify-center mx-auto mb-6 text-indigo-400">
                <i data-feather="edit-3" class="w-10 h-10"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">Aucun brouillon</h3>
            <p class="text-gray-400 mb-6">
                Vous n'avez pas d'événement en cours de préparation. Tous vos événements sont en ligne !
            </p>
            <a href="{{ route('organisateur.ajouter-un-evenement') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl inline-flex items-center space-x-2 transition-colors duration-200">
                <i data-feather="plus" class="w-4 h-4"></i>
                <span>Créer un nouvel événement</span>
            </a>
        </div>
    @endif
</div>
@endsection
