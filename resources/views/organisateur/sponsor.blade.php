@extends('layouts.Obase')
@section('title', '| Sponsor')
@section('content')

<div class="container mx-auto px-6 py-8 max-w-5xl">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl md:text-4xl font-extrabold bg-gradient-to-r from-white via-gray-100 to-gray-300 bg-clip-text text-transparent mb-2">
            Ajouter un Sponsor
        </h1>
        <p class="text-gray-400 text-sm">Associez un nouveau sponsor à l'un de vos événements</p>
    </div>

    <!-- Notification -->
    @if (session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 mb-6 rounded-xl flex items-center justify-between shadow-lg" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2 text-emerald-400"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="text-emerald-400 hover:text-white transition-colors" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    <!-- Card layout -->
    <div class="glass-card rounded-2xl border border-white/5 overflow-hidden">
        <div class="grid lg:grid-cols-12 gap-0">
            <!-- Left Info Panel -->
            <div class="hidden lg:block lg:col-span-5 relative min-h-[300px]">
                <img src="{{ asset('asset/image/hero-bg.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-60 filter saturate-50" alt="Sponsors">
                <div class="absolute inset-0 bg-gradient-to-t from-[#0a0b10] via-accentIndigo/10 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-6 text-white z-10">
                    <h3 class="text-lg font-bold mb-1">Mettez en avant vos sponsors</h3>
                    <p class="text-xs text-gray-300">Intégrez les logos de vos sponsors pour leur offrir une visibilité optimale sur la page de votre événement.</p>
                </div>
            </div>

            <!-- Form Panel -->
            <div class="lg:col-span-7 p-6 md:p-8">
                <form action="{{ route('organisateur.valide-sponsor') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div class="space-y-4">
                        <!-- Select Event -->
                        <div>
                            <label for="evenement" class="text-sm font-semibold text-gray-300 mb-1.5 block">Événement sponsorisé <span class="text-red-400">*</span></label>
                            <select class="w-full bg-white/5 border border-white/10 text-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-colors" id="evenement" name="evenement_id" required>
                                <option value="" disabled selected class="bg-[#0f111a] text-gray-400">Choisir un événement</option>
                                @foreach ($evenementid as $event)
                                    <option class="bg-[#0f111a]" value="{{ $event->id }}">{{ $event->titre }}</option>
                                @endforeach
                            </select>
                            @error('evenement_id')
                                <div class="text-red-400 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Sponsor Name -->
                        <div>
                            <label for="nom" class="text-sm font-semibold text-gray-300 mb-1.5 block">Nom du sponsor <span class="text-red-400">*</span></label>
                            <input type="text" class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-colors" id="nom" name="nom" value="{{ old('nom') }}" placeholder="Ex: Acme Corp" required>
                            @error('nom')
                                <div class="text-red-400 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Logo File -->
                            <div>
                                <label for="logo" class="text-sm font-semibold text-gray-300 mb-1.5 block">Photo / Logo du sponsor <span class="text-red-400">*</span></label>
                                <input type="file" name="logo" class="w-full bg-white/5 border border-white/10 text-gray-300 focus:outline-none rounded-xl px-4 py-2 transition-all" id="logo" required>
                                <p class="text-xxs text-gray-500 mt-1.5">Formats acceptés: PNG, JPG, JPEG, SVG</p>
                                @error('logo')
                                    <div class="text-red-400 text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Website URL -->
                            <div>
                                <label for="lien_web" class="text-sm font-semibold text-gray-300 mb-1.5 block">Lien internet</label>
                                <input type="url" class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-colors" id="lien_web" name="lien_web" value="{{ old('lien_web') }}" placeholder="https://exemplesponsor.com">
                                @error('lien_web')
                                    <div class="text-red-400 text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-accentIndigo to-accentViolet text-white font-bold rounded-xl shadow-lg hover:shadow-indigo-500/25 transform hover:-translate-y-0.5 transition-all flex items-center justify-center">
                            <i data-feather="save" class="w-4 h-4 mr-2"></i>
                            <span>Enregistrer le sponsor</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
