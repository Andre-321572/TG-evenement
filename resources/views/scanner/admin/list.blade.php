@extends('layouts.Obase')

@section('content')
<div class="p-4 sm:p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <div>
            <h2 class="text-xl font-bold" style="color:#0f172a;">Comptes Scanners</h2>
            <p style="color:#64748b; font-size:.85rem;">Gérez les agents autorisés à scanner les billets.</p>
        </div>
        <a href="{{ route('organisateur.scanner-create') }}"
           style="background:#4f46e5; color:#ffffff; border:none; border-radius:8px;
                  padding:.6rem 1.2rem; font-weight:700; font-size:.9rem; text-decoration:none;
                  display:inline-flex; align-items:center; gap:.4rem;">
            <i class="fas fa-plus"></i> Nouveau scanner
        </a>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
    <div style="background:#dcfce7; border:1px solid #86efac; color:#166534; padding:.75rem 1rem;
                border-radius:8px; margin-bottom:1.2rem; font-size:.9rem;">
        {{ session('success') }}
    </div>
    @endif

    {{-- Table --}}
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="table-container">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:rgba(79,70,229,0.05); border-bottom:1px solid rgba(59,130,246,0.08);">
                        <th style="text-align:left; padding:.75rem 1rem; color:#475569; font-size:.75rem;
                                   text-transform:uppercase; letter-spacing:.08em; font-weight:700;">Nom</th>
                        <th style="text-align:left; padding:.75rem 1rem; color:#475569; font-size:.75rem;
                                   text-transform:uppercase; letter-spacing:.08em; font-weight:700;">Email</th>
                        <th style="text-align:left; padding:.75rem 1rem; color:#475569; font-size:.75rem;
                                   text-transform:uppercase; letter-spacing:.08em; font-weight:700; white-space:nowrap;">Téléphone</th>
                        <th style="text-align:left; padding:.75rem 1rem; color:#475569; font-size:.75rem;
                                   text-transform:uppercase; letter-spacing:.08em; font-weight:700; white-space:nowrap;">Créé le</th>
                        <th style="text-align:center; padding:.75rem 1rem; color:#475569; font-size:.75rem;
                                   text-transform:uppercase; letter-spacing:.08em; font-weight:700;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scanners as $scanner)
                    <tr style="border-bottom:1px solid rgba(59,130,246,0.05);">
                        <td style="padding:.85rem 1rem; color:#0f172a; font-weight:600; font-size:.9rem;">
                            {{ $scanner->prenom }} {{ $scanner->nom }}
                        </td>
                        <td style="padding:.85rem 1rem; color:#475569; font-size:.88rem;">
                            {{ $scanner->email }}
                        </td>
                        <td style="padding:.85rem 1rem; color:#475569; font-size:.88rem;">
                            {{ $scanner->phone }}
                        </td>
                        <td style="padding:.85rem 1rem; color:#64748b; font-size:.82rem; white-space:nowrap;">
                            {{ $scanner->created_at->format('d/m/Y') }}
                        </td>
                        <td style="padding:.85rem 1rem; text-align:center;">
                            <form method="POST" action="{{ route('organisateur.scanner-delete', $scanner->id) }}"
                                  onsubmit="return confirm('Révoquer l\'accès scanner de {{ $scanner->prenom }} {{ $scanner->nom }} ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        style="background:rgba(239,68,68,0.1); color:#b91c1c; border:1px solid rgba(239,68,68,0.2);
                                               border-radius:6px; padding:.35rem .8rem; font-size:.8rem; font-weight:600; cursor:pointer;">
                                    Révoquer
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding:2rem; text-align:center; color:#94a3b8; font-size:.9rem;">
                            Aucun compte scanner pour l'instant.
                            <a href="{{ route('organisateur.scanner-create') }}"
                               style="color:#4f46e5; font-weight:600; margin-left:.4rem;">Créer le premier</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
