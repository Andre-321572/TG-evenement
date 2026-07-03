@extends('layouts.base')
@section('title', '| Mon Compte')

@section('content')
<main class="container py-5">
    <div class="space-y-8 animate__animated animate__fadeIn">

        <!-- Welcome Profile Card -->
        <div class="bg-white border border-slate-100 p-6 sm:p-8 rounded-3xl shadow-sm relative overflow-hidden flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="absolute -right-16 -top-16 w-36 h-36 rounded-full bg-accentRed/5 blur-2xl"></div>
            <div class="flex items-center space-x-4 relative z-10">
                @if($user->img_profil)
                    <img class="w-16 h-16 rounded-full object-cover border-2 border-indigo-600" src="{{ asset('storage/' . $user->img_profil) }}" alt="{{ $user->nom }}">
                @else
                    <div class="w-16 h-16 rounded-full bg-indigo-600 flex items-center justify-center text-white keep-white font-black text-2xl shadow-md">
                        {{ strtoupper(substr($user->nom, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 leading-tight">
                        {{ $user->prenom }} {{ $user->nom }}
                    </h1>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mt-0.5">Participant</p>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-3 relative z-10">
                <a href="{{ route('p.evenement') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 bg-[#d9383a] hover:bg-[#c22e30] text-white keep-white font-bold rounded-xl text-sm transition-all shadow-md text-decoration-none">
                    <i class="fas fa-search"></i>
                    <span>Trouver un événement</span>
                </a>
            </div>
        </div>

        <!-- Navigation Tabs / Pills -->
        <div class="flex border-b border-slate-200">
            <a href="{{ route('dashboard', ['tab' => 'tickets']) }}" class="flex items-center space-x-2 px-4 sm:px-6 py-3 border-b-2 font-bold text-sm transition-all text-decoration-none {{ request('tab') === 'tickets' || !request('tab') || request('tab') === 'dashboard' ? 'border-[#d9383a] text-[#d9383a]' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                <i class="fas fa-ticket-alt"></i>
                <span>Mes Tickets</span>
            </a>
            <a href="{{ route('dashboard', ['tab' => 'favoris']) }}" class="flex items-center space-x-2 px-4 sm:px-6 py-3 border-b-2 font-bold text-sm transition-all text-decoration-none {{ request('tab') === 'favoris' ? 'border-[#d9383a] text-[#d9383a]' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                <i class="far fa-heart"></i>
                <span>Favoris</span>
            </a>
            <a href="{{ route('dashboard', ['tab' => 'historique']) }}" class="flex items-center space-x-2 px-4 sm:px-6 py-3 border-b-2 font-bold text-sm transition-all text-decoration-none {{ request('tab') === 'historique' ? 'border-[#d9383a] text-[#d9383a]' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                <i class="fas fa-history"></i>
                <span>Historique</span>
            </a>
        </div>

        <!-- Content Area -->
        <div class="pt-2">
            @if(request('tab') === 'tickets' || !request('tab') || request('tab') === 'dashboard')
                <!-- Upcoming/Active Tickets Tab -->
                <div class="space-y-6">
                    <div class="mb-2">
                        <h2 class="text-lg font-bold text-slate-900 flex items-center space-x-2">
                            <i class="fas fa-ticket-alt text-[#d9383a]"></i>
                            <span>Mes Tickets Actifs</span>
                        </h2>
                        <p class="text-xs text-slate-400 font-medium">Retrouvez ci-dessous tous les événements à venir pour lesquels vous avez acheté un billet.</p>
                    </div>

                    @if($activeTickets->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($activeTickets as $ticket)
                                @php
                                    $ev = $ticket->evenement;
                                    $evDate = \Carbon\Carbon::parse($ev->date);
                                @endphp
                                <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                                    <div class="relative overflow-hidden h-36 bg-slate-900 text-white">
                                        @if($ev->photo)
                                            <img src="{{ asset('storage/evenement/photo/' . $ev->photo) }}" alt="{{ $ev->titre }}" class="w-full h-full object-cover opacity-60">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-tr from-indigo-950 to-slate-900"></div>
                                        @endif
                                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg text-[10px] font-bold text-white bg-black/60 backdrop-blur-sm border border-white/10">
                                            {{ $ev->categorie ?? 'Ticket' }}
                                        </span>
                                    </div>

                                    <div class="p-4 flex-grow flex flex-col justify-between space-y-4">
                                        <div class="space-y-1">
                                            <h4 class="font-bold text-slate-900 text-sm leading-snug line-clamp-1">{{ $ev->titre }}</h4>
                                            <p class="text-[11px] text-slate-500 font-semibold flex items-center mb-1">
                                                <i class="far fa-calendar mr-1.5 text-[#d9383a]"></i>
                                                {{ $evDate->translatedFormat('d M Y, H:i') }}
                                            </p>
                                            <p class="text-[11px] text-slate-500 font-semibold flex items-center mb-0">
                                                <i class="fas fa-map-marker-alt mr-1.5 text-[#d9383a]"></i>
                                                {{ Str::limit($ev->lieu, 25) }}
                                            </p>
                                        </div>
                                        
                                        <div class="border-t border-slate-50 pt-3 flex items-center justify-between text-[11px] text-slate-400 font-semibold">
                                            <span>Type: {{ $ticket->billet->type }}</span>
                                            <span class="text-[#d9383a]">{{ number_format($ticket->billet->prix, 0, ',', ' ') }} F</span>
                                        </div>

                                        <div class="pt-1">
                                            <button onclick="openTicketModal('{{ $ticket->code }}', '{{ addslashes($ev->titre) }}', '{{ $evDate->translatedFormat('d M Y, H:i') }}', '{{ addslashes($ev->lieu) }}', '{{ $ticket->billet->type }}', '{{ number_format($ticket->billet->prix, 0, ',', ' ') }} FCFA', '{{ $ev->photo ? asset('storage/evenement/photo/' . $ev->photo) : '' }}')" class="w-full text-center bg-[#4f46e5] hover:bg-[#4338ca] text-white py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm border-0 flex items-center justify-center space-x-2">
                                                <i class="fas fa-qrcode"></i>
                                                <span>Afficher le Ticket</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty state tickets -->
                        <div class="bg-white border border-slate-100 rounded-3xl p-8 text-center space-y-4 shadow-sm">
                            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto text-slate-300 text-2xl">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <div class="max-w-md mx-auto space-y-1">
                                <h3 class="text-base font-bold text-slate-900">Aucun ticket actif</h3>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    Vous n'avez pas de tickets achetés pour des événements à venir.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

            @elseif(request('tab') === 'favoris')
                <!-- Favoris Tab -->
                <div class="space-y-6">
                    <div class="mb-2">
                        <h2 class="text-lg font-bold text-slate-900 flex items-center space-x-2">
                            <i class="far fa-heart text-[#d9383a]"></i>
                            <span>Mes Favoris</span>
                        </h2>
                        <p class="text-xs text-slate-400 font-medium">Retrouvez ci-dessous la liste de vos événements coups de cœur.</p>
                    </div>

                    <!-- Empty state favoris -->
                    <div class="bg-white border border-slate-100 rounded-3xl p-8 text-center space-y-4 shadow-sm">
                        <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto text-slate-300 text-2xl">
                            <i class="far fa-heart"></i>
                        </div>
                        <div class="max-w-md mx-auto space-y-1">
                            <h3 class="text-base font-bold text-slate-900">Aucun favori</h3>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Vous n'avez pas encore ajouté d'événements à vos favoris. Parcourez notre catalogue et cliquez sur l'icône cœur pour les retrouver ici !
                            </p>
                        </div>
                    </div>
                </div>

            @elseif(request('tab') === 'historique')
                <!-- Historique Tab -->
                <div class="space-y-6">
                    <div class="mb-2">
                        <h2 class="text-lg font-bold text-slate-900 flex items-center space-x-2">
                            <i class="fas fa-history text-[#d9383a]"></i>
                            <span>Historique d'achats</span>
                        </h2>
                        <p class="text-xs text-slate-400 font-medium">Retrouvez la liste de vos billets pour vos événements passés.</p>
                    </div>

                    @if($pastTickets->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($pastTickets as $ticket)
                                @php
                                    $ev = $ticket->evenement;
                                    $evDate = \Carbon\Carbon::parse($ev->date);
                                @endphp
                                <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between opacity-80">
                                    <div class="relative overflow-hidden h-36 bg-slate-950 text-white">
                                        @if($ev->photo)
                                            <img src="{{ asset('storage/evenement/photo/' . $ev->photo) }}" alt="{{ $ev->titre }}" class="w-full h-full object-cover opacity-40 grayscale">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-tr from-slate-950 to-slate-900"></div>
                                        @endif
                                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg text-[10px] font-bold text-white bg-black/60 backdrop-blur-sm border border-white/10">
                                            Terminé
                                        </span>
                                    </div>

                                    <div class="p-4 flex-grow flex flex-col justify-between space-y-4">
                                        <div class="space-y-1">
                                            <h4 class="font-bold text-slate-700 text-sm leading-snug line-clamp-1">{{ $ev->titre }}</h4>
                                            <p class="text-[11px] text-slate-500 font-semibold flex items-center mb-1">
                                                <i class="far fa-calendar mr-1.5 text-slate-400"></i>
                                                {{ $evDate->translatedFormat('d M Y, H:i') }}
                                            </p>
                                            <p class="text-[11px] text-slate-500 font-semibold flex items-center mb-0">
                                                <i class="fas fa-map-marker-alt mr-1.5 text-slate-400"></i>
                                                {{ Str::limit($ev->lieu, 25) }}
                                            </p>
                                        </div>
                                        
                                        <div class="border-t border-slate-50 pt-3 flex items-center justify-between text-[11px] text-slate-400 font-semibold">
                                            <span>Type: {{ $ticket->billet->type }}</span>
                                            <span class="text-slate-500">{{ number_format($ticket->billet->prix, 0, ',', ' ') }} F</span>
                                        </div>

                                        <div class="pt-1">
                                            <button onclick="openTicketModal('{{ $ticket->code }}', '{{ addslashes($ev->titre) }}', '{{ $evDate->translatedFormat('d M Y, H:i') }}', '{{ addslashes($ev->lieu) }}', '{{ $ticket->billet->type }}', '{{ number_format($ticket->billet->prix, 0, ',', ' ') }} FCFA', '{{ $ev->photo ? asset('storage/evenement/photo/' . $ev->photo) : '' }}')" class="w-full text-center bg-slate-100 hover:bg-slate-200 text-slate-700 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm border-0 flex items-center justify-center space-x-2">
                                                <i class="fas fa-qrcode"></i>
                                                <span>Afficher le Ticket</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty state history -->
                        <div class="bg-white border border-slate-100 rounded-3xl p-8 text-center space-y-4 shadow-sm">
                            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto text-slate-300 text-2xl">
                                <i class="fas fa-history"></i>
                            </div>
                            <div class="max-w-md mx-auto space-y-1">
                                <h3 class="text-base font-bold text-slate-900">Aucun historique</h3>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    Vous n'avez aucun billet pour des événements passés dans votre historique.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Recommendations Section -->
        <div class="pt-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-slate-900 flex items-center space-x-2">
                    <i class="far fa-compass text-[#d9383a]"></i>
                    <span>Basé sur vos intérêts</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($recommendations as $rec)
                    <div class="bg-white border border-slate-100 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                        <div class="relative overflow-hidden h-40">
                            @if($rec->photo)
                                <img src="{{ asset('storage/evenement/photo/' . $rec->photo) }}" alt="{{ $rec->titre }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-tr from-indigo-500 to-purple-600"></div>
                            @endif
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-lg text-[10px] font-bold text-white bg-black/60 backdrop-blur-sm border border-white/10">
                                {{ $rec->categorie ?? 'Recommandé' }}
                            </span>
                            @if($rec->min_price > 0)
                                <span class="absolute top-3 right-3 px-2.5 py-1 rounded-lg text-[10px] font-extrabold text-white bg-[#d9383a] shadow-sm">
                                    {{ number_format($rec->min_price, 0, ',', ' ') }} F
                                </span>
                            @else
                                <span class="absolute top-3 right-3 px-2.5 py-1 rounded-lg text-[10px] font-extrabold text-white bg-blue-600 shadow-sm">
                                    Gratuit
                                </span>
                            @endif
                        </div>

                        <div class="p-4 flex-grow flex flex-col justify-between space-y-4">
                            <div class="space-y-1">
                                <h4 class="font-bold text-slate-900 text-sm leading-snug line-clamp-1">{{ $rec->titre }}</h4>
                                <p class="text-[11px] text-slate-500 leading-relaxed line-clamp-2">{{ Str::limit($rec->description, 70) }}</p>
                            </div>
                            
                            <div class="border-t border-slate-50 pt-3 flex items-center justify-between text-[11px] text-slate-400 font-semibold">
                                <span class="flex items-center"><i class="far fa-calendar mr-1 text-[#d9383a]"></i> {{ \Carbon\Carbon::parse($rec->date)->format('d M') }}</span>
                                <span class="flex items-center"><i class="fas fa-map-marker-alt mr-1 text-[#d9383a]"></i> {{ Str::limit($rec->lieu, 15) }}</span>
                            </div>

                            <div class="pt-1">
                                <a href="{{ route('p.detail', $rec->id) }}" class="w-full text-center bg-slate-50 hover:bg-[#d9383a]/10 hover:text-[#d9383a] text-slate-700 py-2 rounded-xl text-xs font-bold transition-all border border-slate-100 block text-decoration-none">
                                    S'inscrire
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white border border-slate-100 rounded-3xl p-6 text-center text-slate-400 text-xs">
                        Aucune recommandation disponible pour le moment.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</main>

<!-- Ticket Modal -->
<div id="ticketModal" class="fixed inset-0 z-[1050] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden transform scale-95 transition-transform duration-300 relative flex flex-col">
        
        <!-- Ticket Header (Banner image) -->
        <div class="relative h-44 w-full flex-shrink-0">
            <img id="modalEventPhoto" src="" alt="Event banner" class="w-full h-full object-cover hidden" data-no-cover>
            <div id="modalDefaultGradient" class="w-full h-full bg-gradient-to-br from-indigo-950 via-[#1e1154] to-slate-900"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-white via-slate-950/20 to-transparent"></div>
            
            <!-- Close Button -->
            <button onclick="closeTicketModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-black/45 hover:bg-black/60 text-white flex items-center justify-center border-0 transition-colors focus:outline-none">
                <i class="fas fa-times text-xs"></i>
            </button>

            <!-- Event Category -->
            <div class="absolute bottom-4 left-6 z-10">
                <span id="modalTicketType" class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-[#d9383a] text-white">
                    STANDARD
                </span>
            </div>
        </div>

        <!-- Ticket Body Content -->
        <div class="p-6 space-y-5 flex-grow">
            <!-- Event Title -->
            <h3 id="modalEventTitle" class="text-xl font-extrabold text-slate-900 leading-snug">
                Event Title
            </h3>

            <!-- Details list -->
            <div class="grid grid-cols-2 gap-4 text-xs font-semibold text-slate-600">
                <div class="space-y-1">
                    <span class="text-[10px] text-slate-400 uppercase tracking-wider block mb-0.5">Date & Heure</span>
                    <span id="modalEventDate" class="text-slate-800">12 Oct 2024</span>
                </div>
                <div class="space-y-1">
                    <span class="text-[10px] text-slate-400 uppercase tracking-wider block mb-0.5">Lieu</span>
                    <span id="modalEventLieu" class="text-slate-800">Lomé, Togo</span>
                </div>
                <div class="space-y-1">
                    <span class="text-[10px] text-slate-400 uppercase tracking-wider block mb-0.5">Détenteur</span>
                    <span class="text-slate-800">{{ $user->prenom }} {{ $user->nom }}</span>
                </div>
                <div class="space-y-1">
                    <span class="text-[10px] text-slate-400 uppercase tracking-wider block mb-0.5">Prix payé</span>
                    <span id="modalTicketPrice" class="text-slate-800">5 000 FCFA</span>
                </div>
            </div>

            <!-- Perforation line -->
            <div class="relative flex items-center justify-center my-2">
                <div class="absolute -left-9 w-6 h-6 rounded-full bg-black/60 z-10"></div>
                <div class="absolute -right-9 w-6 h-6 rounded-full bg-black/60 z-10"></div>
                <div class="w-full border-t border-dashed border-slate-200"></div>
            </div>

            <!-- QR Section -->
            <div class="flex flex-col items-center justify-center space-y-3">
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-3 inline-block shadow-inner">
                    <img id="modalQRImage" src="" alt="Ticket QR Code" class="w-36 h-36 object-contain" data-no-cover>
                </div>
                <div class="text-center space-y-1">
                    <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400 block mb-0.5">Code Unique</span>
                    <span id="modalTicketCode" class="text-sm font-black tracking-widest text-slate-900">
                        TGE-XXXXXX
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    function openTicketModal(code, title, date, lieu, type, price, photo) {
        const modal = document.getElementById('ticketModal');
        const modalBody = modal.querySelector('.bg-white');

        // Set static/dynamic info
        document.getElementById('modalTicketCode').textContent = code;
        document.getElementById('modalEventTitle').textContent = title;
        document.getElementById('modalEventDate').textContent = date;
        document.getElementById('modalEventLieu').textContent = lieu;
        document.getElementById('modalTicketType').textContent = type.toUpperCase();
        document.getElementById('modalTicketPrice').textContent = price;

        // QR Code Server integration
        const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(code)}&size=200x200&color=0f172a&bgcolor=ffffff&margin=1&qzone=1`;
        document.getElementById('modalQRImage').src = qrUrl;

        // Image banner handling
        const bannerImg = document.getElementById('modalEventPhoto');
        const defaultGrad = document.getElementById('modalDefaultGradient');
        if (photo) {
            bannerImg.src = photo;
            bannerImg.classList.remove('hidden');
            defaultGrad.classList.add('hidden');
        } else {
            bannerImg.classList.add('hidden');
            defaultGrad.classList.remove('hidden');
        }

        // Show modal with animation
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalBody.classList.remove('scale-95');
            modalBody.classList.add('scale-100');
        }, 50);
    }

    function closeTicketModal() {
        const modal = document.getElementById('ticketModal');
        const modalBody = modal.querySelector('.bg-white');

        modal.classList.add('opacity-0');
        modalBody.classList.remove('scale-100');
        modalBody.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Close on click outside modal content
    document.getElementById('ticketModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeTicketModal();
        }
    });
</script>
@endsection
