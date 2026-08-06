@props([
    'step' => 1,
    'evenement' => null
])

@php
    $evenementId = $evenement->id ?? request('evenement_id');
@endphp

<div class="mb-8">
    <!-- Stepper Container -->
    <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200 shadow-sm">
        
        <!-- Header text & event info if available -->
        @if($evenement)
            <div class="mb-5 pb-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider block">Événement en configuration</span>
                        <h2 class="text-base font-extrabold text-slate-900 line-clamp-1">{{ $evenement->titre }}</h2>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-lg w-fit">
                    <i class="fas fa-map-marker-alt text-red-500"></i>
                    <span>{{ $evenement->lieu }}</span>
                    <span class="mx-1">•</span>
                    <i class="far fa-clock text-indigo-500"></i>
                    <span>{{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y') }}</span>
                </div>
            </div>
        @endif

        <!-- 3-Step Process Bar -->
        <div class="relative">
            <!-- Connector Progress Line (Desktop) -->
            <div class="hidden md:block absolute top-1/2 left-0 w-full h-1 bg-slate-200 -translate-y-1/2 rounded-full z-0"></div>
            <div class="hidden md:block absolute top-1/2 left-0 h-1 bg-gradient-to-r from-indigo-500 to-emerald-500 -translate-y-1/2 rounded-full z-0 transition-all duration-500"
                 style="width: {{ $step == 1 ? '15%' : ($step == 2 ? '50%' : '100%') }};"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 relative z-10">
                
                <!-- Step 1: Info Événement -->
                <div class="flex items-center gap-3 p-3 rounded-xl transition-all {{ $step == 1 ? 'bg-indigo-50 border border-indigo-200' : 'bg-white' }}">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-extrabold text-sm shrink-0 transition-all shadow-sm
                        {{ $step > 1 ? 'bg-emerald-500 text-white' : ($step == 1 ? 'bg-indigo-600 text-white ring-4 ring-indigo-100' : 'bg-slate-200 text-slate-500') }}">
                        @if($step > 1)
                            <i class="fas fa-check"></i>
                        @else
                            1
                        @endif
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-bold uppercase tracking-wider {{ $step == 1 ? 'text-indigo-600' : 'text-slate-500' }}">Étape 1</span>
                            @if($step > 1)
                                <span class="text-[10px] bg-emerald-100 text-emerald-700 font-bold px-1.5 py-0.5 rounded-full">OK</span>
                            @endif
                        </div>
                        <p class="text-sm font-bold text-slate-900 truncate">Détails Événement</p>
                    </div>
                </div>

                <!-- Step 2: Billets -->
                @php
                    $step2Url = $evenementId ? route('organisateur.billet-form', ['evenement_id' => $evenementId, 'wizard' => 1]) : '#';
                @endphp
                <a href="{{ $step > 1 && $evenementId ? $step2Url : 'javascript:void(0)' }}" 
                   class="flex items-center gap-3 p-3 rounded-xl transition-all {{ $step == 2 ? 'bg-indigo-50 border border-indigo-200' : 'bg-white' }} {{ $step > 1 && $evenementId ? 'hover:bg-slate-50 cursor-pointer' : '' }}">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-extrabold text-sm shrink-0 transition-all shadow-sm
                        {{ $step > 2 ? 'bg-emerald-500 text-white' : ($step == 2 ? 'bg-indigo-600 text-white ring-4 ring-indigo-100' : 'bg-slate-200 text-slate-500') }}">
                        @if($step > 2)
                            <i class="fas fa-check"></i>
                        @else
                            2
                        @endif
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-bold uppercase tracking-wider {{ $step == 2 ? 'text-indigo-600' : 'text-slate-500' }}">Étape 2</span>
                            @if($step > 2)
                                <span class="text-[10px] bg-emerald-100 text-emerald-700 font-bold px-1.5 py-0.5 rounded-full">OK</span>
                            @endif
                        </div>
                        <p class="text-sm font-bold text-slate-900 truncate">Billets & Tarifs</p>
                    </div>
                </a>

                <!-- Step 3: Sponsors -->
                @php
                    $step3Url = $evenementId ? route('organisateur.sponsor-form', ['evenement_id' => $evenementId, 'wizard' => 1]) : '#';
                @endphp
                <a href="{{ $step >= 2 && $evenementId ? $step3Url : 'javascript:void(0)' }}" 
                   class="flex items-center gap-3 p-3 rounded-xl transition-all {{ $step == 3 ? 'bg-indigo-50 border border-indigo-200' : 'bg-white' }} {{ $step >= 2 && $evenementId ? 'hover:bg-slate-50 cursor-pointer' : '' }}">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-extrabold text-sm shrink-0 transition-all shadow-sm
                        {{ $step == 3 ? 'bg-indigo-600 text-white ring-4 ring-indigo-100' : 'bg-slate-200 text-slate-500' }}">
                        3
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-bold uppercase tracking-wider {{ $step == 3 ? 'text-indigo-600' : 'text-slate-500' }}">Étape 3</span>
                        </div>
                        <p class="text-sm font-bold text-slate-900 truncate">Sponsors & Partenaires</p>
                    </div>
                </a>

            </div>
        </div>
    </div>
</div>
