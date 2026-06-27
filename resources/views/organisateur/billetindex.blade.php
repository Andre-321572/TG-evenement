@extends('layouts.Obase')
@section('title', '| Gestion des billets')
@section('content')

<div class="container mx-auto px-6 py-8 max-w-5xl">
    <!-- Header -->
    <div class="text-center mb-10">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2">Gestion des Billets</h1>
        <p class="text-gray-400 text-sm max-w-xl mx-auto">
            Gérez la tarification, le stock, et visualisez les ventes de vos billets d'événements.
        </p>
    </div>

    <!-- Main actions grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Card 1: Create Billet -->
        <div class="glass-card group rounded-2xl p-6 hover:translate-y-[-4px] transition-all duration-300 flex flex-col justify-between items-center text-center">
            <div class="w-16 h-16 bg-indigo-500/10 rounded-2xl flex items-center justify-center text-indigo-400 mb-5 group-hover:scale-105 transition-transform">
                <i data-feather="plus" class="w-8 h-8"></i>
            </div>
            <h2 class="text-xl font-bold text-white mb-2">Créer un billet</h2>
            <p class="text-gray-400 text-xs mb-6 max-w-xs leading-relaxed">
                Ajoutez un nouveau type de billet (VIP, Standard, etc.) à l'un de vos événements.
            </p>
            <a href="{{ route('organisateur.billet-form') }}" class="w-full sm:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl inline-flex items-center justify-center space-x-2 transition-colors duration-200">
                <i data-feather="plus" class="w-4 h-4"></i>
                <span>Nouveau type de billet</span>
            </a>
        </div>

        <!-- Card 2: List Billets -->
        <div class="glass-card group rounded-2xl p-6 hover:translate-y-[-4px] transition-all duration-300 flex flex-col justify-between items-center text-center">
            <div class="w-16 h-16 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-400 mb-5 group-hover:scale-105 transition-transform">
                <i data-feather="list" class="w-8 h-8"></i>
            </div>
            <h2 class="text-xl font-bold text-white mb-2">Voir tous les billets</h2>
            <p class="text-gray-400 text-xs mb-6 max-w-xs leading-relaxed">
                Consultez la liste de tous vos billets, suivez les stocks et examinez les performances de vente.
            </p>
            <a href="{{ route('organisateur.billet-all') }}" class="w-full sm:w-auto px-6 py-3 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 font-semibold rounded-xl border border-emerald-500/20 hover:border-emerald-500/30 inline-flex items-center justify-center space-x-2 transition-all">
                <i data-feather="eye" class="w-4 h-4"></i>
                <span>Consulter les ventes</span>
            </a>
        </div>
    </div>

    <!-- Info widgets -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="glass-card rounded-xl p-4 flex items-center space-x-3">
            <div class="p-2.5 bg-blue-500/10 rounded-xl text-blue-400 flex-shrink-0">
                <i data-feather="zap" class="w-5 h-5"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-white">Création instantanée</h4>
                <p class="text-xxs text-gray-500">Génération automatique des billets</p>
            </div>
        </div>

        <div class="glass-card rounded-xl p-4 flex items-center space-x-3">
            <div class="p-2.5 bg-emerald-500/10 rounded-xl text-emerald-400 flex-shrink-0">
                <i data-feather="shield" class="w-5 h-5"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-white">Suivi sécurisé</h4>
                <p class="text-xxs text-gray-500">Mise à jour automatique des stocks</p>
            </div>
        </div>

        <div class="glass-card rounded-xl p-4 flex items-center space-x-3">
            <div class="p-2.5 bg-purple-500/10 rounded-xl text-purple-400 flex-shrink-0">
                <i data-feather="bar-chart-2" class="w-5 h-5"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-white">Performances</h4>
                <p class="text-xxs text-gray-500">Chiffre d'affaires en temps réel</p>
            </div>
        </div>
    </div>
</div>

@endsection
