<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\TicketDesign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketDesignController extends Controller
{
    /**
     * Afficher l'éditeur de design pour un événement
     */
    public function edit($evenementId)
    {
        $evenement = Evenement::with(['billets', 'ticketDesign'])->findOrFail($evenementId);
        $design    = $evenement->ticketDesign ?? new TicketDesign(['evenement_id' => $evenementId]);

        $policesDisponibles = [
            'Outfit'              => 'Outfit (Moderne)',
            'Montserrat'          => 'Montserrat (Professionnel)',
            'Playfair Display'    => 'Playfair Display (Élégant)',
            'Poppins'             => 'Poppins (Arrondi)',
            'Raleway'             => 'Raleway (Mince)',
            'Dancing Script'      => 'Dancing Script (Script)',
            'Bebas Neue'          => 'Bebas Neue (Impact)',
            'Oswald'              => 'Oswald (Condensé)',
            'Roboto'              => 'Roboto (Classique)',
            'Lato'                => 'Lato (Sobre)',
        ];

        return view('organisateur.ticket-design', compact('evenement', 'design', 'policesDisponibles'));
    }

    /**
     * Sauvegarder ou mettre à jour le design
     */
    public function store(Request $request, $evenementId)
    {
        $evenement = Evenement::findOrFail($evenementId);

        $validated = $request->validate([
            'fond_type'     => 'required|in:couleur,degrade,image',
            'fond_couleur1' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'fond_couleur2' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'fond_opacite'  => 'required|integer|min:0|max:100',
            'police'        => 'required|string|max:100',
            'couleur_titre' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'couleur_texte' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'template'      => 'required|in:classique,moderne,festif',
            'logo'          => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'fond_image'    => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
        ]);

        $design = TicketDesign::firstOrNew(['evenement_id' => $evenementId]);
        $design->fill($validated);
        $design->evenement_id = $evenementId;

        // Upload logo
        if ($request->hasFile('logo')) {
            if ($design->logo) {
                Storage::disk('public')->delete('ticket_designs/logos/' . $design->logo);
            }
            $design->logo = $request->file('logo')->store('ticket_designs/logos', 'public');
            $design->logo = basename($design->logo);
        }

        // Upload image de fond
        if ($request->hasFile('fond_image')) {
            if ($design->fond_image) {
                Storage::disk('public')->delete('ticket_designs/fonds/' . $design->fond_image);
            }
            $design->fond_image = $request->file('fond_image')->store('ticket_designs/fonds', 'public');
            $design->fond_image = basename($design->fond_image);
        }

        // Reset fond_image si type != image
        if ($validated['fond_type'] !== 'image') {
            // Garder l'image existante mais ne pas l'utiliser
        }

        $design->save();

        return redirect()->back()->with('success', 'Design du ticket sauvegardé avec succès !');
    }

    /**
     * Supprimer le logo
     */
    public function deleteLogo($evenementId)
    {
        $design = TicketDesign::where('evenement_id', $evenementId)->firstOrFail();
        if ($design->logo) {
            Storage::disk('public')->delete('ticket_designs/logos/' . $design->logo);
            $design->logo = null;
            $design->save();
        }
        return response()->json(['success' => true]);
    }

    /**
     * Supprimer l'image de fond
     */
    public function deleteFond($evenementId)
    {
        $design = TicketDesign::where('evenement_id', $evenementId)->firstOrFail();
        if ($design->fond_image) {
            Storage::disk('public')->delete('ticket_designs/fonds/' . $design->fond_image);
            $design->fond_image = null;
            $design->save();
        }
        return response()->json(['success' => true]);
    }
}
