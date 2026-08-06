<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SponsorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $evenementid = Evenement::orderBy('date', 'desc')->get();
        $selectedEvenementId = $request->input('evenement_id');
        $evenementActuel = null;
        $sponsorsExistants = collect();

        if ($selectedEvenementId) {
            $evenementActuel = Evenement::with('sponsors')->find($selectedEvenementId);
            if ($evenementActuel) {
                $sponsorsExistants = $evenementActuel->sponsors;
            }
        }

        $isWizard = $request->has('wizard') || !empty($selectedEvenementId);

        return view('organisateur.sponsor', compact('evenementid', 'selectedEvenementId', 'evenementActuel', 'sponsorsExistants', 'isWizard'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'evenement_id' => 'required|exists:evenements,id',
            'nom' => 'nullable|string|max:255',
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'lien_web' => 'nullable|string|url',
            'sponsors' => 'nullable|array',
            'sponsors.*.nom' => 'nullable|string|max:255',
            'sponsors.*.lien_web' => 'nullable|string|url',
        ], [
            'logo.mimes' => "Le fichier doit être une image au format jpeg, png, jpg, gif ou svg.",
            'lien_web.url' => "Le lien web doit être une URL valide.",
            'evenement_id.required' => "Vous devez sélectionner un événement.",
            'evenement_id.exists' => "L'événement sélectionné n'existe pas.",
        ]);

        $evenementId = $validatedData['evenement_id'];
        $countAdded = 0;

        try {
            // Traitement du tableau de sponsors dynamiques
            if ($request->has('sponsors') && is_array($request->input('sponsors')) && count($request->input('sponsors')) > 0) {
                foreach ($request->input('sponsors') as $index => $sItem) {
                    if (empty($sItem['nom'])) continue;

                    $sponsor = new Sponsor();
                    $sponsor->nom = trim($sItem['nom']);
                    $sponsor->lien_web = $sItem['lien_web'] ?? '';
                    $sponsor->evenement_id = $evenementId;

                    if ($request->hasFile("sponsors.{$index}.logo")) {
                        $photoPath = $request->file("sponsors.{$index}.logo")->store('evenement/sponsors', 'public');
                        $sponsor->logo = basename($photoPath);
                    } else {
                        $sponsor->logo = '';
                    }

                    $sponsor->save();
                    $countAdded++;
                }
            } elseif (!empty($validatedData['nom'])) {
                // Traitement d'une entrée unique classique
                $sponsor = new Sponsor();
                $sponsor->nom = trim($validatedData['nom']);

                if ($request->hasFile('logo')) {
                    $photoPath = $request->file('logo')->store('evenement/sponsors', 'public');
                    $sponsor->logo = basename($photoPath);
                } else {
                    $sponsor->logo = '';
                }

                $sponsor->lien_web = $validatedData['lien_web'] ?? '';
                $sponsor->evenement_id = $evenementId;
                $sponsor->save();
                $countAdded++;
            }

            $redirectParams = ['evenement_id' => $evenementId];
            if ($request->has('wizard')) {
                $redirectParams['wizard'] = $request->input('wizard');
            }

            if ($request->input('action') === 'finish') {
                return redirect()->route('organisateur.detail', ['id' => $evenementId])
                                ->with('success', 'Félicitations ! Événement, billets et sponsors configurés avec succès.');
            }

            return redirect()->route('organisateur.sponsor-form', $redirectParams)
                            ->with('success', $countAdded > 0 ? "$countAdded sponsor(s) inséré(s) avec succès !" : "Configuration enregistrée.");
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du sponsor : ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la création du sponsor : ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sponsors = Sponsor::findOrFail($id);
        return view('organisateur.modifier_sponsor', compact('sponsors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'logo' => 'nullable|string', // Ou file si uploadé
            'lien_web' => 'nullable|string|url',
            'evenement_id' => 'required|exists:evenements,id'
        ]);

        try {
            $sponsor = Sponsor::findOrFail($id);
            $sponsor->nom = $validatedData['nom'];
            $sponsor->logo = $validatedData['logo'] ?? $sponsor->logo;
            $sponsor->lien_web = $validatedData['lien_web'] ?? '';
            $sponsor->evenement_id = $validatedData['evenement_id'];
            $sponsor->save();

            return redirect()->route('organisateur.dashboard')->with('success', 'Sponsor mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du sponsor : ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $sponsor = Sponsor::findOrFail($id);
            $sponsor->delete();
            return redirect()->back()->with('success', 'Sponsor supprimé avec succès de cet événement.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression du sponsor : ' . $e->getMessage());
        }
    }
}
