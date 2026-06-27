@extends('layouts.Obase')
@section('title', 'Tous les Billets')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-7xl space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-3xl font-extrabold text-white">Tous les Billets</h1>
            <p class="text-gray-400 mt-1">Vue d'ensemble de tous vos billets d'événements et statistiques de ventes.</p>
        </div>
        <div class="flex-shrink-0 flex items-center space-x-3">
            <a href="{{ route('organisateur.billet-form') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl flex items-center justify-center space-x-2 transition-colors duration-200">
                <i data-feather="plus" class="w-4 h-4"></i>
                <span>Nouveau Billet</span>
            </a>
        </div>
    </div>

    <!-- Notifications -->
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white transition-colors">×</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-white transition-colors">×</button>
        </div>
    @endif

    <!-- Global Stats Cards -->
    @if($billets->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Stat 1 -->
            <div class="glass-card rounded-2xl p-5 hover:translate-y-[-4px] transition-all duration-300 relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-20 h-20 rounded-full bg-indigo-500/5 blur-xl"></div>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Total Billets</p>
                        <h3 class="text-2xl font-extrabold text-white mt-1">
                            {{ number_format($billets->sum('quantite_totale')) }}
                        </h3>
                    </div>
                    <div class="p-2.5 rounded-xl bg-indigo-500/10 text-indigo-400">
                        <i data-feather="tag" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-xxs text-gray-500 mt-4">
                    <span class="text-indigo-400 font-semibold">{{ $billets->count() }}</span> types différents
                </p>
            </div>

            <!-- Stat 2 -->
            <div class="glass-card rounded-2xl p-5 hover:translate-y-[-4px] transition-all duration-300 relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-20 h-20 rounded-full bg-emerald-500/5 blur-xl"></div>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Billets Vendus</p>
                        <h3 class="text-2xl font-extrabold text-emerald-400 mt-1">
                            {{ number_format($billets->sum('quantite_vendue')) }}
                        </h3>
                    </div>
                    <div class="p-2.5 rounded-xl bg-emerald-500/10 text-emerald-400">
                        <i data-feather="trending-up" class="w-5 h-5"></i>
                    </div>
                </div>
                @php
                    $totalStock = $billets->sum('quantite_totale');
                    $totalVendu = $billets->sum('quantite_vendue');
                    $tauxGlobal = $totalStock > 0 ? ($totalVendu / $totalStock) * 100 : 0;
                @endphp
                <p class="text-xxs text-gray-500 mt-4">
                    <span class="text-emerald-400 font-semibold">{{ number_format($tauxGlobal, 1) }}%</span> du stock vendu
                </p>
            </div>

            <!-- Stat 3 -->
            <div class="glass-card rounded-2xl p-5 hover:translate-y-[-4px] transition-all duration-300 relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-20 h-20 rounded-full bg-blue-500/5 blur-xl"></div>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Disponibles</p>
                        <h3 class="text-2xl font-extrabold text-blue-400 mt-1">
                            {{ number_format($billets->sum('quantite_disponible')) }}
                        </h3>
                    </div>
                    <div class="p-2.5 rounded-xl bg-blue-500/10 text-blue-400">
                        <i data-feather="box" class="w-5 h-5"></i>
                    </div>
                </div>
                @php
                    $billetsEpuises = $billets->where('quantite_disponible', 0)->count();
                @endphp
                <p class="text-xxs text-gray-500 mt-4">
                    @if($billetsEpuises > 0)
                        <span class="text-red-400 font-semibold">{{ $billetsEpuises }}</span> type(s) épuisé(s)
                    @else
                        <span class="text-emerald-400 font-semibold">Tous</span> en stock
                    @endif
                </p>
            </div>

            <!-- Stat 4 -->
            <div class="glass-card rounded-2xl p-5 hover:translate-y-[-4px] transition-all duration-300 relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-20 h-20 rounded-full bg-yellow-500/5 blur-xl"></div>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">CA Réalisé</p>
                        <h3 class="text-2xl font-extrabold text-yellow-400 mt-1">
                            {{ number_format($billets->sum(function($b) { return $b->quantite_vendue * $b->prix; }), 0, ',', ' ') }}
                        </h3>
                    </div>
                    <div class="p-2.5 rounded-xl bg-yellow-500/10 text-yellow-400">
                        <i data-feather="dollar-sign" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-xxs text-gray-500 mt-4">
                    En <span class="text-yellow-400 font-semibold">FCFA</span> générés
                </p>
            </div>
        </div>
    @endif

    <!-- Table Card -->
    <div class="glass-card rounded-2xl overflow-hidden mt-6">
        <div class="table-container">
            <table class="min-w-full divide-y divide-white/5">
                <thead class="bg-white/[0.02]">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Événement</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Type & Prix</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Ventes</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Performance</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($billets as $billet)
                        <tr class="hover:bg-white/[0.01] transition-colors">
                            <!-- Event Details -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-white">{{ $billet->evenement->titre }}</div>
                                    <div class="text-xs text-gray-400 mt-1 flex items-center">
                                        <i data-feather="calendar" class="w-3.5 h-3.5 mr-1 text-indigo-400"></i>
                                        {{ \Carbon\Carbon::parse($billet->evenement->date)->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-0.5 flex items-center">
                                        <i data-feather="map-pin" class="w-3.5 h-3.5 mr-1 text-indigo-400"></i>
                                        {{ Str::limit($billet->evenement->lieu, 30) }}
                                    </div>
                                </div>
                            </td>

                            <!-- Type & Price -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 max-w-fit uppercase tracking-wider">
                                        {{ $billet->type }}
                                    </span>
                                    <span class="text-sm font-bold text-emerald-400 mt-1">
                                        {{ number_format($billet->prix, 0, ',', ' ') }} FCFA
                                    </span>
                                </div>
                            </td>

                            <!-- Stock -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-sm font-semibold text-white">
                                        {{ $billet->quantite_disponible }}/{{ $billet->quantite_totale }}
                                    </span>
                                    @php
                                        $stockPourcentage = $billet->quantite_totale > 0
                                            ? ($billet->quantite_disponible / $billet->quantite_totale) * 100
                                            : 0;
                                    @endphp
                                    <div class="w-16 bg-white/5 rounded-full h-1 mt-1.5">
                                        <div class="h-1 rounded-full @if($stockPourcentage > 50) bg-emerald-400 @elseif($stockPourcentage > 20) bg-yellow-400 @else bg-red-400 @endif"
                                             style="width: {{ $stockPourcentage }}%">
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Sales -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-sm font-semibold text-blue-400">
                                        {{ $billet->quantite_vendue }}
                                    </span>
                                    <span class="text-xxs text-gray-500">
                                        {{ number_format($billet->quantite_vendue * $billet->prix, 0, ',', ' ') }} FCFA
                                    </span>
                                </div>
                            </td>

                            <!-- Performance percentage -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $tauxVente = $billet->quantite_totale > 0
                                        ? ($billet->quantite_vendue / $billet->quantite_totale) * 100
                                        : 0;
                                @endphp
                                <div class="flex flex-col items-center">
                                    <span class="text-sm font-bold @if($tauxVente >= 80) text-emerald-400 @elseif($tauxVente >= 50) text-yellow-400 @else text-gray-400 @endif">
                                        {{ number_format($tauxVente, 1) }}%
                                    </span>
                                    <div class="w-12 bg-white/5 rounded-full h-1 mt-1.5">
                                        <div class="h-1 rounded-full @if($tauxVente >= 80) bg-emerald-400 @elseif($tauxVente >= 50) bg-yellow-400 @else bg-indigo-400 @endif"
                                             style="width: {{ $tauxVente }}%">
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Status badge -->
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                @if($billet->quantite_disponible <= 0)
                                    <span class="bg-red-500/10 text-red-400 border border-red-500/20 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide">
                                        Épuisé
                                    </span>
                                @elseif($tauxVente >= 80)
                                    <span class="bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide">
                                        Faible
                                    </span>
                                @else
                                    <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide">
                                        Disponible
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold">
                                <a href="{{ route('organisateur.billet-form') }}" class="text-indigo-400 hover:text-indigo-300 transition-colors"><i data-feather="edit-3" class="w-4 h-4 inline"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i data-feather="tag" class="w-12 h-12 text-gray-600 mb-3"></i>
                                    <p class="text-sm">Aucun billet configuré pour l'instant.</p>
                                    <a href="{{ route('organisateur.billet-form') }}" class="mt-3 text-xs font-semibold text-indigo-400 hover:text-indigo-300">Créer mon premier billet →</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($billets->hasPages())
            <div class="px-6 py-4 bg-white/[0.01] border-t border-white/5 flex items-center justify-between text-xs text-gray-400">
                <div>
                    Affichage de {{ $billets->firstItem() }} à {{ $billets->lastItem() }} sur {{ $billets->total() }} billets.
                </div>
                <div>
                    {{ $billets->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-refresh logic
        setInterval(function() {
            if (document.visibilityState === 'visible') {
                location.reload();
            }
        }, 300000); // 5 minutes
    });
</script>
@endpush
@endsection
