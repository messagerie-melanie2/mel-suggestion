<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sugestion;
use Ramsey\Uuid\Type\Integer;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\Constraint\Count;


class SugestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /* public function index()
    {
        $Sugestions = Sugestion::all();
        return response()->json($Sugestions);
    }*/

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
        $user = $_SESSION['email'];
        if ($request->type === 'Moderateur') {
            $state = '2';
        } else {
            $state = '1';
        }

        $sugestion = new Sugestion;
        $sugestion->id=$request->id;
        $sugestion->Title = $request->title;
        $sugestion->Description = $request->description;
        $sugestion->User = "louka.ruiz@orange.fr";
        $sugestion->start_date = $request->date;
        $sugestion->state = $state;
        $sugestion->instance = '';
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
            "state" => $request->state,
        ]);
    }
    public function updateetat(Request $request, $id)
    {
        $sugestion = Sugestion::where('auteur', `$id`)->get();
        $sugestion->update([

            "state" => $request->state,
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
        $Sugestions = Sugestion::where('state', `A valider`)->get();
        return response()->json($Sugestions);
    }
    public function Recupsugestion($type)
    {
        $AdaptSugestion = new lsugestion();
        $LesSugestions = new Collection();
        if ($type == 'user') {

            $user = $_SESSION['email'];
            $nombre = 0;


            $Sugestions = Sugestion::all();
            foreach ($Sugestions as $Sugestion) {
                $vote = Vote::where('id', `$Sugestion->id`)->get();
                foreach ($vote as $votes) {
                    if ($votes->user === $user) {
                        $nombre = $nombre + 1;
                    } else {
                        $nombre = $nombre;
                    }
                    $AdaptSugestion->nbvote = Count($vote);
                }
                if ($nombre == 0) {
                    $AdaptSugestion->etvote = 'non';
                } else {
                    $AdaptSugestion->etvote = 'oui';
                }
            }
            $AdaptSugestion->title = $Sugestion->title;
            $AdaptSugestion->description = $Sugestion->description;
            $AdaptSugestion->start_date = $Sugestion->start_date;
            $AdaptSugestion->state = $Sugestion->state;
            $LesSugestions->push($AdaptSugestion);
        } else {
            $AdaptSugestion = new lsugestion();
            $user = $_SESSION['email'];
            $nombre = 0;


            $Sugestions = Sugestion::all();
            foreach ($Sugestions as $Sugestion) {
                $vote = Vote::where('id', `$Sugestion->id`)->get();
                foreach ($vote as $votes) {
                    if ($votes->user === $user) {
                        $nombre = $nombre + 1;
                    } else {
                        $nombre = $nombre;
                    }
                    $AdaptSugestion->number_votes = Count($vote);
                }
                if ($nombre == 0) {
                    $AdaptSugestion->voted = 'non';
                } else {
                    $AdaptSugestion->voted = 'oui';
                }
            }
            $AdaptSugestion->title = $Sugestion->title;
            $AdaptSugestion->description = $Sugestion->description;
            $AdaptSugestion->start_date = $Sugestion->start_date;
            $AdaptSugestion->state = $Sugestion->state;
            $AdaptSugestion->$user = $Sugestion->$user;
            $LesSugestions->push($AdaptSugestion);
        }
        return response()->json($LesSugestions);
    }
}
class lsugestion
{
    public $title;
    public $description;
    public $start_date;
    public $state;
    public $voted;
    public $number_votes;
    public $user = '';
}
