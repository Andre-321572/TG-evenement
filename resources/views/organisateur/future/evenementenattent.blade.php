@extends('layouts.Obase')
@section('title', "| Événements futurs")
@section('content')

<div class="container mx-auto px-6 py-8 max-w-7xl">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-white">Événements Futurs</h1>
        <p class="text-gray-400 mt-1">Consultez vos événements planifiés pour le futur.</p>
        <div class="mt-4">
            <span class="bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 px-4 py-1.5 rounded-full text-xs font-bold">
                {{ $countfuture }} événement(s) futur(s)
            </span>
        </div>
    </div>

    @if ($countfuture > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($evenementdateavenir as $evavenir)
                <div class="glass-card group rounded-2xl overflow-hidden hover:translate-y-[-4px] transition-all duration-300 flex flex-col justify-between">
                    
                    <!-- Thumbnail image -->
                    <div class="relative h-48 bg-black/40 overflow-hidden">
                        @if($evavenir->photo)
                            <img src="{{ asset('storage/evenement/photo/' . $evavenir->photo) }}"
                                 alt="{{ $evavenir->titre }}"
                                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="absolute inset-0 bg-white/[0.02] flex items-center justify-center">
                                <i data-feather="image" class="w-12 h-12 text-gray-600"></i>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0a0b10] via-transparent to-transparent"></div>
                        <div class="absolute top-4 left-4">
                            <span class="bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 px-3 py-1 rounded-full text-xs font-bold backdrop-blur-md">
                                Planifié
                            </span>
                        </div>
                    </div>

                    <!-- Details content -->
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-indigo-400 bg-indigo-500/5 px-2.5 py-1 rounded-lg border border-indigo-500/10">
                                {{ $evavenir->categorie }}
                            </span>
                            <h3 class="text-lg font-bold text-white mt-3 truncate">{{ $evavenir->titre }}</h3>
                            
                            <div class="mt-4 space-y-2">
                                <div class="flex items-center text-sm text-gray-400">
                                    <i data-feather="calendar" class="w-4 h-4 mr-2 text-indigo-400"></i>
                                    <span>{{ \Carbon\Carbon::parse($evavenir->date)->format('d M Y') }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-400">
                                    <i data-feather="clock" class="w-4 h-4 mr-2 text-indigo-400"></i>
                                    <span>{{ \Carbon\Carbon::parse($evavenir->start_heure)->format('H:i') }} - {{ \Carbon\Carbon::parse($evavenir->end_heure)->format('H:i') }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-400">
                                    <i data-feather="map-pin" class="w-4 h-4 mr-2 text-indigo-400"></i>
                                    <span class="truncate">{{ $evavenir->lieu }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-white/5 flex items-center justify-between">
                            <a href="{{ route('organisateur.detail', ['id' => $evavenir->id]) }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 flex items-center space-x-1">
                                <span>Détails de l'événement</span>
                                <i data-feather="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16 glass-card rounded-2xl p-8 max-w-lg mx-auto">
            <div class="w-20 h-20 rounded-full bg-indigo-500/5 border border-indigo-500/10 flex items-center justify-center mx-auto mb-6 text-indigo-400">
                <i data-feather="calendar" class="w-10 h-10"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">Aucun événement futur</h3>
            <p class="text-gray-400">
                Vous n'avez pas encore planifié d'événements pour le futur.
            </p>
        </div>
    @endif
</div>
@endsection
