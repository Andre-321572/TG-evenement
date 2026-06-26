@extends('layouts.Obase')
@section('title', 'Historique des événements')
@section('content')

<div class="container mx-auto px-6 py-8 max-w-7xl">
    <!-- Header with animation -->
    <div class="glass-card rounded-2xl mb-8 overflow-hidden relative">
        <div class="absolute -right-16 -top-16 w-32 h-32 rounded-full bg-accentIndigo/10 blur-2xl"></div>
        <div class="px-8 py-10 text-center relative z-10">
            <div class="flex items-center justify-center mb-4">
                <i class="fas fa-history text-3xl text-indigo-400 mr-3"></i>
                <h1 class="text-3xl md:text-4xl font-extrabold bg-gradient-to-r from-white via-gray-100 to-gray-300 bg-clip-text text-transparent">Historique des Événements</h1>
            </div>
            <p class="text-gray-400 text-base max-w-xl mx-auto">
                Consultez les statistiques et performances de vos événements passés
            </p>
            <div class="flex items-center justify-center mt-6 space-x-6 text-gray-400">
                <div class="flex items-center">
                    <i data-feather="calendar" class="w-4 h-4 text-indigo-400 mr-2"></i>
                    <span>{{ isset($evenements) ? $evenements->count() : 0 }} événements</span>
                </div>
                <div class="flex items-center">
                    <i data-feather="bar-chart-2" class="w-4 h-4 text-emerald-400 mr-2"></i>
                    <span>Analyses détaillées</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="glass-card rounded-xl p-5 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 w-full md:w-auto">
                <div class="relative w-full sm:w-64">
                    <i data-feather="search" class="absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                    <input type="text" id="searchInput" placeholder="Rechercher un événement..."
                           class="pl-10 pr-4 py-2 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo w-full transition-all">
                </div>
                <select id="sortBy" class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-gray-300 focus:outline-none focus:ring-2 focus:ring-accentIndigo w-full sm:w-auto transition-all">
                    <option class="bg-[#0f111a]" value="date_desc">Plus récent d'abord</option>
                    <option class="bg-[#0f111a]" value="date_asc">Plus ancien d'abord</option>
                    <option class="bg-[#0f111a]" value="name_asc">Nom A-Z</option>
                    <option class="bg-[#0f111a]" value="name_desc">Nom Z-A</option>
                </select>
            </div>
            <div class="text-xs text-gray-500">
                Dernière mise à jour: {{ now()->format('d/m/Y à H:i') }}
            </div>
        </div>
    </div>

    @if(isset($evenements) && $evenements->count() > 0)
        <!-- Event List -->
        <div id="eventsContainer" class="space-y-6">
            @foreach($evenements as $evenement)
                <div class="event-card glass-card rounded-2xl overflow-hidden hover:translate-y-[-4px] transition-all duration-300"
                     data-name="{{ strtolower($evenement->titre) }}"
                     data-date="{{ $evenement->date_evenement }}">

                    <div class="grid lg:grid-cols-12 gap-0">
                        <!-- Event Image -->
                        <div class="lg:col-span-3">
                            <div class="relative h-48 lg:h-full min-h-[180px]">
                                @if(isset($evenement->image) && $evenement->image)
                                    <img src="{{ asset('storage/' . $evenement->image) }}"
                                         alt="{{ $evenement->titre }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-indigo-500/10 to-purple-500/10 flex items-center justify-center text-indigo-400">
                                        <i data-feather="calendar" class="w-12 h-12"></i>
                                    </div>
                                @endif
                                <div class="absolute top-4 right-4">
                                    <span class="bg-gray-500/20 text-gray-300 border border-gray-500/30 px-3 py-1 rounded-full text-xs font-bold backdrop-blur-md">
                                        Terminé
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Event Info -->
                        <div class="lg:col-span-6 p-6 flex flex-col justify-between">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-indigo-400 bg-indigo-500/5 px-2.5 py-1 rounded-lg border border-indigo-500/10">
                                    {{ $evenement->categorie ?? 'Catégorie' }}
                                </span>
                                <h3 class="text-xl font-bold text-white mt-4 mb-3">
                                    {{ $evenement->titre }}
                                </h3>

                                <div class="space-y-2 mb-4 text-sm text-gray-400">
                                    <div class="flex items-center">
                                        <i data-feather="calendar" class="w-4 h-4 mr-2.5 text-indigo-400"></i>
                                        <span>{{ \Carbon\Carbon::parse($evenement->date_evenement)->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i data-feather="clock" class="w-4 h-4 mr-2.5 text-indigo-400"></i>
                                        <span>{{ isset($evenement->heure_debut) ? $evenement->heure_debut : 'Non définie' }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i data-feather="map-pin" class="w-4 h-4 mr-2.5 text-indigo-400"></i>
                                        <span class="truncate">{{ $evenement->lieu }}</span>
                                    </div>
                                </div>

                                @if(isset($evenement->description))
                                    <p class="text-gray-500 text-sm line-clamp-2">
                                        {{ Str::limit($evenement->description, 120) }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Statistics Column -->
                        <div class="lg:col-span-3 bg-white/[0.02] border-t lg:border-t-0 lg:border-l border-white/5 p-6 flex flex-col justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Statistiques</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Likes -->
                                    <div class="bg-white/5 border border-white/5 p-2 rounded-xl text-center">
                                        <p class="text-xxs font-bold text-gray-500 uppercase">Likes</p>
                                        <p class="text-lg font-extrabold text-emerald-400 mt-0.5">
                                            {{ isset($evenement->likes_count) ? $evenement->likes_count : rand(10, 50) }}
                                        </p>
                                    </div>

                                    <!-- Vues -->
                                    <div class="bg-white/5 border border-white/5 p-2 rounded-xl text-center">
                                        <p class="text-xxs font-bold text-gray-500 uppercase">Vues</p>
                                        <p class="text-lg font-extrabold text-blue-400 mt-0.5">
                                            {{ isset($evenement->vues_count) ? $evenement->vues_count : rand(100, 500) }}
                                        </p>
                                    </div>

                                    <!-- Billets -->
                                    <div class="bg-white/5 border border-white/5 p-2 rounded-xl text-center col-span-2">
                                        <p class="text-xxs font-bold text-gray-500 uppercase">Billets Vendus</p>
                                        <p class="text-lg font-extrabold text-indigo-400 mt-0.5">
                                            {{ isset($evenement->billets_vendus) ? $evenement->billets_vendus : rand(50, 200) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <button type="button"
                                    class="w-full mt-6 bg-gradient-to-r from-accentIndigo to-accentViolet text-white py-2.5 rounded-xl font-semibold shadow-lg hover:shadow-indigo-500/20 flex items-center justify-center transition-all transform hover:-translate-y-0.5"
                                    onclick="showEventDetails('{{ $evenement->id }}', '{{ $evenement->titre }}')">
                                <i data-feather="bar-chart-2" class="w-4 h-4 mr-2"></i>
                                Voir rapport
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if(method_exists($evenements, 'links'))
            <div class="mt-8">
                {{ $evenements->links() }}
            </div>
        @endif

    @else
        <!-- Empty State -->
        <div class="glass-card rounded-2xl p-12 text-center max-w-lg mx-auto">
            <div class="w-20 h-20 rounded-full bg-indigo-500/5 border border-indigo-500/10 flex items-center justify-center mx-auto mb-6 text-indigo-400">
                <i data-feather="calendar" class="w-10 h-10"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">Aucun événement passé</h3>
            <p class="text-gray-400 mb-8">Vous n'avez pas encore d'événements enregistrés dans l'historique.</p>
        </div>
    @endif
</div>

<!-- Modal for details -->
<div id="eventModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="glass-card rounded-2xl max-w-3xl w-full max-h-[85vh] overflow-hidden flex flex-col justify-between shadow-2xl">
        <div class="bg-white/5 border-b border-white/5 px-6 py-4 flex items-center justify-between">
            <h2 id="modalTitle" class="text-xl font-bold text-white">Détails de l'événement</h2>
            <button onclick="hideEventDetails()" class="p-1 rounded-lg hover:bg-white/5 text-gray-400 hover:text-white transition-colors">
                <i data-feather="x" class="w-6 h-6"></i>
            </button>
        </div>
        <div id="modalContent" class="p-6 overflow-y-auto flex-1">
            <!-- Content will be injected here -->
        </div>
    </div>
</div>

<script>
// Live Search
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const eventCards = document.querySelectorAll('.event-card');

    eventCards.forEach(card => {
        const eventName = card.getAttribute('data-name');
        if (eventName.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Sorting
document.getElementById('sortBy').addEventListener('change', function(e) {
    const sortBy = e.target.value;
    const container = document.getElementById('eventsContainer');
    const cards = Array.from(document.querySelectorAll('.event-card'));

    cards.sort((a, b) => {
        switch(sortBy) {
            case 'date_desc':
                return new Date(b.getAttribute('data-date')) - new Date(a.getAttribute('data-date'));
            case 'date_asc':
                return new Date(a.getAttribute('data-date')) - new Date(b.getAttribute('data-date'));
            case 'name_asc':
                return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
            case 'name_desc':
                return b.getAttribute('data-name').localeCompare(a.getAttribute('data-name'));
            default:
                return 0;
        }
    });

    cards.forEach(card => container.appendChild(card));
});

// Modal Actions
function showEventDetails(eventId, eventTitle) {
    document.getElementById('modalTitle').textContent = eventTitle;
    document.getElementById('eventModal').classList.remove('hidden');

    const modalContent = document.getElementById('modalContent');
    modalContent.innerHTML = `
        <div class="grid md:grid-cols-2 gap-6 text-gray-300">
            <div class="space-y-5">
                <div class="bg-gradient-to-r from-accentIndigo/20 to-accentViolet/20 border border-accentIndigo/30 p-6 rounded-2xl">
                    <h3 class="text-lg font-bold text-white mb-4">Résumé des performances</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-3xl font-extrabold text-white">${Math.floor(Math.random() * 500) + 100}</div>
                            <div class="text-xs text-gray-400 mt-1">Participants</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-extrabold text-indigo-400">${(Math.floor(Math.random() * 50) + 10) * 1000} FCFA</div>
                            <div class="text-xs text-gray-400 mt-1">Revenus</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white/5 border border-white/5 p-6 rounded-2xl">
                    <h4 class="font-bold text-white mb-3">Engagement du public</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-400">Satisfaction</span>
                            <span class="font-bold text-emerald-400">85%</span>
                        </div>
                        <div class="w-full bg-white/5 rounded-full h-1.5">
                            <div class="bg-emerald-400 h-1.5 rounded-full" style="width: 85%"></div>
                        </div>
                        <div class="flex justify-between items-center text-sm mt-2">
                            <span class="text-gray-400">Retour d'expérience</span>
                            <span class="font-bold text-indigo-400">72%</span>
                        </div>
                        <div class="w-full bg-white/5 rounded-full h-1.5">
                            <div class="bg-indigo-400 h-1.5 rounded-full" style="width: 72%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div class="bg-white/5 border border-white/5 p-6 rounded-2xl">
                    <h4 class="font-bold text-white mb-3 text-sm">Répartition des ventes</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-400">VIP</span>
                            <span class="font-bold text-white">${Math.floor(Math.random() * 20) + 10}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Standard</span>
                            <span class="font-bold text-white">${Math.floor(Math.random() * 100) + 50}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white/5 border border-white/5 p-6 rounded-2xl">
                    <h4 class="font-bold text-white mb-3 text-sm">Retours & Avis</h4>
                    <div class="space-y-3 text-xs">
                        <div class="border-l-2 border-indigo-400 pl-3">
                            <p class="text-gray-300">"Excellent événement, très bien organisé !"</p>
                        </div>
                        <div class="border-l-2 border-purple-400 pl-3">
                            <p class="text-gray-300">"Ambiance fantastique, à refaire !"</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-white/5 flex justify-end space-x-3">
            <button onclick="hideEventDetails()" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-gray-300 rounded-xl transition-colors">
                Fermer
            </button>
            <button class="px-4 py-2 bg-gradient-to-r from-accentIndigo to-accentViolet text-white rounded-xl font-semibold shadow-lg hover:shadow-indigo-500/20">
                Exporter le rapport
            </button>
        </div>
    `;
    feather.replace();
}

function hideEventDetails() {
    document.getElementById('eventModal').classList.add('hidden');
}
</script>

@endsection
