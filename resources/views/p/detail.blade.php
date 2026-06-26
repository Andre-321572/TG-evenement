@extends('layouts.base')

@section('title', '| ' . $detail_evenement->titre)

@section('content')
<main class="container py-5 text-white">
    <!-- Breadcrumb & Back button -->
    <div class="mb-4">
        <a href="{{ route('p.evenement') }}" class="text-indigo-400 hover:text-indigo-300 text-decoration-none font-semibold d-inline-flex align-items-center transition-colors duration-300">
            <i class="fas fa-arrow-left me-2"></i> Retour aux événements
        </a>
    </div>

    <!-- Event Top Banner -->
    <section class="glass-card rounded-3xl overflow-hidden mb-5 border border-white/10 relative" style="background: rgba(13, 16, 27, 0.65);">
        
        <div class="row g-0 align-items-stretch">
            <!-- Event Cover Image -->
            <div class="col-lg-6 relative" style="min-height: 350px;">
                <img src="{{ $detail_evenement->photo ? asset('storage/evenement/photo/' . $detail_evenement->photo) : asset('images/default-event.jpg') }}" alt="{{ $detail_evenement->titre }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-[#090a12] via-transparent to-transparent d-lg-none"></div>
            </div>
            
            <!-- Quick Summary -->
            <div class="col-lg-6 p-4 p-md-5 d-flex flex-col justify-between">
                <div>
                    <span class="px-3.5 py-1.5 rounded-xl text-xs font-semibold text-indigo-300 bg-indigo-500/10 border border-indigo-500/20 mb-3 d-inline-block">
                        {{ ucfirst($detail_evenement->categorie) }}
                    </span>
                    <h1 class="fw-extrabold text-white mb-4 fs-2 leading-tight">{{ $detail_evenement->titre }}</h1>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center text-gray-300">
                                <span class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 d-flex justify-content-center align-items-center me-3 text-indigo-400">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <div>
                                    <small class="text-gray-500 block text-[10px] uppercase font-bold tracking-wider">Date</small>
                                    <span class="small font-medium">{{ \Carbon\Carbon::parse($detail_evenement->date)->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center text-gray-300">
                                <span class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 d-flex justify-content-center align-items-center me-3 text-indigo-400">
                                    <i class="fas fa-clock"></i>
                                </span>
                                <div>
                                    <small class="text-gray-500 block text-[10px] uppercase font-bold tracking-wider">Heure</small>
                                    <span class="small font-medium">{{ \Carbon\Carbon::parse($detail_evenement->start_heure)->format('H:i') }} @if($detail_evenement->end_heure) - {{ \Carbon\Carbon::parse($detail_evenement->end_heure)->format('H:i') }}@endif</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center text-gray-300">
                                <span class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 d-flex justify-content-center align-items-center me-3 text-indigo-400">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                <div>
                                    <small class="text-gray-500 block text-[10px] uppercase font-bold tracking-wider">Lieu</small>
                                    <span class="small font-medium">{{ $detail_evenement->lieu }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($detail_evenement->lien_google_map)
                    <div>
                        <a href="{{ $detail_evenement->lien_google_map }}" target="_blank" class="btn btn-outline-light border-white/10 hover:bg-white/5 rounded-xl px-4 py-2 text-sm text-gray-300 text-decoration-none d-inline-flex align-items-center">
                            <i class="fas fa-map me-2 text-indigo-500"></i> Voir sur Google Maps
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Left Column: Description, Organizer & Sponsors -->
        <div class="col-lg-8">
            <!-- Description -->
            <section class="glass-card rounded-3xl p-4 p-md-5 mb-4">
                <h3 class="fw-bold text-white mb-4 h5 border-b border-white/5 pb-2">Description de l'événement</h3>
                <div class="text-gray-300 leading-relaxed fs-6" style="white-space: pre-line;">
                    {{ $detail_evenement->description }}
                </div>
            </section>

            <!-- Video (if present) -->
            @if($detail_evenement->video_url)
            <section class="glass-card rounded-3xl p-4 p-md-5 mb-4">
                <h3 class="fw-bold text-white mb-4 h5 border-b border-white/5 pb-2">Vidéo de présentation</h3>
                <div class="ratio ratio-16x9 rounded-2xl overflow-hidden border border-white/10 shadow-2xl">
                    <video src="{{ $detail_evenement->video_url }}" controls class="w-full"></video>
                </div>
            </section>
            @endif

            <!-- Organizer Block -->
            <section class="glass-card rounded-3xl p-4 p-md-5 mb-4">
                <h3 class="fw-bold text-white mb-4 h5 border-b border-white/5 pb-2">Organisateur</h3>
                <div class="d-flex align-items-center mb-4">
                    <div class="w-16 h-16 rounded-full bg-indigo-600 flex items-center justify-center text-white font-extrabold fs-3 shadow-inner me-4">
                        {{ strtoupper(substr($detail_evenement->nom_proprietaire ?? $detail_evenement->user->nom ?? 'O', 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="fw-bold text-white mb-1 fs-5">{{ $detail_evenement->nom_proprietaire ?? $detail_evenement->user->nom ?? 'Organisateur Anonyme' }}</h4>
                        <p class="text-gray-400 small mb-0">Membre TGEvent</p>
                    </div>
                </div>
                
                <div class="row g-3 mb-4 text-gray-300">
                    @if($detail_evenement->telephone)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-phone me-3 text-indigo-400"></i>
                                <span>{{ $detail_evenement->telephone }}</span>
                            </div>
                        </div>
                    @endif
                    @if($detail_evenement->email)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-envelope me-3 text-indigo-400"></i>
                                <span>{{ $detail_evenement->email }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Socials -->
                <div class="d-flex gap-3">
                    @if($detail_evenement->facebook)
                        <a href="{{ $detail_evenement->facebook }}" target="_blank" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 d-flex justify-content-center align-items-center text-gray-400 hover:text-white hover:bg-blue-600 hover:border-blue-600 transition-all duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif
                    @if($detail_evenement->twiter)
                        <a href="{{ $detail_evenement->twiter }}" target="_blank" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 d-flex justify-content-center align-items-center text-gray-400 hover:text-white hover:bg-indigo-600 hover:border-indigo-600 transition-all duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif
                    @if($detail_evenement->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $detail_evenement->whatsapp) }}" target="_blank" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 d-flex justify-content-center align-items-center text-gray-400 hover:text-white hover:bg-emerald-600 hover:border-emerald-600 transition-all duration-300">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    @endif
                </div>
            </section>

            <!-- Sponsors Block -->
            @if($detail_evenement->sponsors && $detail_evenement->sponsors->count() > 0)
            <section class="glass-card rounded-3xl p-4 p-md-5">
                <h3 class="fw-bold text-white mb-4 h5 border-b border-white/5 pb-2">Sponsors Officiels</h3>
                <div class="row g-4 align-items-center">
                    @foreach($detail_evenement->sponsors as $sponsor)
                        <div class="col-6 col-sm-4 col-md-3">
                            <div class="glass-card rounded-2xl p-3 text-center border border-white/5 shadow-inner hover:scale-105 transition-transform duration-300">
                                @if($sponsor->photo)
                                    <img src="{{ asset('storage/sponsor/photo/' . $sponsor->photo) }}" alt="{{ $sponsor->nom }}" class="img-fluid rounded max-h-12 mx-auto mb-2 object-contain">
                                @endif
                                <span class="text-xs text-gray-400 block font-medium">{{ $sponsor->nom }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
            @endif
        </div>

        <!-- Right Column: Tickets Checkout Panel -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 96px; z-index: 10;">
                <div class="glass-card rounded-3xl p-4 border border-white/10 relative overflow-hidden">
                    
                    <h3 class="fw-bold text-white mb-4 h5 border-b border-white/5 pb-2">
                        <i class="fas fa-ticket-alt me-2 text-indigo-500"></i>Billetterie
                    </h3>
                    
                    @if($detail_evenement->billets && $detail_evenement->billets->count() > 0)
                        <div class="d-flex flex-col gap-3 mb-4">
                            @foreach($detail_evenement->billets as $billet)
                                <div class="p-3 rounded-2xl bg-white/5 border border-white/5 relative">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <span class="fw-bold text-white block">{{ $billet->type }}</span>
                                            @if($billet->description)
                                                <small class="text-gray-400 text-xs block mt-0.5">{{ $billet->description }}</small>
                                            @endif
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-extrabold text-indigo-400 block">{{ number_format($billet->prix, 0, ',', ' ') }} <span class="text-xs">FCFA</span></span>
                                            @if($billet->quantite_disponible == 0)
                                                <span class="badge bg-red-500/20 text-red-400 text-[10px] rounded-lg mt-1 border border-red-500/20">Épuisé</span>
                                            @elseif($billet->quantite_disponible <= 5)
                                                <span class="badge bg-amber-500/20 text-amber-400 text-[10px] rounded-lg mt-1 border border-amber-500/20">Plus que {{ $billet->quantite_disponible }} !</span>
                                            @else
                                                <span class="badge bg-emerald-500/20 text-emerald-400 text-[10px] rounded-lg mt-1 border border-emerald-500/20">Disponible</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="d-grid">
                            @if($detail_evenement->billets->sum('quantite_disponible') > 0)
                                <a href="{{ route('p.paiement.form', $detail_evenement->id) }}" class="btn text-white font-semibold rounded-xl py-3 hover:scale-105 transition-all duration-300 border-0" style="background: #6366f1; text-align: center;">
                                    Réserver mon billet <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            @else
                                <button class="btn btn-secondary rounded-xl py-3 cursor-not-allowed" disabled>
                                    Complet / Épuisé
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-info-circle fa-2x mb-2 text-gray-600"></i>
                            <p class="small mb-0">Aucun billet n'a été créé pour cet événement.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>
@endsection