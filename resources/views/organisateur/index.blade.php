@extends('layouts.Obase')
@section('title', '| Organisateur - Dashboard')
@section('content')
    <div class="p-6 space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Stats Card 1 -->
            <div class="glass-card rounded-2xl p-5 border-l-4 border-blue-500 hover:translate-y-[-4px] transition-all duration-300 relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm font-medium">Événements à venir</p>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-white mt-1">{{ $statistiques['evenements_a_venir'] ?? 0 }}</h3>
                    </div>
                    <div class="p-2.5 rounded-xl bg-blue-500/10 text-blue-400">
                        <i data-feather="calendar" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4 flex items-center">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span>
                    Planifiés dans le futur
                </p>
            </div>

            <!-- Stats Card 2 -->
            <div class="glass-card rounded-2xl p-5 border-l-4 border-green-500 hover:translate-y-[-4px] transition-all duration-300 relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm font-medium">Événements publiés</p>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-white mt-1">{{ $statistiques['evenements_publies'] ?? 0 }}</h3>
                    </div>
                    <div class="p-2.5 rounded-xl bg-green-500/10 text-green-400">
                        <i data-feather="check-circle" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4 flex items-center">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                    Visibles par le public
                </p>
            </div>

            <!-- Stats Card 3 -->
            <div class="glass-card rounded-2xl p-5 border-l-4 border-yellow-500 hover:translate-y-[-4px] transition-all duration-300 relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm font-medium">Total Événements</p>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-white mt-1">{{ $statistiques['total_evenements'] ?? 0 }}</h3>
                    </div>
                    <div class="p-2.5 rounded-xl bg-yellow-500/10 text-yellow-400">
                        <i data-feather="database" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4 flex items-center">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5"></span>
                    Enregistrés en base
                </p>
            </div>

            <!-- Stats Card 4 -->
            <div class="glass-card rounded-2xl p-5 border-l-4 border-purple-500 hover:translate-y-[-4px] transition-all duration-300 relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-400 text-xs sm:text-sm font-medium">En organisation</p>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-white mt-1">{{ $statistiques['evenements_en_organisation'] ?? 0 }}</h3>
                    </div>
                    <div class="p-2.5 rounded-xl bg-purple-500/10 text-purple-400">
                        <i data-feather="settings" class="w-5 h-5"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4 flex items-center">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-purple-500 mr-1.5"></span>
                    Brouillons en attente
                </p>
            </div>
        </div>

        <!-- Charts and Recent Events -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Chart Card -->
            <div class="glass-card rounded-2xl p-5 sm:p-6 xl:col-span-2 relative overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-lg font-bold text-white">Participation aux événements</h2>
                        <p class="text-xs text-gray-400">Suivi hebdomadaire de l'engagement</p>
                    </div>
                    <select class="bg-white/5 border border-white/10 text-gray-300 rounded-xl px-4 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-accentIndigo transition-all w-full sm:w-auto">
                        <option class="bg-[#0f111a]">7 derniers jours</option>
                        <option class="bg-[#0f111a]">30 derniers jours</option>
                        <option class="bg-[#0f111a]">3 derniers mois</option>
                    </select>
                </div>
                <div class="chart-container">
                    <canvas id="eventsChart"></canvas>
                </div>
            </div>

            <!-- Recent Events Card -->
            <div class="glass-card rounded-2xl p-5 sm:p-6 flex flex-col justify-between">
                <div>
                    <h2 class="text-lg font-bold text-white mb-4">Événements récents</h2>
                    <div class="space-y-3">
                        @forelse($evenementsRecents as $event)
                            <div class="flex items-center space-x-3 p-3 bg-white/5 rounded-xl border border-white/5 hover:bg-white/[0.08] transition-colors">
                                <div class="p-2 bg-indigo-500/10 rounded-xl text-indigo-400 flex-shrink-0">
                                    <i data-feather="tag" class="w-4 h-4"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-semibold text-sm text-white truncate">{{ $event->titre }}</h4>
                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</p>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 uppercase tracking-wide">
                                    {{ $event->statut }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-6">
                                <p class="text-sm text-gray-500">Aucun événement récent.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <a href="{{ route('organisateur.evenement-en-cours') }}" class="mt-4 block text-center w-full py-2.5 text-sm text-indigo-400 hover:text-indigo-300 font-semibold bg-indigo-500/5 hover:bg-indigo-500/10 border border-indigo-500/10 hover:border-indigo-500/20 rounded-xl transition-all">
                    Voir tous les événements
                </a>
            </div>
        </div>

        <!-- Upcoming Events Table -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="p-5 sm:p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center border-b border-white/5 space-y-3 sm:space-y-0 bg-white/[0.01]">
                <div>
                    <h2 class="text-lg font-bold text-white">Événements à venir</h2>
                    <p class="text-xs text-gray-400">Liste triée chronologiquement</p>
                </div>
                <a href="{{ route('organisateur.ajouter-un-evenement') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl flex items-center justify-center space-x-2 transition-colors duration-200">
                    <i data-feather="plus" class="w-4 h-4"></i>
                    <span>Nouvel événement</span>
                </a>
            </div>

            <div class="table-container">
                <table class="min-w-full divide-y divide-white/5">
                    <thead class="bg-white/[0.02]">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nom</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider hidden sm:table-cell">Lieu</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Propriétaire</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider hidden lg:table-cell">Statut</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($evenementsAVenir as $event)
                            <tr class="hover:bg-white/[0.01] transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-400">
                                            <i data-feather="calendar" class="w-5 h-5"></i>
                                        </div>
                                        <div class="ml-4 min-w-0">
                                            <div class="text-sm font-semibold text-white">{{ $event->titre }}</div>
                                            <div class="text-xs text-gray-400">{{ $event->categorie }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-white">{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($event->start_heure)->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                    <div class="text-sm text-gray-300 truncate max-w-xs">{{ $event->lieu }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-white">{{ $event->nom_proprietaire }}</div>
                                    <div class="text-xs text-gray-400">{{ $event->telephone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-indigo-500/10 text-indigo-400 uppercase tracking-wide">
                                        {{ $event->statut }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold">
                                    <a href="{{ route('organisateur.update_form', $event->id) }}" class="text-indigo-400 hover:text-indigo-300 mr-4 transition-colors"><i data-feather="edit-3" class="w-4 h-4 inline"></i></a>
                                    <a href="{{ route('organisateur.supprimer', $event->id) }}" class="text-red-400 hover:text-red-300 transition-colors" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')"><i data-feather="trash-2" class="w-4 h-4 inline"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">Aucun événement à venir.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
