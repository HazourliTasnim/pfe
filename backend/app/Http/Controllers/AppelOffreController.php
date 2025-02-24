<?php

namespace App\Http\Controllers;

use App\Models\AppelOffre;
use Illuminate\Http\Request;

class AppelOffreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index()
{
    return response()->json(AppelOffre::with('entreprise')->get(), 200)

        ->header("Access-Control-Allow-Origin", "http://localhost:5173")
        ->header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS")
        ->header("Access-Control-Allow-Headers", "Content-Type, Authorization")
        ->header("Access-Control-Allow-Credentials", "true");
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'entreprise_id'   => 'required|exists:entreprises,id',
            'langue'          => 'required|string',
            'etat_offre'      => 'required|string',
            'wilaya'          => 'required|string',
            'numero_offre'    => 'required|string|unique:appels_d_offres,numero_offre',
            'titre_offre'     => 'required|string',
            'type_offre'      => 'required|string',
            'niveau'          => 'required|in:National,International',
            'date_publication'=> 'required|date',
            'date_limite'     => 'required|date|after_or_equal:date_publication',
            'date_prorogation'=> 'nullable|date|after_or_equal:date_limite',
            'description'     => 'required|string',
            'document_pdf'    => 'nullable|file|mimes:pdf|max:2048',
            'etat'            => 'required|string',
        ]);
    
        $documentPath = null;
        if ($request->hasFile('document_pdf')) {
            $documentPath = $request->file('document_pdf')->store('documents');
        }
    
        $appelOffre = AppelOffre::create([
            'entreprise_id'   => $request->entreprise_id, // Ajout de la clé étrangère
            'langue'          => $request->langue,
            'etat_offre'      => $request->etat_offre,
            'wilaya'          => $request->wilaya,
            'numero_offre'    => $request->numero_offre,
            'titre_offre'     => $request->titre_offre,
            'type_offre'      => $request->type_offre,
            'niveau'          => $request->niveau,
            'date_publication'=> $request->date_publication,
            'date_limite'     => $request->date_limite,
            'date_prorogation'=> $request->date_prorogation,
            'description'     => $request->description,
            'document_pdf'    => $documentPath,
            'etat'            => $request->etat,
        ]);
    
        return response()->json([
            'message' => "Appel d'offre créé avec succès",
            'data' => $appelOffre,
        ], 201);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(AppelOffre $appelOffre)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AppelOffre $appelOffre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AppelOffre $appelOffre)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AppelOffre $appelOffre)
    {
        //
    }
}
