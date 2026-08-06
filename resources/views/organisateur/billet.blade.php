@extends('layouts.Obase')
@section('title', '| Création de billets')
@section('content')

@php
    $currentEvId = $selectedEvenementId ?? ($evenementActuel->id ?? null);
@endphp

<div class="container mx-auto px-4 sm:px-6 py-8 max-w-5xl text-slate-800">
    
    <!-- Wizard Stepper -->
    @include('organisateur.include.wizard-stepper', ['step' => 2, 'evenement' => $evenementActuel ?? null])

    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Outfit', sans-serif;">Étape 2 : Configuration des Billets</h1>
            <p class="text-slate-500 text-sm font-medium mt-1">Définissez vos types de billets, vos quantités et fixez vos prix ou marquez-les gratuits.</p>
        </div>
    </div>

    <!-- Notifications -->
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 mb-6 rounded-xl shadow-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span class="text-sm font-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" class="text-emerald-500 hover:text-emerald-700" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 mb-6 rounded-xl shadow-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <span class="text-sm font-semibold">{{ session('error') }}</span>
            </div>
            <button type="button" class="text-red-500 hover:text-red-700" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    <!-- Form Panel -->
    <form action="{{ route('organisateur.valide-billet') }}" method="POST" id="batch-ticket-form" class="space-y-6">
        @csrf
        @if(request('wizard') || isset($isWizard))
            <input type="hidden" name="wizard" value="1">
        @endif
        <input type="hidden" name="action" id="form-action" value="save">

        <!-- Card Event Choice & Presets -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
            
            <!-- Select Event -->
            <div>
                <label for="evenement" class="text-xs font-bold text-slate-600 mb-1.5 block">
                    Événement rattaché <span class="text-red-500">*</span>
                </label>
                <select class="w-full bg-slate-50 border border-slate-200 text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-xl px-4 py-3 text-sm transition-all" id="evenement" name="evenement_id" required>
                    <option value="" disabled {{ !$currentEvId ? 'selected' : '' }} class="text-slate-400">Choisir un événement</option>
                    @foreach ($evenementid as $event)
                        <option value="{{ $event->id }}" {{ $currentEvId == $event->id ? 'selected' : '' }}>
                            {{ $event->titre }} ({{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }})
                        </option>
                    @endforeach
                </select>
                @error('evenement_id')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Quick Add Presets Bar -->
            <div class="pt-4 border-t border-slate-100">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-3">Ajouter rapidement une catégorie :</label>
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="addTicketRow('STANDARD')" class="px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 font-bold rounded-xl text-xs transition-all flex items-center gap-1.5 shadow-xs">
                        <i class="fas fa-plus text-[10px]"></i> Standard
                    </button>
                    <button type="button" onclick="addTicketRow('VIP')" class="px-4 py-2 bg-amber-50 border border-amber-200 text-amber-800 hover:bg-amber-100 font-bold rounded-xl text-xs transition-all flex items-center gap-1.5 shadow-xs">
                        <i class="fas fa-plus text-[10px]"></i> VIP
                    </button>
                    <button type="button" onclick="addTicketRow('VVIP')" class="px-4 py-2 bg-purple-50 border border-purple-200 text-purple-800 hover:bg-purple-100 font-bold rounded-xl text-xs transition-all flex items-center gap-1.5 shadow-xs">
                        <i class="fas fa-plus text-[10px]"></i> VVIP
                    </button>
                    <button type="button" onclick="addTicketRow('')" class="px-4 py-2 bg-slate-100 border border-slate-200 text-slate-700 hover:bg-slate-200 font-bold rounded-xl text-xs transition-all flex items-center gap-1.5 shadow-xs">
                        <i class="fas fa-plus text-[10px]"></i> Autre Billet
                    </button>
                </div>
            </div>
        </div>

        <!-- Dynamic Rows Container -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2" style="font-family: 'Outfit', sans-serif;">
                    <span class="text-indigo-600"><i class="fas fa-tags"></i></span>
                    <span>Vos catégories de billets</span>
                </h3>
                <span id="ticket-count-badge" class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-200">0 catégorie(s)</span>
            </div>

            <div id="ticket-rows-container" class="space-y-4">
                <!-- Rows injected dynamically via JS -->
            </div>

            <!-- Empty state message if no row present -->
            <div id="empty-state" class="text-center py-10 border-2 border-dashed border-slate-200 rounded-2xl">
                <i class="fas fa-ticket-alt text-slate-300 text-4xl mb-3"></i>
                <p class="text-sm font-bold text-slate-600">Aucune catégorie de billet ajoutée pour le moment.</p>
                <p class="text-xs text-slate-400 mt-1">Utilisez les boutons ci-dessus pour ajouter des billets Standard, VIP ou personnalisés.</p>
            </div>

            <!-- Live Calculation Summary -->
            <div id="calculation-summary" class="hidden pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-2">
                <div>Total des places : <strong id="total-qty-sum" class="text-slate-900 font-extrabold">0</strong></div>
                <div>Recette maximale potentielle : <strong id="total-revenue-sum" class="text-emerald-600 font-extrabold text-sm">0 FCFA</strong></div>
            </div>
        </div>

        <!-- Submit Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-slate-200">
            <a href="{{ route('organisateur.evenement-en-cours') }}" class="w-full sm:w-auto text-center px-5 py-3 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold rounded-xl text-xs transition-all shadow-xs">
                Ignorer & Conserver billets actuels
            </a>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button type="button" onclick="submitBatchForm('save')" class="w-full sm:w-auto px-5 py-3 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold rounded-xl text-sm transition-all shadow-xs">
                    Enregistrer les billets
                </button>
                <button type="button" onclick="submitBatchForm('next_sponsors')" class="w-full sm:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm transition-all border-0 shadow-sm flex items-center justify-center gap-2">
                    <span>Suivant : Créer les sponsors</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Section des billets déjà créés pour cet événement -->
    @if(isset($billetsExistants) && count($billetsExistants) > 0)
        <div class="mt-8 bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <h3 class="text-base font-extrabold text-slate-900 mb-4 flex items-center gap-2" style="font-family: 'Outfit', sans-serif;">
                <span class="text-emerald-600"><i class="fas fa-check-circle"></i></span>
                <span>Billets actuellement configurés ({{ count($billetsExistants) }})</span>
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($billetsExistants as $b)
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-extrabold uppercase tracking-wider text-indigo-700 bg-indigo-100 px-2.5 py-0.5 rounded-lg">{{ $b->type }}</span>
                                <span class="text-sm font-extrabold {{ $b->prix == 0 ? 'text-emerald-600' : 'text-slate-900' }}">
                                    {{ $b->prix == 0 ? 'GRATUIT' : number_format($b->prix, 0, ',', ' ') . ' FCFA' }}
                                </span>
                            </div>
                            <p class="text-xs font-semibold text-slate-500">Quantité : <strong class="text-slate-800">{{ number_format($b->quantite, 0, ',', ' ') }} places</strong></p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

