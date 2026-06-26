@extends('layouts.base')

@section('title', '| Confirmation de paiement')

@section('content')
<main class="container py-5 text-white flex flex-col items-center justify-center animate__animated animate__fadeIn">
    <!-- Success Banner -->
    <div class="text-center mb-5">
        <div class="w-16 h-16 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 d-flex justify-content-center align-items-center mx-auto mb-3 fs-3 animate-bounce">
            <i class="fas fa-check"></i>
        </div>
        <h1 class="fw-extrabold text-white mb-2 fs-3">Paiement réussi !</h1>
        <p class="text-gray-400 small">Votre commande a été traitée avec succès et votre billet est prêt.</p>
    </div>

    <!-- Apple Wallet Style Pass Card -->
    <div class="w-[360px] md:w-[400px] rounded-3xl overflow-hidden shadow-2xl relative border border-white/10" style="background: #131e3d;">
        <!-- Top notch -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-28 h-6 bg-blue-950/40 rounded-b-2xl border-x border-b border-white/5"></div>
        <div class="absolute -top-12 -right-12 w-40 h-40 bg-blue-500/10 rounded-full filter blur-[40px] pointer-events-none"></div>

        <!-- Header -->
        <div class="p-4 border-b border-dashed border-white/10 d-flex justify-content-between align-items-center mt-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-calendar-check text-indigo-500 me-2 fs-5"></i>
                <span class="fw-extrabold text-white tracking-widest text-xs uppercase">TGEvent Pass</span>
            </div>
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-lg text-emerald-400 bg-emerald-500/10 border border-emerald-500/20">Validé</span>
        </div>

        <!-- Event Image Cover -->
        <div class="h-44 relative overflow-hidden">
            <img src="{{ $event->photo_url }}" alt="{{ $event->titre }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-[#131e3d] via-transparent to-transparent"></div>
        </div>

        <!-- Body Details -->
        <div class="p-4 relative">
            <span class="text-[10px] text-gray-500 uppercase tracking-widest block font-bold mb-1">Événement</span>
            <h3 class="fw-extrabold text-white fs-5 leading-tight mb-4 text-gradient-primary">{{ $event->titre }}</h3>

            <div class="row g-3 mb-4">
                <div class="col-6">
                    <span class="text-[9px] text-gray-500 uppercase font-bold tracking-wider block">Date</span>
                    <span class="text-xs font-semibold text-white">{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</span>
                </div>
                <div class="col-6">
                    <span class="text-[9px] text-gray-500 uppercase font-bold tracking-wider block">Heure</span>
                    <span class="text-xs font-semibold text-white">{{ \Carbon\Carbon::parse($event->start_heure)->format('H:i') }}</span>
                </div>
                <div class="col-12">
                    <span class="text-[9px] text-gray-500 uppercase font-bold tracking-wider block">Lieu</span>
                    <span class="text-xs font-semibold text-white text-truncate block">{{ $event->lieu }}</span>
                </div>
            </div>

            <div class="row g-3 border-t border-white/5 pt-3">
                <div class="col-6">
                    <span class="text-[9px] text-gray-500 uppercase font-bold tracking-wider block">Bénéficiaire</span>
                    <span class="text-xs font-semibold text-white">{{ Auth::user()->nom ?? 'Participant' }}</span>
                </div>
                <div class="col-6">
                    <span class="text-[9px] text-gray-500 uppercase font-bold tracking-wider block">Prix</span>
                    <span class="text-xs font-semibold text-indigo-400">{{ number_format($transaction->amount, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>

        <!-- Coupon Dashed divider -->
        <div class="d-flex align-items-center justify-content-between px-3">
            <div class="w-6 h-6 rounded-full bg-[#0b1329] -ms-6 border-r border-white/10"></div>
            <div class="flex-grow-1 border-t border-dashed border-white/15 mx-2"></div>
            <div class="w-6 h-6 rounded-full bg-[#0b1329] -me-6 border-l border-white/10"></div>
        </div>

        <!-- Ticket QR Barcode Footer -->
        <div class="p-4 text-center bg-black/20">
            <!-- Simulated QR Code with pure SVG -->
            <div class="w-32 h-32 bg-white p-2 rounded-2xl mx-auto mb-3 shadow-lg border border-white/10 flex items-center justify-center">
                <!-- SVG mockup of a nice QR Code -->
                <svg viewBox="0 0 100 100" class="w-full h-full text-black">
                    <!-- Outer frame blocks -->
                    <rect x="0" y="0" width="25" height="25" fill="currentColor"/>
                    <rect x="3" y="3" width="19" height="19" fill="white"/>
                    <rect x="7" y="7" width="11" height="11" fill="currentColor"/>

                    <rect x="75" y="0" width="25" height="25" fill="currentColor"/>
                    <rect x="78" y="3" width="19" height="19" fill="white"/>
                    <rect x="82" y="7" width="11" height="11" fill="currentColor"/>

                    <rect x="0" y="75" width="25" height="25" fill="currentColor"/>
                    <rect x="3" y="78" width="19" height="19" fill="white"/>
                    <rect x="7" y="82" width="11" height="11" fill="currentColor"/>

                    <!-- Random pixel patterns inside -->
                    <rect x="35" y="5" width="10" height="5" fill="currentColor"/>
                    <rect x="55" y="10" width="5" height="10" fill="currentColor"/>
                    <rect x="40" y="20" width="20" height="5" fill="currentColor"/>
                    <rect x="5" y="35" width="5" height="15" fill="currentColor"/>
                    <rect x="25" y="45" width="15" height="5" fill="currentColor"/>
                    <rect x="35" y="35" width="10" height="10" fill="currentColor"/>
                    <rect x="50" y="30" width="5" height="25" fill="currentColor"/>
                    <rect x="65" y="35" width="15" height="5" fill="currentColor"/>
                    <rect x="85" y="45" width="10" height="10" fill="currentColor"/>
                    <rect x="15" y="60" width="15" height="5" fill="currentColor"/>
                    <rect x="30" y="65" width="5" height="15" fill="currentColor"/>
                    <rect x="45" y="60" width="20" height="10" fill="currentColor"/>
                    <rect x="70" y="65" width="10" height="5" fill="currentColor"/>
                    <rect x="35" y="85" width="15" height="5" fill="currentColor"/>
                    <rect x="60" y="80" width="5" height="15" fill="currentColor"/>
                    <rect x="80" y="85" width="15" height="5" fill="currentColor"/>
                </svg>
            </div>
            
            <span class="text-[10px] text-gray-500 uppercase tracking-widest block font-bold mb-1">Code Billet</span>
            <code class="text-sm font-semibold text-white tracking-widest bg-white/5 border border-white/5 px-3 py-1 rounded-lg">{{ $ticket->code }}</code>
        </div>
    </div>

    <!-- Actions -->
    <div class="d-flex gap-3 mt-5">
        <button onclick="window.print()" class="btn btn-outline-light border-white/10 hover:bg-white/5 rounded-xl px-4 py-2.5 text-sm text-gray-300">
            <i class="fas fa-print me-2"></i> Imprimer le billet
        </button>
        <a href="{{ route('index') }}" class="btn text-white font-semibold rounded-xl px-4 py-2.5 hover:scale-105 transition-all duration-300 border-0" style="background: #2563eb; text-decoration: none;">
            Retour à l'accueil <i class="fas fa-home ms-2"></i>
        </a>
    </div>
</main>
@endsection
