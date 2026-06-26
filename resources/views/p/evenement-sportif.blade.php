@extends('layouts.base')

@section('title', '| Événements Sportifs')

@section('content')
<main class="container py-5 text-white animate__animated animate__fadeIn">
    <!-- Header Banner -->
    <section class="glass-card rounded-3xl p-6 md:p-8 lg:p-10 mb-12 relative overflow-hidden text-center border border-white/10">
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-indigo-500/10 rounded-full filter blur-[80px] pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-emerald-500/10 rounded-full filter blur-[80px] pointer-events-none"></div>
        
        <div class="relative z-10 max-w-2xl mx-auto py-4">
            <span class="w-16 h-16 rounded-full bg-gradient-to-tr from-emerald-500 to-indigo-500 flex items-center justify-center text-white fs-3 mx-auto mb-4 shadow-lg shadow-indigo-500/20">
                <i class="fas fa-running"></i>
            </span>
            <h1 class="fw-extrabold text-white mb-2 fs-2 leading-tight">Sports & Fitness</h1>
            <p class="text-indigo-200 fs-5 mb-4 font-medium">Dépassez vos limites, vivez le sport</p>
            <p class="text-gray-400 small leading-relaxed">Trouvez des marathons, tournois de football, cours de yoga collectifs et compétitions sportives locales pour rester en forme et encourager vos athlètes préférés.</p>
            <a href="#events-list" class="btn w-10 h-10 rounded-full bg-white/5 border border-white/10 d-inline-flex justify-content-center align-items-center text-gray-400 hover:text-white hover:bg-indigo-600 hover:border-indigo-600 mt-4 transition-all duration-300">
                <i class="fas fa-arrow-down"></i>
            </a>
        </div>
    </section>

    <!-- Events Grid -->
    <section id="events-list" class="mb-12">
        <div class="row g-4">
            @forelse($events as $event)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="glass-card rounded-2xl overflow-hidden hover-up h-100 flex flex-col justify-between">
                        <div class="relative overflow-hidden group" style="height: 180px;">
                            <img src="{{ $event->photo ? asset('storage/evenement/photo/' . $event->photo) : asset('images/default-event.jpg') }}" alt="{{ $event->titre }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <span class="absolute top-2 right-2 px-2.5 py-1 rounded-xl text-[10px] font-semibold text-white bg-black/60 backdrop-blur-md border border-white/10">
                                {{ $event->categorie ?? 'Sport' }}
                            </span>
                            @if(\Carbon\Carbon::parse($event->date)->isFuture())
                                <span class="absolute top-2 left-2 px-2.5 py-1 rounded-xl text-[10px] font-semibold text-white bg-emerald-500/80 border border-emerald-400/20 backdrop-blur-md">
                                    À venir
                                </span>
                            @else
                                <span class="absolute top-2 left-2 px-2.5 py-1 rounded-xl text-[10px] font-semibold text-gray-300 bg-white/10 border border-white/5 backdrop-blur-md">
                                    Passé
                                </span>
                            @endif
                        </div>

                        <div class="p-3 flex-grow-1 flex flex-col justify-between">
                            <div>
                                <h5 class="fw-bold text-white mb-2 line-clamp-1 h6">{{ $event->titre }}</h5>
                                <p class="text-gray-400 text-xs mb-3 line-clamp-2 leading-relaxed">{{ Str::limit($event->description, 80) }}</p>
                            </div>
                            
                            <div class="border-t border-white/5 pt-2">
                                <div class="d-flex align-items-center text-gray-400 text-xs mb-1.5">
                                    <i class="fas fa-calendar-day me-2 text-indigo-500"></i>
                                    <span>{{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</span>
                                </div>
                                <div class="d-flex align-items-center text-gray-400 text-xs mb-1.5">
                                    <i class="fas fa-clock me-2 text-indigo-500"></i>
                                    <span>{{ \Carbon\Carbon::parse($event->start_heure)->format('H:i') }}</span>
                                </div>
                                <div class="d-flex align-items-center text-gray-400 text-xs mb-3">
                                    <i class="fas fa-map-marker-alt me-2 text-indigo-500"></i>
                                    <span class="text-truncate">{{ $event->lieu }}</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-2 border-t border-white/5">
                                    <span class="fw-bold text-indigo-400 text-sm">
                                        @if($event->billets && $event->billets->count() > 0 && $event->billets->min('prix') > 0)
                                            {{ number_format($event->billets->min('prix'), 0, ',', ' ') }} FCFA
                                        @else
                                            Gratuit
                                        @endif
                                    </span>
                                    <a href="{{ route('p.detail', $event->id) }}" class="btn btn-sm px-3 rounded-lg text-white font-medium hover:scale-105 transition-all duration-300 border-0" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);">
                                        Plus d'info
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 glass-card rounded-3xl">
                    <i class="fas fa-calendar-times fa-3x text-gray-600 mb-3"></i>
                    <h4 class="text-gray-400">Aucun événement sportif disponible</h4>
                    <p class="text-gray-500 small">Revenez plus tard pour voir les nouveaux événements sportifs !</p>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($events->count() > 0)
            <div class="d-flex justify-content-center mt-5">
                {{ $events->links() }}
            </div>
        @endif
    </section>
</main>
@endsection
