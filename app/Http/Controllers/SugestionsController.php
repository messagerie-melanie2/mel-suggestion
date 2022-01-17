<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sugestion;
use Ramsey\Uuid\Type\Integer;

class SugestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Sugestions = Sugestion::all();
        return response()->json($Sugestions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user=$_SESSION['email'];
        if ($request->type === 'Moderateur') {
            $etat = '2';
        } else {
            $etat = '1';
        }

        $sugestion = new Sugestion;
        $sugestion->title = $request->titre;
        $sugestion->Description = $request->description;
        $sugestion->User = "louka.ruiz@orange.fr";
        $sugestion->date_creat = $request->date;
        $sugestion->etat = $etat;
        $sugestion->Url = 'https://lab-im.joona.fr/channel/evenements';
        $sugestion->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sugestion = Sugestion::where('auteur', `$id`)->get();
        $sugestion->update([
            "title" => $request->title,
            "Description" => $request->Description,
            "etat" => $request->etat,
        ]);
    }
    public function updateetat(Request $request, $id)
    {
        $sugestion = Sugestion::where('auteur', `$id`)->get();
        $sugestion->update([

            "etat" => $request->etat,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sugestion = Sugestion::where('auteur', `$id`)->get();
        $sugestion->delete();
    }
    public function getnonvalider()
    {
        $Sugestions = Sugestion::where('etat', `A valider`)->get();
        return response()->json($Sugestions);
    }
}