<!-- JavaScript pour la gestion dynamique des lignes de billets avec gratuité et prix définis par l'organisateur -->
<script>
let rowIndex = 0;

function addTicketRow(type = '') {
    const container = document.getElementById('ticket-rows-container');
    const emptyState = document.getElementById('empty-state');
    emptyState.classList.add('hidden');

    const rowId = `row-${rowIndex}`;
    const div = document.createElement('div');
    div.id = rowId;
    div.className = "bg-slate-50 border border-slate-200 p-4 rounded-2xl space-y-3 transition-all relative shadow-xs";

    div.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <!-- Nom du Billet -->
            <div class="md:col-span-4">
                <label class="text-xs font-bold text-slate-600 block mb-1">Nom / Type du billet *</label>
                <input type="text" name="billets[${rowIndex}][type]" value="${type}" placeholder="Ex: Standard, VIP, Pass Journée" required
                       oninput="updateSummary()"
                       class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold">
            </div>

            <!-- Quantité -->
            <div class="md:col-span-3">
                <label class="text-xs font-bold text-slate-600 block mb-1">Nombre de places *</label>
                <input type="number" name="billets[${rowIndex}][quantite]" value="100" min="1" required
                       oninput="updateSummary()"
                       class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold">
            </div>

            <!-- Prix & Checkbox Gratuit -->
            <div class="md:col-span-4">
                <div class="flex items-center justify-between mb-1">
                    <label class="text-xs font-bold text-slate-600 block">Prix unitaire (FCFA) *</label>
                    <label class="inline-flex items-center gap-1.5 cursor-pointer text-xs font-bold text-indigo-600">
                        <input type="checkbox" onchange="toggleGratuit('${rowId}', this)" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span>Événement / Billet Gratuit</span>
                    </label>
                </div>
                <input type="number" name="billets[${rowIndex}][prix]" value="" min="0" placeholder="Ex: 5000 (Saisissez le prix)" required
                       oninput="updateSummary()"
                       class="price-input w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-extrabold">
            </div>

            <!-- Supprimer -->
            <div class="md:col-span-1 flex justify-end pt-2 md:pt-0">
                <button type="button" onclick="removeTicketRow('${rowId}')" title="Supprimer cette catégorie" class="w-9 h-9 rounded-xl bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition-colors">
                    <i class="fas fa-trash-alt text-xs"></i>
                </button>
            </div>
        </div>
    `;

    container.appendChild(div);
    rowIndex++;
    updateSummary();
}

function toggleGratuit(rowId, checkbox) {
    const row = document.getElementById(rowId);
    if (!row) return;
    const priceInput = row.querySelector('.price-input');
    if (checkbox.checked) {
        priceInput.value = 0;
        priceInput.readOnly = true;
        priceInput.classList.add('bg-slate-100', 'text-slate-400');
    } else {
        priceInput.readOnly = false;
        priceInput.value = '';
        priceInput.classList.remove('bg-slate-100', 'text-slate-400');
    }
    updateSummary();
}

function removeTicketRow(rowId) {
    const row = document.getElementById(rowId);
    if (row) {
        row.remove();
    }
    const container = document.getElementById('ticket-rows-container');
    if (container.children.length === 0) {
        document.getElementById('empty-state').classList.remove('hidden');
    }
    updateSummary();
}

function updateSummary() {
    const container = document.getElementById('ticket-rows-container');
    const rows = container.children;
    const countBadge = document.getElementById('ticket-count-badge');
    const summaryDiv = document.getElementById('calculation-summary');
    
    countBadge.textContent = `${rows.length} catégorie(s)`;

    if (rows.length === 0) {
        summaryDiv.classList.add('hidden');
        return;
    }

    summaryDiv.classList.remove('hidden');
    let totalQty = 0;
    let totalRevenue = 0;

    for (let row of rows) {
        const priceInput = row.querySelector('.price-input');
        const qtyInput = row.querySelector('input[name*="[quantite]"]');

        const price = priceInput ? parseFloat(priceInput.value) || 0 : 0;
        const qty = qtyInput ? parseInt(qtyInput.value) || 0 : 0;

        totalQty += qty;
        totalRevenue += (price * qty);
    }

    document.getElementById('total-qty-sum').textContent = totalQty.toLocaleString();
    document.getElementById('total-revenue-sum').textContent = `${totalRevenue.toLocaleString()} FCFA`;
}

function submitBatchForm(actionValue) {
    const container = document.getElementById('ticket-rows-container');
    if (container.children.length === 0) {
        addTicketRow('Standard');
    }
    document.getElementById('form-action').value = actionValue;
    document.getElementById('batch-ticket-form').submit();
}

document.addEventListener('DOMContentLoaded', function() {
    @if(!isset($billetsExistants) || count($billetsExistants) == 0)
        addTicketRow('Standard');
    @endif
});
</script>

@endsection
