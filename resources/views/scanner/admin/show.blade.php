@extends('layouts.Obase')

@section('content')
<div class="p-4 sm:p-6">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('organisateur.scanners') }}"
           style="color:#4f46e5; font-size:.85rem; font-weight:600; text-decoration:none;
                  display:inline-flex; align-items:center; gap:.3rem; margin-bottom:.8rem;">
            <i class="fas fa-arrow-left" style="font-size:.75rem;"></i> Retour à la liste
        </a>
        <h2 class="text-xl font-bold" style="color:#0f172a;">Profil de l'agent scanner</h2>
        <p style="color:#64748b; font-size:.85rem;">Consultez les informations et l'historique des scans effectués par cet agent.</p>
    </div>

    <div style="display:grid; grid-template-columns:1fr; gap:1.5rem; align-items:start; margin-bottom:2rem;" class="lg:grid-cols-3">
        
        {{-- Profile Card --}}
        <div class="glass-card rounded-2xl p-6 lg:col-span-1">
            <div style="text-align:center; margin-bottom:1.5rem;">
                <div style="width:72px; height:72px; border-radius:50%; background:rgba(79,70,229,0.1);
                            color:#4f46e5; display:inline-flex; align-items:center; justify-content:center;
                            font-size:1.8rem; font-weight:700; margin-bottom:.75rem;">
                    {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                </div>
                <h3 style="font-size:1.15rem; font-weight:700; color:#0f172a; margin:0 0 .25rem 0;">{{ $user->prenom }} {{ $user->nom }}</h3>
                <span style="background:rgba(79,70,229,0.08); color:#4f46e5; padding:.2rem .6rem; border-radius:99px;
                             font-size:.75rem; font-weight:700; text-transform:uppercase;">Agent Scanner</span>
            </div>

            <div style="border-top:1px solid #f1f5f9; padding-top:1rem; display:flex; flex-direction:column; gap:.85rem;">
                <div>
                    <span style="display:block; font-size:.75rem; color:#64748b; text-transform:uppercase; font-weight:700; letter-spacing:.05em;">Email</span>
                    <span style="font-size:.9rem; color:#334155; font-weight:600;">{{ $user->email }}</span>
                </div>
                <div>
                    <span style="display:block; font-size:.75rem; color:#64748b; text-transform:uppercase; font-weight:700; letter-spacing:.05em;">Téléphone</span>
                    <span style="font-size:.9rem; color:#334155; font-weight:600;">{{ $user->phone }}</span>
                </div>
                <div>
                    <span style="display:block; font-size:.75rem; color:#64748b; text-transform:uppercase; font-weight:700; letter-spacing:.05em;">Créé le</span>
                    <span style="font-size:.9rem; color:#334155; font-weight:600;">{{ $user->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div>
                    <span style="display:block; font-size:.75rem; color:#64748b; text-transform:uppercase; font-weight:700; letter-spacing:.05em;">Événement assigné</span>
                    <span style="font-size:.9rem; color:#1e3a8a; font-weight:700;">
                        @if($user->assignedEvenement)
                            {{ $user->assignedEvenement->titre }}
                        @else
                            <span style="color:#94a3b8; font-style:italic;">Tous les événements (Aucune restriction)</span>
                        @endif
                    </span>
                </div>
            </div>

            <div style="margin-top:1.5rem; display:flex; gap:.5rem;">
                <a href="{{ route('organisateur.scanner-edit', $user->id) }}"
                   style="flex:1; background:#4f46e5; color:#fff; text-align:center; padding:.5rem;
                          border-radius:8px; font-weight:600; font-size:.85rem; text-decoration:none; display:block;">
                    Modifier le profil
                </a>
            </div>
        </div>

        {{-- Scans Log List --}}
        <div class="glass-card rounded-2xl p-6 lg:col-span-2">
            <h3 style="font-size:1.1rem; font-weight:700; color:#0f172a; margin:0 0 1rem 0;">Historique des scans ({{ $scans->total() }})</h3>
            
            <div class="table-container">
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="background:rgba(79,70,229,0.03); border-bottom:1px solid rgba(59,130,246,0.05);">
                            <th style="text-align:left; padding:.65rem .75rem; color:#475569; font-size:.7rem; text-transform:uppercase; font-weight:700;">Code</th>
                            <th style="text-align:left; padding:.65rem .75rem; color:#475569; font-size:.7rem; text-transform:uppercase; font-weight:700;">Événement</th>
                            <th style="text-align:left; padding:.65rem .75rem; color:#475569; font-size:.7rem; text-transform:uppercase; font-weight:700;">Billet</th>
                            <th style="text-align:left; padding:.65rem .75rem; color:#475569; font-size:.7rem; text-transform:uppercase; font-weight:700;">Acheteur</th>
                            <th style="text-align:right; padding:.65rem .75rem; color:#475569; font-size:.7rem; text-transform:uppercase; font-weight:700;">Scanné le</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($scans as $scan)
                        <tr style="border-bottom:1px solid rgba(59,130,246,0.03);">
                            <td style="padding:.75rem; color:#0f172a; font-family:monospace; font-weight:600; font-size:.85rem;">
                                {{ $scan->code }}
                            </td>
                            <td style="padding:.75rem; color:#334155; font-size:.82rem; font-weight:600;">
                                {{ $scan->evenement?->titre }}
                            </td>
                            <td style="padding:.75rem; color:#475569; font-size:.8rem;">
                                {{ $scan->billet?->type }}
                            </td>
                            <td style="padding:.75rem; color:#475569; font-size:.8rem;">
                                <span style="display:block; font-weight:600;">{{ $scan->buyer_name }}</span>
                                <span style="font-size:.72rem; color:#64748b;">{{ $scan->buyer_email }}</span>
                            </td>
                            <td style="padding:.75rem; color:#64748b; font-size:.78rem; text-align:right; white-space:nowrap;">
                                {{ $scan->scanned_at ? $scan->scanned_at->format('d/m/Y à H:i:s') : 'N/A' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="padding:3rem; text-align:center; color:#94a3b8; font-size:.9rem; font-style:italic;">
                                Aucun scan enregistré pour cet agent.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top:1rem;">
                {{ $scans->links() }}
            </div>
        </div>

    </div>

</div>
@endsection
