@extends('layouts.Obases')

@section('title', '— Utilisateurs inscrits')

@section('content')
<div class="content-wrapper" style="background:#f0f4f8; min-height:100vh;">

    {{-- Header --}}
    <div style="background:linear-gradient(135deg,#1e1b4b,#312e81); padding:2rem 2rem 1.5rem; margin-bottom:2rem;">
        <h1 style="color:#fff; font-size:1.6rem; font-weight:800; margin:0 0 .25rem;">
            👥 Utilisateurs inscrits
        </h1>
        <p style="color:#a5b4fc; margin:0; font-size:.9rem;">Tous les comptes créés sur le site web et l'application mobile</p>
    </div>

    <div class="container-fluid px-4">

        @if(session('success'))
            <div style="background:#dcfce7; border:1px solid #86efac; color:#166534; border-radius:12px; padding:1rem 1.25rem; margin-bottom:1.5rem; font-weight:600;">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Stats cards --}}
        <div class="row g-3 mb-4">
            @php
            $statCards = [
                ['label'=>'Total inscrits',  'val'=>$stats['total'],         'icon'=>'👥', 'color'=>'#4f46e5','bg'=>'#eef2ff'],
                ['label'=>'Participants',     'val'=>$stats['participants'],  'icon'=>'🎟️', 'color'=>'#059669','bg'=>'#dcfce7'],
                ['label'=>'Organisateurs',    'val'=>$stats['organisateurs'], 'icon'=>'🎪', 'color'=>'#d97706','bg'=>'#fef3c7'],
                ['label'=>'Scanners',         'val'=>$stats['scanners'],      'icon'=>'📷', 'color'=>'#0891b2','bg'=>'#cffafe'],
                ['label'=>'Via Mobile App',   'val'=>$stats['mobile'],        'icon'=>'📱', 'color'=>'#7c3aed','bg'=>'#f5f3ff'],
            ];
            @endphp
            @foreach($statCards as $card)
            <div class="col-6 col-md-4 col-lg-2" style="flex:1;">
                <div style="background:#fff; border-radius:14px; padding:1.1rem 1rem; border:1px solid #e2e8f0; text-align:center;">
                    <div style="font-size:1.8rem; margin-bottom:.3rem;">{{ $card['icon'] }}</div>
                    <div style="font-size:1.6rem; font-weight:800; color:{{ $card['color'] }};">{{ number_format($card['val']) }}</div>
                    <div style="font-size:.78rem; color:#64748b; font-weight:600;">{{ $card['label'] }}</div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Filtres --}}
        <div style="background:#fff; border-radius:14px; padding:1.25rem; border:1px solid #e2e8f0; margin-bottom:1.5rem;">
            <form method="GET" action="{{ route('organisateur.utilisateurs') }}" class="d-flex gap-2 flex-wrap align-items-end">
                <div style="flex:2; min-width:200px;">
                    <label style="font-size:.8rem; font-weight:700; color:#475569; display:block; margin-bottom:.3rem;">🔍 Recherche</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nom, prénom, email, téléphone..."
                           style="width:100%; padding:.6rem .85rem; border:1px solid #e2e8f0; border-radius:8px; font-size:.9rem; outline:none;">
                </div>
                <div style="min-width:180px;">
                    <label style="font-size:.8rem; font-weight:700; color:#475569; display:block; margin-bottom:.3rem;">🎭 Rôle</label>
                    <select name="role" style="width:100%; padding:.6rem .85rem; border:1px solid #e2e8f0; border-radius:8px; font-size:.9rem;">
                        <option value="">Tous les rôles</option>
                        <option value="utilisateur"  {{ $role == 'utilisateur'  ? 'selected' : '' }}>Participant</option>
                        <option value="organisateur" {{ $role == 'organisateur' ? 'selected' : '' }}>Organisateur</option>
                        <option value="admin"        {{ $role == 'admin'        ? 'selected' : '' }}>Admin</option>
                        <option value="scanner"      {{ $role == 'scanner'      ? 'selected' : '' }}>Scanner</option>
                    </select>
                </div>
                <button type="submit" style="padding:.65rem 1.25rem; background:#4f46e5; color:#fff; border:none; border-radius:8px; font-weight:700; cursor:pointer;">
                    Filtrer
                </button>
                @if($search || $role)
                    <a href="{{ route('organisateur.utilisateurs') }}" style="padding:.65rem 1rem; background:#f1f5f9; color:#334155; border-radius:8px; font-weight:600; text-decoration:none; font-size:.9rem;">
                        ✕ Réinitialiser
                    </a>
                @endif
            </form>
        </div>

        {{-- Tableau --}}
        <div style="background:#fff; border-radius:16px; border:1px solid #e2e8f0; overflow:hidden; box-shadow:0 1px 8px rgba(0,0,0,.04);">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between;">
                <h6 style="margin:0; font-weight:800; color:#1e293b;">
                    {{ $users->total() }} utilisateur{{ $users->total() > 1 ? 's' : '' }} trouvé{{ $users->total() > 1 ? 's' : '' }}
                </h6>
                <span style="font-size:.8rem; color:#94a3b8;">Page {{ $users->currentPage() }} / {{ $users->lastPage() }}</span>
            </div>

            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f8fafc;">
                            <th style="padding:.9rem 1.25rem; text-align:left; font-size:.78rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.05em; white-space:nowrap;">Utilisateur</th>
                            <th style="padding:.9rem 1rem; text-align:left; font-size:.78rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.05em;">Contact</th>
                            <th style="padding:.9rem 1rem; text-align:left; font-size:.78rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.05em;">Rôle</th>
                            <th style="padding:.9rem 1rem; text-align:left; font-size:.78rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.05em;">Plateforme</th>
                            <th style="padding:.9rem 1rem; text-align:left; font-size:.78rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.05em;">Inscription</th>
                            <th style="padding:.9rem 1rem; text-align:center; font-size:.78rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.05em;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        @php
                            $initiale   = strtoupper(substr($user->nom ?? '?', 0, 1));
                            $isMobile   = $user->tokens()->count() > 0;
                            $roleColors = [
                                'admin'        => ['bg'=>'#fef3c7','color'=>'#92400e','label'=>'Admin'],
                                'organisateur' => ['bg'=>'#dbeafe','color'=>'#1e40af','label'=>'Organisateur'],
                                'scanner'      => ['bg'=>'#cffafe','color'=>'#0e7490','label'=>'Scanner'],
                                'utilisateur'  => ['bg'=>'#dcfce7','color'=>'#166534','label'=>'Participant'],
                            ];
                            $rc = $roleColors[$user->role] ?? ['bg'=>'#f1f5f9','color'=>'#334155','label'=>$user->role];
                        @endphp
                        <tr style="border-top:1px solid #f1f5f9; transition:background .15s;" onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background='transparent'">
                            {{-- Avatar + Nom --}}
                            <td style="padding:1rem 1.25rem;">
                                <div style="display:flex; align-items:center; gap:.75rem;">
                                    <div style="width:40px; height:40px; border-radius:50%; background:#4f46e5; color:#fff; font-weight:700; font-size:1rem; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        {{ $initiale }}
                                    </div>
                                    <div>
                                        <div style="font-weight:700; color:#1e293b; font-size:.9rem;">{{ $user->nom }} {{ $user->prenom }}</div>
                                        <div style="font-size:.78rem; color:#94a3b8;">#{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            {{-- Contact --}}
                            <td style="padding:1rem;">
                                <div style="font-size:.85rem; color:#334155;">{{ $user->email }}</div>
                                @if($user->phone)
                                    <div style="font-size:.78rem; color:#64748b; margin-top:.15rem;">{{ $user->phone }}</div>
                                @endif
                            </td>
                            {{-- Rôle --}}
                            <td style="padding:1rem;">
                                <form method="POST" action="{{ route('organisateur.utilisateur-role', $user->id) }}" style="display:flex; align-items:center; gap:.5rem;">
                                    @csrf
                                    <span style="background:{{ $rc['bg'] }}; color:{{ $rc['color'] }}; padding:.25rem .65rem; border-radius:20px; font-size:.75rem; font-weight:700;">
                                        {{ $rc['label'] }}
                                    </span>
                                    <select name="role" onchange="this.form.submit()" style="font-size:.75rem; border:1px solid #e2e8f0; border-radius:6px; padding:.2rem .4rem; color:#334155; cursor:pointer;">
                                        <option value="">Changer...</option>
                                        <option value="utilisateur">Participant</option>
                                        <option value="organisateur">Organisateur</option>
                                        <option value="scanner">Scanner</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </form>
                            </td>
                            {{-- Plateforme --}}
                            <td style="padding:1rem;">
                                @if($isMobile)
                                    <span style="background:#f5f3ff; color:#6d28d9; padding:.3rem .7rem; border-radius:20px; font-size:.75rem; font-weight:700; display:inline-flex; align-items:center; gap:.3rem;">
                                        📱 Mobile
                                    </span>
                                @else
                                    <span style="background:#f1f5f9; color:#475569; padding:.3rem .7rem; border-radius:20px; font-size:.75rem; font-weight:700; display:inline-flex; align-items:center; gap:.3rem;">
                                        🌐 Web
                                    </span>
                                @endif
                            </td>
                            {{-- Date --}}
                            <td style="padding:1rem;">
                                <div style="font-size:.85rem; color:#334155; font-weight:600;">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </div>
                                <div style="font-size:.75rem; color:#94a3b8;">
                                    {{ $user->created_at->diffForHumans() }}
                                </div>
                            </td>
                            {{-- Actions --}}
                            <td style="padding:1rem; text-align:center;">
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('organisateur.utilisateur-delete', $user->id) }}"
                                          onsubmit="return confirm('Supprimer {{ addslashes($user->nom) }} ? Cette action est irréversible.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background:#fff1f2; color:#e11d48; border:1px solid #fecdd3; border-radius:8px; padding:.4rem .75rem; font-size:.8rem; font-weight:700; cursor:pointer;" title="Supprimer">
                                            🗑️
                                        </button>
                                    </form>
                                @else
                                    <span style="font-size:.75rem; color:#94a3b8;">Vous</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="padding:3rem; text-align:center; color:#94a3b8;">
                                <div style="font-size:3rem; margin-bottom:.5rem;">👤</div>
                                <div style="font-weight:600;">Aucun utilisateur trouvé</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($users->hasPages())
                <div style="padding:1rem 1.5rem; border-top:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between;">
                    <span style="font-size:.85rem; color:#64748b;">
                        Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }} sur {{ $users->total() }}
                    </span>
                    <div style="display:flex; gap:.5rem;">
                        @if($users->onFirstPage())
                            <span style="padding:.4rem .85rem; border-radius:8px; border:1px solid #e2e8f0; color:#cbd5e1; font-size:.85rem;">← Préc.</span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" style="padding:.4rem .85rem; border-radius:8px; border:1px solid #e2e8f0; color:#334155; font-size:.85rem; text-decoration:none; font-weight:600;">← Préc.</a>
                        @endif

                        @foreach($users->getUrlRange(max(1,$users->currentPage()-2), min($users->lastPage(),$users->currentPage()+2)) as $page => $url)
                            <a href="{{ $url }}" style="padding:.4rem .75rem; border-radius:8px; border:1px solid {{ $page == $users->currentPage() ? '#4f46e5' : '#e2e8f0' }}; background:{{ $page == $users->currentPage() ? '#4f46e5' : '#fff' }}; color:{{ $page == $users->currentPage() ? '#fff' : '#334155' }}; font-size:.85rem; text-decoration:none; font-weight:600;">{{ $page }}</a>
                        @endforeach

                        @if($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" style="padding:.4rem .85rem; border-radius:8px; border:1px solid #e2e8f0; color:#334155; font-size:.85rem; text-decoration:none; font-weight:600;">Suiv. →</a>
                        @else
                            <span style="padding:.4rem .85rem; border-radius:8px; border:1px solid #e2e8f0; color:#cbd5e1; font-size:.85rem;">Suiv. →</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
