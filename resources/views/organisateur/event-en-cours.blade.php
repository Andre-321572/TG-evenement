@extends('layouts.Obase')
@section('title', ' | gestion des evenements en cours')
@section('content')

<div class="container mx-auto px-6 py-8 max-w-7xl">
    <!-- Success Messages -->
    @if (session('evenement_ajouter') || session('success'))
        <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl shadow-lg flex items-center justify-between animate-pulse">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-emerald-400 text-lg"></i>
                <span class="font-semibold">{{ session('evenement_ajouter') ?? session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-3xl font-extrabold text-white">Gestion des Événements</h1>
            <p class="text-gray-400 mt-1">Consultez et gérez l'ensemble des événements créés.</p>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('organisateur.ajouter-un-evenement') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl flex items-center justify-center space-x-2 transition-colors duration-200">
                <i data-feather="plus" class="w-4 h-4"></i>
                <span>Créer un événement</span>
            </a>
        </div>
    </div>

    <!-- Events Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($evenements as $nombre => $evenement)
            <div class="glass-card group rounded-2xl overflow-hidden hover:translate-y-[-8px] transition-all duration-300 flex flex-col justify-between">
                
                <!-- Card Media -->
                <div class="relative h-56 bg-black/40 overflow-hidden">
                    @if($evenement->photo)
                        <img src="{{ asset('storage/evenement/photo/' . $evenement->photo) }}"
                             alt="{{ $evenement->titre }}"
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="absolute inset-0 bg-white/[0.02] flex items-center justify-center">
                            <i data-feather="image" class="w-12 h-12 text-gray-600"></i>
                        </div>
                    @endif
                    <!-- Image Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0a0b10] via-transparent to-transparent"></div>

                    <!-- Status Badge -->
                    <div class="absolute top-4 left-4">
                        @if($evenement->statut === 'publier')
                            <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-3 py-1 rounded-full text-xs font-bold flex items-center backdrop-blur-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                                En ligne
                            </span>
                        @elseif($evenement->statut === 'en organisation')
                            <span class="bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 px-3 py-1 rounded-full text-xs font-bold flex items-center backdrop-blur-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 mr-1.5"></span>
                                Brouillon
                            </span>
                        @else
                            <span class="bg-gray-500/10 text-gray-400 border border-gray-500/20 px-3 py-1 rounded-full text-xs font-bold flex items-center backdrop-blur-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1.5"></span>
                                Archivé
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-400 bg-indigo-500/5 px-2.5 py-1 rounded-lg border border-indigo-500/10">
                            {{ $evenement->categorie }}
                        </span>
                        <h3 class="text-xl font-bold text-white mt-4 leading-snug truncate" title="{{ $evenement->titre }}">
                            {{ $evenement->titre }}
                        </h3>
                        
                        <!-- Event Meta Details -->
                        <div class="mt-4 space-y-2.5">
                            <div class="flex items-center text-sm text-gray-400">
                                <i data-feather="calendar" class="w-4 h-4 mr-2.5 text-indigo-400"></i>
                                <span>{{ \Carbon\Carbon::parse($evenement->date)->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-400">
                                <i data-feather="clock" class="w-4 h-4 mr-2.5 text-indigo-400"></i>
                                <span>{{ \Carbon\Carbon::parse($evenement->start_heure)->format('H:i') }} - {{ \Carbon\Carbon::parse($evenement->end_heure)->format('H:i') }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-400">
                                <i data-feather="map-pin" class="w-4 h-4 mr-2.5 text-indigo-400"></i>
                                <span class="truncate">{{ $evenement->lieu }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Row -->
                    <div class="mt-6 pt-4 border-t border-white/5 flex items-center justify-between">
                        <span class="text-xs text-gray-500">Par: {{ $evenement->nom_proprietaire ?? 'Admin' }}</span>
                        
                        <div class="flex items-center space-x-2">
                            <!-- View Details Button -->
                            <a href="{{ route('organisateur.detail', ['id' => $evenement->id]) }}"
                               class="p-2 bg-white/5 hover:bg-indigo-500/10 text-gray-400 hover:text-indigo-400 rounded-xl border border-white/10 hover:border-indigo-500/20 transition-all transform hover:scale-105"
                               title="Voir les détails">
                                <i data-feather="eye" class="w-4 h-4"></i>
                            </a>

                            <!-- Edit Button -->
                            <a href="{{ route('organisateur.update_form', ['id' => $evenement->id]) }}"
                               class="p-2 bg-white/5 hover:bg-yellow-500/10 text-gray-400 hover:text-yellow-400 rounded-xl border border-white/10 hover:border-yellow-500/20 transition-all transform hover:scale-105"
                               title="Modifier l'événement">
                                <i data-feather="edit-3" class="w-4 h-4"></i>
                            </a>

                            <!-- Delete Button -->
                            <a href="{{ route('organisateur.supprimer', ['id' => $evenement->id]) }}"
                               class="p-2 bg-white/5 hover:bg-red-500/10 text-gray-400 hover:text-red-400 rounded-xl border border-white/10 hover:border-red-500/20 transition-all transform hover:scale-105"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')"
                               title="Supprimer l'événement">
                                <i data-feather="trash-2" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="col-span-full text-center py-16 glass-card rounded-2xl p-8 max-w-lg mx-auto mt-6">
                <div class="w-20 h-20 rounded-full bg-indigo-500/5 border border-indigo-500/10 flex items-center justify-center mx-auto mb-6 text-indigo-400">
                    <i data-feather="calendar" class="w-10 h-10"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">Aucun événement en cours</h3>
                <p class="text-gray-400 mb-6">
                    Vous n'avez pas encore d'événements créés en base de données.
                </p>
                <a href="{{ route('organisateur.ajouter-un-evenement') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl inline-flex items-center space-x-2 transition-colors duration-200">
                    <i data-feather="plus" class="w-4 h-4"></i>
                    <span>Créer un premier événement</span>
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($evenements) && method_exists($evenements, 'hasPages') && $evenements->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $evenements->links() }}
        </div>
    @endif
</div>
@endsection
