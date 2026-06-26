@extends('layouts.base')

@section('title', '| Finaliser l\'achat')

@section('content')
<main class="container py-5 text-white">
    <!-- Breadcrumb -->
    <div class="mb-4">
        <a href="{{ route('p.detail', $evenement->id) }}" class="text-indigo-400 hover:text-indigo-300 text-decoration-none font-semibold d-inline-flex align-items-center transition-colors duration-300">
            <i class="fas fa-arrow-left me-2"></i> Retour aux détails
        </a>
    </div>

    <div class="row g-4">
        <!-- Form Section -->
        <div class="col-lg-7 col-xl-8">
            <div class="glass-card rounded-3xl p-4 p-md-5 border border-white/10 relative overflow-hidden">
                <div class="absolute -top-32 -left-32 w-96 h-96 bg-indigo-500/10 rounded-full filter blur-[100px] pointer-events-none"></div>
                
                <h2 class="fw-bold text-white mb-4 fs-3 border-b border-white/5 pb-2">Informations de Facturation</h2>
                
                <form action="{{ route('p.paiement.process') }}" method="GET" class="needs-validation">
                    <input type="hidden" name="evenement_id" value="{{ $evenement->id }}">
                    
                    <!-- User details (auto-filled if authenticated) -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label text-gray-400 small fw-semibold">Nom complet</label>
                            <input type="text" id="name" name="name" 
                                   value="{{ Auth::user()->nom ?? '' }}" 
                                   placeholder="Jean Dupont" required
                                   class="form-control glass-input rounded-xl py-2.5 px-3">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label text-gray-400 small fw-semibold">Adresse Email</label>
                            <input type="email" id="email" name="email" 
                                   value="{{ Auth::user()->email ?? '' }}" 
                                   placeholder="jean.dupont@example.com" required
                                   class="form-control glass-input rounded-xl py-2.5 px-3">
                        </div>
                        <div class="col-12">
                            <label for="phone" class="form-label text-gray-400 small fw-semibold">Numéro de téléphone</label>
                            <input type="tel" id="phone" name="phone" 
                                   value="{{ Auth::user()->telephone ?? '' }}" 
                                   placeholder="+228 90 00 00 00" required
                                   class="form-control glass-input rounded-xl py-2.5 px-3">
                        </div>
                    </div>

                    <h3 class="fw-bold text-white mb-3 fs-5 border-b border-white/5 pb-2">Mode de paiement</h3>
                    
                    <div class="row g-3 mb-5">
                        <!-- TMoney Option -->
                        <div class="col-sm-6">
                            <label class="d-block cursor-pointer">
                                <input type="radio" name="payment_method" value="tmoney" checked class="d-none peer-input">
                                <div class="p-3 rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 transition-all duration-300 d-flex align-items-center justify-content-between check-card">
                                    <div class="d-flex align-items-center">
                                        <div class="w-10 h-10 rounded-xl bg-yellow-500/20 d-flex justify-content-center align-items-center me-3 text-yellow-500 font-bold fs-5">T</div>
                                        <div>
                                            <span class="fw-bold text-white block small">TMoney</span>
                                            <span class="text-gray-500 text-[10px] block">Togo Telecom</span>
                                        </div>
                                    </div>
                                    <i class="fas fa-check-circle text-indigo-500 check-icon opacity-0 fs-5"></i>
                                </div>
                            </label>
                        </div>
                        
                        <!-- Flooz Option -->
                        <div class="col-sm-6">
                            <label class="d-block cursor-pointer">
                                <input type="radio" name="payment_method" value="flooz" class="d-none peer-input">
                                <div class="p-3 rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 transition-all duration-300 d-flex align-items-center justify-content-between check-card">
                                    <div class="d-flex align-items-center">
                                        <div class="w-10 h-10 rounded-xl bg-red-500/20 d-flex justify-content-center align-items-center me-3 text-red-500 font-bold fs-5">F</div>
                                        <div>
                                            <span class="fw-bold text-white block small">Flooz</span>
                                            <span class="text-gray-500 text-[10px] block">Moov Africa</span>
                                        </div>
                                    </div>
                                    <i class="fas fa-check-circle text-indigo-500 check-icon opacity-0 fs-5"></i>
                                </div>
                            </label>
                        </div>
                        
                        <!-- Credit Card Option -->
                        <div class="col-12">
                            <label class="d-block cursor-pointer">
                                <input type="radio" name="payment_method" value="card" class="d-none peer-input">
                                <div class="p-3 rounded-2xl border border-white/10 bg-white/5 hover:bg-white/10 transition-all duration-300 d-flex align-items-center justify-content-between check-card">
                                    <div class="d-flex align-items-center">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 d-flex justify-content-center align-items-center me-3 text-indigo-500 fs-5">
                                            <i class="fas fa-credit-card"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-white block small">Carte bancaire</span>
                                            <span class="text-gray-500 text-[10px] block">Visa, Mastercard</span>
                                        </div>
                                    </div>
                                    <i class="fas fa-check-circle text-indigo-500 check-icon opacity-0 fs-5"></i>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn text-white font-semibold rounded-xl py-3 hover:scale-105 transition-all duration-300 border-0" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);">
                            Procéder au paiement de {{ number_format($evenement->billets->min('prix') ?? 0, 0, ',', ' ') }} FCFA
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="col-lg-5 col-xl-4">
            <div class="glass-card rounded-3xl p-4 border border-white/10 relative overflow-hidden">
                <div class="absolute -bottom-24 -right-24 w-60 h-60 bg-indigo-500/10 rounded-full filter blur-[60px] pointer-events-none"></div>
                
                <h3 class="fw-bold text-white mb-4 h5 border-b border-white/5 pb-2">Récapitulatif</h3>
                
                <div class="mb-4">
                    <div class="rounded-2xl overflow-hidden mb-3 border border-white/5" style="height: 150px;">
                        <img src="{{ $evenement->photo ? asset('storage/evenement/photo/' . $evenement->photo) : asset('images/default-event.jpg') }}" alt="{{ $evenement->titre }}" class="w-full h-full object-cover">
                    </div>
                    <h4 class="fw-bold text-white fs-6 mb-2">{{ $evenement->titre }}</h4>
                    <p class="text-gray-400 text-xs mb-1 d-flex align-items-center">
                        <i class="fas fa-calendar-day text-indigo-500 me-2"></i>
                        {{ \Carbon\Carbon::parse($evenement->date)->format('d M Y') }}
                    </p>
                    <p class="text-gray-400 text-xs d-flex align-items-center">
                        <i class="fas fa-map-marker-alt text-indigo-500 me-2"></i>
                        {{ $evenement->lieu }}
                    </p>
                </div>
                
                <div class="border-t border-white/5 pt-3 mb-4">
                    <div class="d-flex justify-content-between text-gray-400 small mb-2">
                        <span>Billet Standard (min.)</span>
                        <span>{{ number_format($evenement->billets->min('prix') ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="d-flex justify-content-between text-gray-400 small mb-3">
                        <span>Frais de service</span>
                        <span>0 FCFA</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold text-white border-t border-white/5 pt-3">
                        <span>Total</span>
                        <span class="text-indigo-400">{{ number_format($evenement->billets->min('prix') ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>

                <div class="rounded-2xl bg-indigo-500/10 border border-indigo-500/20 p-3 text-center">
                    <p class="text-xs text-indigo-300 mb-0 leading-relaxed">
                        <i class="fas fa-lock me-1"></i> Transaction sécurisée par cryptage SSL 256 bits. Vos données restent confidentielles.
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    /* Radio input peer-selection styles */
    .peer-input:checked + .check-card {
        border-color: #6366f1 !important;
        background: rgba(99, 102, 241, 0.1) !important;
        box-shadow: 0 0 15px rgba(99, 102, 241, 0.15) !important;
    }
    .peer-input:checked + .check-card .check-icon {
        opacity: 1 !important;
    }
</style>
@endsection
