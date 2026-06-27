@extends('layouts.Obase')
@section('title', '| Ajouter un Sponsor')
@section('content')

<div class="container mx-auto px-6 py-8 max-w-4xl">

    {{-- Breadcrumb --}}
    <nav class="flex items-center space-x-2 text-sm mb-6">
        <a href="{{ route('organisateur.dashboard') }}" class="text-gray-400 hover:text-indigo-500 transition-colors">Dashboard</a>
        <i data-feather="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
        <span class="font-semibold text-indigo-500">Nouveau sponsor</span>
    </nav>

    {{-- Notification --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 mb-6 rounded-2xl flex items-center justify-between" role="alert">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                <i data-feather="check" class="w-4 h-4 text-emerald-600"></i>
            </div>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600 transition-colors">
            <i data-feather="x" class="w-4 h-4"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 mb-6 rounded-2xl flex items-center justify-between" role="alert">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <i data-feather="alert-circle" class="w-4 h-4 text-red-600"></i>
            </div>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 transition-colors">
            <i data-feather="x" class="w-4 h-4"></i>
        </button>
    </div>
    @endif

    {{-- En-tête de la page --}}
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-gray-900">Associer un sponsor</h1>
        <p class="text-gray-500 mt-1 text-sm">Ajoutez un partenaire commercial à l'un de vos événements.</p>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">

        {{-- Panneau gauche : aperçu du logo --}}
        <div class="lg:col-span-1 space-y-5">

            {{-- Carte aperçu logo --}}
            <div class="glass-card rounded-2xl p-5 border border-blue-100/60">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Aperçu du logo</p>
                <div id="logoPreviewWrap" class="w-full aspect-video rounded-xl border-2 border-dashed border-blue-200 bg-blue-50/50 flex flex-col items-center justify-center cursor-pointer transition-all duration-200 hover:border-indigo-400 hover:bg-indigo-50/40"
                     onclick="document.getElementById('logo').click()">
                    <img id="logoPreviewImg" src="" alt="" class="w-full h-full object-contain rounded-xl hidden p-3">
                    <div id="logoPlaceholder" class="flex flex-col items-center text-center px-4">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center mb-2">
                            <i data-feather="image" class="w-5 h-5 text-indigo-400"></i>
                        </div>
                        <p class="text-xs font-medium text-gray-500">Cliquez pour sélectionner<br>le logo du sponsor</p>
                        <p class="text-xxs text-gray-400 mt-1">PNG, JPG, SVG</p>
                    </div>
                </div>
            </div>

            {{-- Conseils --}}
            <div class="glass-card rounded-2xl p-5 border border-blue-100/60">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Bonnes pratiques</p>
                <ul class="space-y-3">
                    <li class="flex items-start space-x-2.5">
                        <div class="w-5 h-5 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i data-feather="check" class="w-3 h-3 text-indigo-500"></i>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed">Utilisez un logo à fond transparent (PNG/SVG)</p>
                    </li>
                    <li class="flex items-start space-x-2.5">
                        <div class="w-5 h-5 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i data-feather="check" class="w-3 h-3 text-indigo-500"></i>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed">Résolution recommandée : 400 × 200 px minimum</p>
                    </li>
                    <li class="flex items-start space-x-2.5">
                        <div class="w-5 h-5 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i data-feather="check" class="w-3 h-3 text-indigo-500"></i>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed">Le logo apparaît sur la page publique de l'événement</p>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Formulaire principal --}}
        <div class="lg:col-span-2">
            <div class="glass-card rounded-2xl border border-blue-100/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-blue-100/60 bg-gradient-to-r from-indigo-50/60 to-blue-50/40">
                    <h2 class="font-bold text-gray-800 text-base">Informations du sponsor</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Les champs marqués <span class="text-red-500">*</span> sont obligatoires</p>
                </div>

                <form action="{{ route('organisateur.valide-sponsor') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                    @csrf

                    {{-- Événement associé --}}
                    <div>
                        <label for="evenement" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Événement sponsorisé <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="evenement" name="evenement_id" required
                                    class="w-full appearance-none rounded-xl border @error('evenement_id') border-red-400 bg-red-50 @else border-gray-200 bg-white @enderror px-4 py-2.5 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all">
                                <option value="" disabled selected>— Sélectionner un événement —</option>
                                @foreach($evenementid as $event)
                                    <option value="{{ $event->id }}" {{ old('evenement_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->titre }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                <i data-feather="chevron-down" class="w-4 h-4 text-gray-400"></i>
                            </div>
                        </div>
                        @error('evenement_id')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center"><i data-feather="alert-circle" class="w-3 h-3 mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nom du sponsor --}}
                    <div>
                        <label for="nom" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nom du sponsor <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required
                               placeholder="Ex: Acme Corporation, Orange Togo..."
                               class="w-full rounded-xl border @error('nom') border-red-400 bg-red-50 @else border-gray-200 bg-white @enderror px-4 py-2.5 text-sm text-gray-700 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all">
                        @error('nom')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center"><i data-feather="alert-circle" class="w-3 h-3 mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Logo (hidden input réel) --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Logo / Identité visuelle <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-3">
                            <label for="logo"
                                   class="flex items-center space-x-2 px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm text-gray-600 cursor-pointer hover:border-indigo-400 hover:text-indigo-600 transition-all shadow-sm">
                                <i data-feather="upload" class="w-4 h-4"></i>
                                <span id="logoLabel">Choisir un fichier</span>
                            </label>
                            <input type="file" id="logo" name="logo" accept="image/*" required class="hidden"
                                   onchange="previewLogo(event)">
                            <p class="text-xs text-gray-400">PNG, JPG, JPEG, SVG — max 2 Mo</p>
                        </div>
                        @error('logo')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center"><i data-feather="alert-circle" class="w-3 h-3 mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Site web --}}
                    <div>
                        <label for="lien_web" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Site web du sponsor
                            <span class="text-gray-400 font-normal text-xs">(facultatif)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2">
                                <i data-feather="link" class="w-4 h-4 text-gray-400"></i>
                            </span>
                            <input type="url" id="lien_web" name="lien_web" value="{{ old('lien_web') }}"
                                   placeholder="https://www.monsponsor.com"
                                   class="w-full rounded-xl border @error('lien_web') border-red-400 bg-red-50 @else border-gray-200 bg-white @enderror pl-10 pr-4 py-2.5 text-sm text-gray-700 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition-all">
                        </div>
                        @error('lien_web')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center"><i data-feather="alert-circle" class="w-3 h-3 mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Séparateur + Actions --}}
                    <div class="pt-2 border-t border-gray-100 flex items-center justify-between gap-3">
                        <a href="{{ route('organisateur.dashboard') }}"
                           class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                            Annuler
                        </a>
                        <button type="submit"
                                class="flex items-center space-x-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-colors duration-200 text-sm shadow-md shadow-indigo-200">
                            <i data-feather="save" class="w-4 h-4"></i>
                            <span>Enregistrer le sponsor</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewLogo(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Mettre à jour le label
    document.getElementById('logoLabel').textContent = file.name.length > 22 ? file.name.substring(0, 20) + '…' : file.name;

    // Afficher l'aperçu
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById('logoPreviewImg');
        const placeholder = document.getElementById('logoPlaceholder');
        img.src = e.target.result;
        img.classList.remove('hidden');
        placeholder.classList.add('hidden');
        document.getElementById('logoPreviewWrap').classList.remove('border-dashed');
    };
    reader.readAsDataURL(file);
}
</script>

@endsection
