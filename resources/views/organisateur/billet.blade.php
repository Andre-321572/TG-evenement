@extends('layouts.Obase')
@section('title', '| Création de billets')
@section('content')

<div class="container mx-auto px-6 py-8 max-w-5xl">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl md:text-4xl font-extrabold bg-gradient-to-r from-white via-gray-100 to-gray-300 bg-clip-text text-transparent mb-2">
            Création de Billets
        </h1>
        <p class="text-gray-400 text-sm">Créez des billets pour vos événements et gérez vos ventes facilement</p>
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
                <img src="{{ asset('asset/image/ticket.jpg') }}" class="absolute inset-0 w-full h-full object-cover opacity-60 filter saturate-50" alt="Billets">
                <div class="absolute inset-0 bg-gradient-to-t from-[#0a0b10] via-accentIndigo/10 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-6 text-white z-10">
                    <h3 class="text-lg font-bold mb-1">Gérez votre billetterie</h3>
                    <p class="text-xs text-gray-300">Définissez différents types de billets (VIP, Standard, Early Bird) pour attirer un plus large public.</p>
                </div>
            </div>

            <!-- Form Panel -->
            <div class="lg:col-span-7 p-6 md:p-8">
                <form action="{{ route('organisateur.valide-billet') }}" method="POST" enctype="multipart/form-data" class="needs-validation space-y-5" novalidate>
                    @csrf

                    <div class="space-y-4">
                        <!-- Select Event -->
                        <div>
                            <label for="evenement" class="text-sm font-semibold text-gray-300 mb-1.5 block">Événement rattaché <span class="text-red-400">*</span></label>
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

                        <!-- Ticket Type -->
                        <div>
                            <label for="type-billet" class="text-sm font-semibold text-gray-300 mb-1.5 block">Nom du type de billet <span class="text-red-400">*</span></label>
                            <input type="text" class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-colors" id="type-billet" name="type" value="{{ old('type') }}" placeholder="Ex: VIP, Standard, Early Bird" required>
                            @error('type')
                                <div class="text-red-400 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Quantity -->
                            <div>
                                <label for="quantite" class="text-sm font-semibold text-gray-300 mb-1.5 block">Quantité totale disponible <span class="text-red-400">*</span></label>
                                <input type="number" class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-colors" id="quantite" name="quantite" min="1" value="{{ old('quantite') ?? 1 }}" placeholder="Ex: 100" required>
                                @error('quantite')
                                    <div class="text-red-400 text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="prix" class="text-sm font-semibold text-gray-300 mb-1.5 block">Prix (FCFA) <span class="text-red-400">*</span></label>
                                <input type="text" class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accentIndigo rounded-xl px-4 py-2.5 transition-colors" id="prix" name="prix" value="{{ old('prix') }}" placeholder="Ex: 5000" required>
                                @error('prix')
                                    <div class="text-red-400 text-xs mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-accentIndigo to-accentViolet text-white font-bold rounded-xl shadow-lg hover:shadow-indigo-500/25 transform hover:-translate-y-0.5 transition-all flex items-center justify-center">
                            <i data-feather="save" class="w-4 h-4 mr-2"></i>
                            <span>Créer le billet</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
