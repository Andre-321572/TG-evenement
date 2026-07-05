@extends('layouts.Obase')

@section('title', '— Design du ticket | ' . $evenement->titre)

@section('content')
<div class="content-wrapper" style="background:#f0f4f8; min-height:100vh;">

    {{-- Header --}}
    <div style="background:linear-gradient(135deg,#1e1b4b,#312e81); padding:2rem 2rem 1.5rem; margin-bottom:2rem;">
        <div class="d-flex align-items-center gap-3 mb-1">
            <a href="{{ route('organisateur.billet-all') }}" style="color:#a5b4fc; text-decoration:none; font-size:.85rem;">
                ← Retour aux billets
            </a>
        </div>
        <h1 style="color:#fff; font-size:1.6rem; font-weight:800; margin:0;">
            🎨 Design du ticket
        </h1>
        <p style="color:#a5b4fc; margin:.25rem 0 0; font-size:.9rem;">{{ $evenement->titre }}</p>
    </div>

    <div class="container-fluid px-4">
        @if(session('success'))
            <div class="alert" style="background:#dcfce7; border:1px solid #86efac; color:#166534; border-radius:12px; padding:1rem 1.25rem; margin-bottom:1.5rem; font-weight:600;">
                ✅ {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('organisateur.ticket-design-store', $evenement->id) }}" method="POST" enctype="multipart/form-data" id="design-form">
            @csrf

            <div style="display:flex; gap:1.5rem; flex-wrap:wrap;">

                {{-- ===== COLONNE GAUCHE: Éditeur ===== --}}
                <div style="flex:1; min-width:340px;">

                    {{-- Template de base --}}
                    <div class="design-card">
                        <h5 class="section-label">📐 Template de base</h5>
                        <div class="row g-3">
                            @foreach(['classique' => ['icon'=>'🎫','desc'=>'Sobre et professionnel'], 'moderne' => ['icon'=>'✨','desc'=>'Épuré et élégant'], 'festif' => ['icon'=>'🎉','desc'=>'Coloré et dynamique']] as $key => $info)
                            <div class="col-4">
                                <label class="template-card {{ ($design->template ?? 'moderne') == $key ? 'selected' : '' }}" for="tmpl-{{ $key }}">
                                    <input type="radio" name="template" id="tmpl-{{ $key }}" value="{{ $key }}" {{ ($design->template ?? 'moderne') == $key ? 'checked' : '' }} style="display:none;">
                                    <div class="tmpl-preview tmpl-{{ $key }}">
                                        <span style="font-size:2rem;">{{ $info['icon'] }}</span>
                                        <div class="tmpl-lines">
                                            <div class="tmpl-line long"></div>
                                            <div class="tmpl-line short"></div>
                                        </div>
                                    </div>
                                    <div class="tmpl-name">{{ ucfirst($key) }}</div>
                                    <div class="tmpl-desc">{{ $info['desc'] }}</div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Logo --}}
                    <div class="design-card">
                        <h5 class="section-label">🖼️ Logo personnalisé</h5>
                        <div style="display:flex; gap:1rem;">
                            @if($design->logo)
                                <div style="position:relative;">
                                    <img src="{{ $design->logo_url }}" style="height:70px; border-radius:8px; border:2px solid #e2e8f0; background:#f8fafc; padding:4px;">
                                    <button type="button" onclick="deleteLogo({{ $evenement->id }})"
                                            style="position:absolute;top:-8px;right:-8px;background:#ef4444;color:#fff;border:none;border-radius:50%;width:22px;height:22px;font-size:.75rem;cursor:pointer;display:flex;align-items:center;justify-content:center;">✕</button>
                                </div>
                            @endif
                            <label class="upload-zone" for="logo-input" style="flex:1; min-width:200px;">
                                <input type="file" name="logo" id="logo-input" accept="image/*" style="display:none;" onchange="previewFile(this,'logo-preview')">
                                <div id="logo-preview" style="text-align:center; color:#64748b;">
                                    <div style="font-size:2rem;">📤</div>
                                    <div style="font-size:.85rem; font-weight:600; margin-top:.25rem;">Choisir un logo</div>
                                    <div style="font-size:.75rem; color:#94a3b8;">PNG, JPG, SVG — max 2 Mo</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Fond --}}
                    <div class="design-card">
                        <h5 class="section-label">🎨 Fond du ticket</h5>

                        {{-- Type de fond --}}
                        <div class="d-flex gap-2 mb-3">
                            @foreach(['couleur'=>'Couleur unie','degrade'=>'Dégradé','image'=>'Image'] as $type => $label)
                            <label class="fond-type-btn {{ ($design->fond_type ?? 'couleur') == $type ? 'active' : '' }}" for="fond-{{ $type }}">
                                <input type="radio" name="fond_type" id="fond-{{ $type }}" value="{{ $type }}" {{ ($design->fond_type ?? 'couleur') == $type ? 'checked' : '' }} style="display:none;" onchange="updateFondType(this.value)">
                                {{ $label }}
                            </label>
                            @endforeach
                        </div>

                        {{-- Options couleur --}}
                        <div id="fond-couleur-section" class="{{ ($design->fond_type ?? 'couleur') == 'image' ? 'd-none' : '' }}">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label-sm">Couleur principale</label>
                                    <div class="color-picker-wrap">
                                        <input type="color" name="fond_couleur1" id="fond_couleur1" value="{{ $design->fond_couleur1 ?? '#4f46e5' }}" onchange="updatePreview()">
                                        <span id="fond_couleur1_hex">{{ $design->fond_couleur1 ?? '#4f46e5' }}</span>
                                    </div>
                                </div>
                                <div class="col-6" id="couleur2-wrap" style="{{ ($design->fond_type ?? 'couleur') == 'couleur' ? 'opacity:.4;pointer-events:none;' : '' }}">
                                    <label class="form-label-sm">Couleur secondaire (dégradé)</label>
                                    <div class="color-picker-wrap">
                                        <input type="color" name="fond_couleur2" id="fond_couleur2" value="{{ $design->fond_couleur2 ?? '#7c3aed' }}" onchange="updatePreview()">
                                        <span id="fond_couleur2_hex">{{ $design->fond_couleur2 ?? '#7c3aed' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Image de fond --}}
                        <div id="fond-image-section" class="{{ ($design->fond_type ?? 'couleur') == 'image' ? '' : 'd-none' }} mt-3">
                            @if($design->fond_image)
                                <div class="mb-2" style="position:relative; display:inline-block;">
                                    <img src="{{ $design->fond_image_url }}" style="height:80px; border-radius:8px; object-fit:cover; width:160px;">
                                    <button type="button" onclick="deleteFond({{ $evenement->id }})"
                                            style="position:absolute;top:-8px;right:-8px;background:#ef4444;color:#fff;border:none;border-radius:50%;width:22px;height:22px;font-size:.75rem;cursor:pointer;">✕</button>
                                </div>
                            @endif
                            <label class="upload-zone" for="fond-image-input">
                                <input type="file" name="fond_image" id="fond-image-input" accept="image/*" style="display:none;" onchange="previewFile(this,'fond-preview')">
                                <div id="fond-preview" style="text-align:center; color:#64748b;">
                                    <div style="font-size:2rem;">🖼️</div>
                                    <div style="font-size:.85rem; font-weight:600;">Choisir une image de fond</div>
                                    <div style="font-size:.75rem; color:#94a3b8;">PNG, JPG — max 5 Mo (recommandé: 1200×600px)</div>
                                </div>
                            </label>
                        </div>

                        {{-- Opacité --}}
                        <div class="mt-3">
                            <label class="form-label-sm">Opacité du fond : <strong id="opacite-val">{{ $design->fond_opacite ?? 100 }}%</strong></label>
                            <input type="range" name="fond_opacite" min="10" max="100" value="{{ $design->fond_opacite ?? 100 }}"
                                   oninput="document.getElementById('opacite-val').textContent=this.value+'%'; updatePreview()"
                                   style="width:100%; accent-color:#4f46e5;">
                        </div>
                    </div>

                    {{-- Typographie & Couleurs --}}
                    <div class="design-card">
                        <h5 class="section-label">✏️ Typographie & Couleurs du texte</h5>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label-sm">Police</label>
                                <select name="police" id="police-select" onchange="updatePreview()" class="form-select-custom">
                                    @foreach($policesDisponibles as $val => $label)
                                        <option value="{{ $val }}" {{ ($design->police ?? 'Outfit') == $val ? 'selected' : '' }}
                                                style="font-family: '{{ $val }}'">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label-sm">Couleur du titre</label>
                                <div class="color-picker-wrap">
                                    <input type="color" name="couleur_titre" id="couleur_titre" value="{{ $design->couleur_titre ?? '#ffffff' }}" onchange="updatePreview()">
                                    <span id="couleur_titre_hex">{{ $design->couleur_titre ?? '#ffffff' }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label-sm">Couleur du texte</label>
                                <div class="color-picker-wrap">
                                    <input type="color" name="couleur_texte" id="couleur_texte" value="{{ $design->couleur_texte ?? '#e2e8f0' }}" onchange="updatePreview()">
                                    <span id="couleur_texte_hex">{{ $design->couleur_texte ?? '#e2e8f0' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Couleurs par type de billet --}}
                    @if($evenement->billets->count() > 0)
                    <div class="design-card">
                        <h5 class="section-label">🎫 Couleur par type de billet</h5>
                        <p style="font-size:.85rem; color:#64748b; margin-bottom:1rem;">Chaque type de billet peut avoir sa propre couleur distinctive.</p>
                        @foreach($evenement->billets as $billet)
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span style="font-weight:700; font-size:.9rem; color:#334155; min-width:100px;">{{ $billet->type }}</span>
                            <span style="font-size:.8rem; color:#64748b; flex:1;">{{ number_format($billet->prix, 0, ',', ' ') }} FCFA</span>
                            <div class="color-picker-wrap" style="margin:0;">
                                <input type="color" name="billet_couleurs[{{ $billet->id }}]"
                                       value="{{ $billet->ticket_couleur ?? '#4f46e5' }}"
                                       onchange="this.nextElementSibling.textContent=this.value">
                                <span>{{ $billet->ticket_couleur ?? '#4f46e5' }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                </div>

                {{-- ===== COLONNE DROITE: Prévisualisation ===== --}}
                <div style="flex:0 0 380px; max-width:420px;">
                    <div>
                        <div class="design-card">
                            <h5 class="section-label">👁️ Prévisualisation en temps réel</h5>

                            {{-- Ticket preview --}}
                            <div id="ticket-preview" style="
                                border-radius:16px;
                                overflow:hidden;
                                box-shadow: 0 20px 60px rgba(0,0,0,.25);
                                position:relative;
                                min-height:220px;
                                font-family: '{{ $design->police ?? 'Outfit' }}', sans-serif;
                                {{ $design->fond_css ?? 'background:#4f46e5;' }}
                            ">
                                {{-- Fond overlay --}}
                                <div id="preview-overlay" style="position:absolute;inset:0;background:rgba(0,0,0,{{ 1 - ($design->fond_opacite ?? 100) / 100 }});border-radius:16px;"></div>

                                {{-- Contenu --}}
                                <div style="position:relative;z-index:2;padding:1.5rem;">
                                    {{-- Logo --}}
                                    @if($design->logo)
                                        <img src="{{ $design->logo_url }}" id="preview-logo" style="height:36px;margin-bottom:.75rem;display:block;">
                                    @else
                                        <div id="preview-logo" style="display:none;height:36px;margin-bottom:.75rem;"></div>
                                    @endif

                                    {{-- Event name --}}
                                    <h3 id="preview-title" style="color:{{ $design->couleur_titre ?? '#ffffff' }};font-size:1.2rem;font-weight:800;margin:0 0 .5rem;">
                                        {{ $evenement->titre }}
                                    </h3>
                                    <p id="preview-date" style="color:{{ $design->couleur_texte ?? '#e2e8f0' }};font-size:.85rem;margin:0 0 .25rem;">
                                        📅 {{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y') }}
                                    </p>
                                    <p id="preview-lieu" style="color:{{ $design->couleur_texte ?? '#e2e8f0' }};font-size:.85rem;margin:0;">
                                        📍 {{ $evenement->lieu }}
                                    </p>

                                    {{-- Divider --}}
                                    <div style="border-top:1px dashed rgba(255,255,255,0.3);margin:1rem 0;"></div>

                                    {{-- QR placeholder --}}
                                    <div style="display:flex;justify-content:space-between;align-items:flex-end;">
                                        <div>
                                            <p style="color:rgba(255,255,255,.6);font-size:.7rem;margin:0 0 .2rem;">DÉTENTEUR</p>
                                            <p id="preview-holder" style="color:{{ $design->couleur_titre ?? '#ffffff' }};font-size:.9rem;font-weight:700;margin:0;">Jean Dupont</p>
                                        </div>
                                        <div style="background:rgba(255,255,255,.15);border-radius:8px;padding:.5rem;">
                                            <div style="display:grid;grid-template-columns:repeat(5,8px);gap:2px;">
                                                @for($i=0;$i<25;$i++)
                                                    <div style="width:8px;height:8px;background:{{ rand(0,1) ? 'rgba(255,255,255,0.9)' : 'transparent' }};border-radius:1px;"></div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Ticket type badge --}}
                                    <div style="margin-top:1rem;">
                                        <span style="background:rgba(255,255,255,0.2);color:#fff;padding:.25rem .75rem;border-radius:20px;font-size:.75rem;font-weight:700;">
                                            {{ $evenement->billets->first()->type ?? 'STANDARD' }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Déco perforations --}}
                                <div style="display:flex;justify-content:space-between;padding:0 1rem;margin-top:-.5rem;position:relative;z-index:2;">
                                    <div style="width:20px;height:20px;background:#f0f4f8;border-radius:50%;margin-left:-2rem;"></div>
                                    <div style="width:20px;height:20px;background:#f0f4f8;border-radius:50%;margin-right:-2rem;"></div>
                                </div>
                            </div>

                            <p style="font-size:.75rem;color:#94a3b8;margin-top:.75rem;text-align:center;">La prévisualisation est approximative</p>
                        </div>

                        {{-- Bouton save --}}
                        <button type="submit" style="
                            width:100%;
                            background:linear-gradient(135deg,#4f46e5,#7c3aed);
                            color:#fff;
                            border:none;
                            border-radius:12px;
                            padding:1rem;
                            font-size:1rem;
                            font-weight:700;
                            cursor:pointer;
                            margin-top:1rem;
                            box-shadow:0 4px 20px rgba(79,70,229,.4);
                            transition:transform .2s;
                        " onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                            💾 Sauvegarder le design
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<style>
.design-card {
    background:#fff;
    border-radius:16px;
    padding:1.5rem;
    margin-bottom:1.5rem;
    border:1px solid #e2e8f0;
    box-shadow:0 1px 8px rgba(0,0,0,.04);
}
.section-label {
    font-size:.95rem;
    font-weight:800;
    color:#1e293b;
    margin-bottom:1.1rem;
    padding-bottom:.6rem;
    border-bottom:2px solid #f1f5f9;
}
.template-card {
    display:block;
    border:2px solid #e2e8f0;
    border-radius:12px;
    padding:.75rem .5rem;
    cursor:pointer;
    text-align:center;
    transition:all .2s;
}
.template-card:hover, .template-card.selected {
    border-color:#4f46e5;
    background:#eef2ff;
}
.tmpl-preview {
    height:60px;
    border-radius:8px;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:.5rem;
    margin-bottom:.5rem;
    padding:.5rem;
}
.tmpl-classique { background:linear-gradient(135deg,#0f172a,#1e293b); }
.tmpl-moderne   { background:linear-gradient(135deg,#4f46e5,#7c3aed); }
.tmpl-festif    { background:linear-gradient(135deg,#f59e0b,#ef4444); }
.tmpl-lines { display:flex; flex-direction:column; gap:4px; }
.tmpl-line { background:rgba(255,255,255,.6); border-radius:2px; height:4px; }
.tmpl-line.long  { width:50px; }
.tmpl-line.short { width:30px; }
.tmpl-name { font-weight:700; font-size:.85rem; color:#1e293b; }
.tmpl-desc { font-size:.72rem; color:#64748b; }
.fond-type-btn {
    flex:1;
    text-align:center;
    padding:.5rem .75rem;
    border:2px solid #e2e8f0;
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
    font-size:.85rem;
    color:#334155;
    transition:all .15s;
}
.fond-type-btn.active {
    border-color:#4f46e5;
    background:#eef2ff;
    color:#4f46e5;
}
.color-picker-wrap {
    display:flex;
    align-items:center;
    gap:.6rem;
    background:#f8fafc;
    border:1px solid #e2e8f0;
    border-radius:8px;
    padding:.35rem .6rem;
}
.color-picker-wrap input[type=color] {
    width:36px;
    height:36px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    padding:0;
    background:none;
}
.color-picker-wrap span {
    font-family:monospace;
    font-size:.85rem;
    color:#475569;
    font-weight:600;
}
.upload-zone {
    display:block;
    border:2px dashed #cbd5e1;
    border-radius:10px;
    padding:1rem;
    cursor:pointer;
    transition:all .2s;
}
.upload-zone:hover { border-color:#4f46e5; background:#f5f3ff; }
.form-label-sm { font-size:.82rem; font-weight:700; color:#475569; display:block; margin-bottom:.4rem; }
.form-select-custom {
    width:100%;
    padding:.55rem .75rem;
    border:1px solid #e2e8f0;
    border-radius:8px;
    font-size:.9rem;
    color:#334155;
    background:#fff;
    cursor:pointer;
}
</style>

<script>
// Google Fonts preload
const fonts = ['Montserrat','Playfair+Display','Poppins','Raleway','Dancing+Script','Bebas+Neue','Oswald','Roboto','Lato','Outfit'];
const link = document.createElement('link');
link.rel = 'stylesheet';
link.href = 'https://fonts.googleapis.com/css2?family=' + fonts.join('&family=') + '&display=swap';
document.head.appendChild(link);

// Template selection
document.querySelectorAll('.template-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.template-card').forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
        updatePreview();
    });
});

// Fond type toggle
function updateFondType(type) {
    document.querySelectorAll('.fond-type-btn').forEach(b => b.classList.remove('active'));
    document.querySelector(`[for="fond-${type}"]`)?.classList.add('active');
    document.querySelectorAll('.fond-type-btn').forEach(b => {
        if (b.querySelector(`input[value="${type}"]`)) b.classList.add('active');
    });

    document.getElementById('fond-image-section').classList.toggle('d-none', type !== 'image');
    document.getElementById('fond-couleur-section').classList.toggle('d-none', type === 'image');
    const c2 = document.getElementById('couleur2-wrap');
    c2.style.opacity = type === 'degrade' ? '1' : '.4';
    c2.style.pointerEvents = type === 'degrade' ? 'auto' : 'none';
    updatePreview();
}

// Fond type button click
document.querySelectorAll('.fond-type-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.fond-type-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});

// Update hex labels on color change
document.querySelectorAll('input[type=color]').forEach(input => {
    input.addEventListener('input', function() {
        const span = this.nextElementSibling;
        if (span) span.textContent = this.value;
    });
});

// Live preview update
function updatePreview() {
    const preview = document.getElementById('ticket-preview');
    const type    = document.querySelector('input[name=fond_type]:checked')?.value || 'couleur';
    const c1      = document.getElementById('fond_couleur1').value;
    const c2      = document.getElementById('fond_couleur2').value;
    const police  = document.getElementById('police-select').value;
    const cTitre  = document.getElementById('couleur_titre').value;
    const cTexte  = document.getElementById('couleur_texte').value;
    const opacite = document.querySelector('input[name=fond_opacite]').value;

    if (type === 'degrade') {
        preview.style.background = `linear-gradient(135deg, ${c1}, ${c2})`;
    } else if (type === 'image') {
        preview.style.background = c1; // fallback
    } else {
        preview.style.background = c1;
    }

    preview.style.fontFamily = `'${police}', sans-serif`;
    document.getElementById('preview-title').style.color  = cTitre;
    document.getElementById('preview-title').style.fontFamily = `'${police}', sans-serif`;
    document.getElementById('preview-date').style.color   = cTexte;
    document.getElementById('preview-lieu').style.color   = cTexte;
    document.getElementById('preview-holder').style.color = cTitre;

    const overlayOpacity = 1 - parseInt(opacite) / 100;
    document.getElementById('preview-overlay').style.background = `rgba(0,0,0,${overlayOpacity})`;
}

// File preview
function previewFile(input, targetId) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const target = document.getElementById(targetId);
        target.innerHTML = `<img src="${e.target.result}" style="max-height:60px;border-radius:6px;"> <div style="font-size:.8rem;color:#64748b;margin-top:.25rem;">${file.name}</div>`;
    };
    reader.readAsDataURL(file);
}

// Delete logo
function deleteLogo(id) {
    if (!confirm('Supprimer ce logo ?')) return;
    fetch(`/organisateur/ticket-design/${id}/logo`, {method:'DELETE',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]')?.content||''}})
        .then(() => location.reload());
}
function deleteFond(id) {
    if (!confirm('Supprimer cette image de fond ?')) return;
    fetch(`/organisateur/ticket-design/${id}/fond`, {method:'DELETE',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]')?.content||''}})
        .then(() => location.reload());
}

// Init
updatePreview();
</script>
@endsection
