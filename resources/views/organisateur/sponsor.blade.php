@extends('layouts.Obase')
@section('title', '| Ajouter un Sponsor')
@section('content')

@php
    $currentEvId = $selectedEvenementId ?? ($evenementActuel->id ?? null);
@endphp

<div class="container mx-auto px-4 sm:px-6 py-8 max-w-5xl text-slate-800">

    <!-- Wizard Stepper -->
    @include('organisateur.include.wizard-stepper', ['step' => 3, 'evenement' => $evenementActuel ?? null])

    {{-- Notifications --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 mb-6 rounded-xl shadow-xs flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">×</button>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 p-4 mb-6 rounded-xl shadow-xs flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i>
            <span class="text-sm font-semibold">{{ session('error') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">×</button>
    </div>
    @endif

    {{-- En-tête de la page --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Outfit', sans-serif;">Étape 3 : Associer des Sponsors (Facultatif)</h1>
            <p class="text-slate-500 text-sm font-medium mt-1">Ajoutez un ou plusieurs partenaires commerciaux et leurs logos pour valoriser votre événement.</p>
        </div>
    </div>

    <!-- Form Panel Batch Sponsors -->
    <form action="{{ route('organisateur.valide-sponsor') }}" method="POST" id="batch-sponsor-form" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if(request('wizard') || isset($isWizard))
            <input type="hidden" name="wizard" value="1">
        @endif
        <input type="hidden" name="action" id="sponsor-form-action" value="save">

        <!-- Card Event Choice -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-4">
            <div>
                <label for="evenement" class="block text-xs font-bold text-slate-600 mb-1.5 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-indigo-600"></i>
                    <span>Événement sponsorisé <span class="text-red-500">*</span></span>
                </label>
                <select id="evenement" name="evenement_id" required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                    <option value="" disabled {{ !$currentEvId ? 'selected' : '' }}>— Sélectionner un événement —</option>
                    @foreach($evenementid as $event)
                        <option value="{{ $event->id }}" {{ $currentEvId == $event->id ? 'selected' : '' }}>
                            {{ $event->titre }} ({{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }})
                        </option>
                    @endforeach
                </select>
                @error('evenement_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Dynamic Sponsor Rows Container -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2" style="font-family: 'Outfit', sans-serif;">
                    <span class="text-indigo-600"><i class="fas fa-handshake"></i></span>
                    <span>Vos sponsors et partenaires</span>
                </h3>
                <button type="button" onclick="addSponsorRow()" class="px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 font-bold rounded-xl text-xs transition-all flex items-center gap-1.5 shadow-xs">
                    <i class="fas fa-plus text-[10px]"></i> Ajouter un sponsor
                </button>
            </div>

            <div id="sponsor-rows-container" class="space-y-4">
                <!-- Rows injected dynamically via JS -->
            </div>

            <!-- Empty state -->
            <div id="sponsor-empty-state" class="text-center py-10 border-2 border-dashed border-slate-200 rounded-2xl">
                <i class="fas fa-handshake text-slate-300 text-4xl mb-3"></i>
                <p class="text-sm font-bold text-slate-600">Aucun sponsor en cours d'ajout.</p>
                <p class="text-xs text-slate-400 mt-1">Cliquez sur "+ Ajouter un sponsor" pour rajouter des logos et liens web.</p>
            </div>
        </div>

        <!-- Submit Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-slate-200">
            @if($currentEvId)
                <a href="{{ route('organisateur.detail', ['id' => $currentEvId]) }}" class="w-full sm:w-auto text-center px-5 py-3 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold rounded-xl text-xs transition-all shadow-xs">
                    Passer / Terminer sans sponsor
                </a>
            @else
                <div></div>
            @endif
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button type="button" onclick="submitSponsorForm('save')" class="w-full sm:w-auto px-5 py-3 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold rounded-xl text-sm transition-all shadow-xs">
                    Enregistrer les sponsors
                </button>
                <button type="button" onclick="submitSponsorForm('finish')" class="w-full sm:w-auto px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition-all border-0 shadow-sm flex items-center justify-center gap-2">
                    <span>Terminer & Voir l'événement</span>
                    <i class="fas fa-check text-xs"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Sponsors déjà associés -->
    @if(isset($sponsorsExistants) && count($sponsorsExistants) > 0)
        <div class="mt-8 bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <h3 class="text-base font-extrabold text-slate-900 mb-4 flex items-center gap-2" style="font-family: 'Outfit', sans-serif;">
                <span class="text-emerald-600"><i class="fas fa-check-circle"></i></span>
                <span>Sponsors déjà associés à cet événement ({{ count($sponsorsExistants) }})</span>
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($sponsorsExistants as $s)
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex flex-col items-center text-center">
                        @if($s->logo)
                            <img src="{{ asset('storage/evenement/sponsors/' . $s->logo) }}" alt="{{ $s->nom }}" class="w-16 h-12 object-contain mb-2">
                        @else
                            <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-base mb-2">
                                {{ strtoupper(substr($s->nom, 0, 2)) }}
                            </div>
                        @endif
                        <span class="text-xs font-bold text-slate-800 truncate w-full">{{ $s->nom }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

<!-- JavaScript dynamic sponsor rows -->
<script>
let sponsorRowIndex = 0;

function addSponsorRow(name = '', web = '') {
    const container = document.getElementById('sponsor-rows-container');
    const emptyState = document.getElementById('sponsor-empty-state');
    emptyState.classList.add('hidden');

    const rowId = `sponsor-row-${sponsorRowIndex}`;
    const div = document.createElement('div');
    div.id = rowId;
    div.className = "bg-slate-50 border border-slate-200 p-4 rounded-2xl space-y-3 transition-all relative shadow-xs";

    div.innerHTML = `
        <div class="flex items-center justify-between pb-2 border-b border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Sponsor #${sponsorRowIndex + 1}</span>
            <button type="button" onclick="removeSponsorRow('${rowId}')" class="text-red-500 hover:text-red-700 text-xs font-bold flex items-center gap-1">
                <i class="fas fa-trash-alt"></i> Supprimer
            </button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="text-xs font-bold text-slate-600 block mb-1">Nom du sponsor *</label>
                <input type="text" name="sponsors[${sponsorRowIndex}][nom]" value="${name}" placeholder="Ex: Moov Togo, Canal+..." required
                       class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold">
            </div>
            <div>
                <label class="text-xs font-bold text-slate-600 block mb-1">Logo / Image (JPEG, PNG, SVG)</label>
                <input type="file" name="sponsors[${sponsorRowIndex}][logo]" accept="image/*"
                       class="w-full bg-white border border-slate-200 text-slate-800 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="text-xs font-bold text-slate-600 block mb-1">Site Web (Optionnel)</label>
                <input type="url" name="sponsors[${sponsorRowIndex}][lien_web]" value="${web}" placeholder="https://..."
                       class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
        </div>
    `;

    container.appendChild(div);
    sponsorRowIndex++;
}

function removeSponsorRow(rowId) {
    const row = document.getElementById(rowId);
    if (row) {
        row.remove();
    }
    const container = document.getElementById('sponsor-rows-container');
    if (container.children.length === 0) {
        document.getElementById('sponsor-empty-state').classList.remove('hidden');
    }
}

function submitSponsorForm(actionValue) {
    document.getElementById('sponsor-form-action').value = actionValue;
    document.getElementById('batch-sponsor-form').submit();
}

document.addEventListener('DOMContentLoaded', function() {
    @if(!isset($sponsorsExistants) || count($sponsorsExistants) == 0)
        addSponsorRow();
    @endif
});
</script>

@endsection
