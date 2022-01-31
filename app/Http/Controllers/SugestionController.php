<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sugestion;
use Ramsey\Uuid\Type\Integer;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\Constraint\Count;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;

$constante=new User();
$constante= User::setfonction();

class SugestionsController extends  BaseController
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
        
        $type = $request->input('type');
        $title = $request->input('title');
        $description = $request->input('description');
        $dateto = $request->input('date');


        $user = $_SESSION['email'];
        if ($type == 'Moderateur') {
            $state = '2';
        } else {
            $state = '1';
        }

        $sugestion = new Sugestion;
        $sugestion->Title = $title;
        $sugestion->Description = $description;
        $sugestion->User = "louka.ruiz@orange.fr";
        $sugestion->start_date = $dateto;
        $sugestion->state = $state;
        $sugestion->instance = '';
        $sugestion->date_update = $request->dateto;
        $sugestion->save();
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function UpdateSugestion(Request $request, $id)
    {
        $title = $request->input('title');
        $Description = $request->input('Description');
        $dateto = $request->input('date');
        $state = $request->input('state');
        $sugestion = Sugestion::where('id', `$id`)->get();
        $sugestion->update([
            "title" => $title,
            "Description" => $Description,
            "state" => $state,
            "date_update" => $dateto
        ]);
    }
    public function UpdateState(Request $request, $id)
    {
        $sugestion = Sugestion::where('Id', `$id`)->get();
        $sugestion->update([

            "state" => $request->state,
        ]);
        if ($request->state == '2') {
            $vote = new Vote;
            $vote->email = $request->connected;
            $vote->voting_day = $request->date;
            $vote->sugestion_id = $sugestion->id;
            $vote->save();
        }
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
    public function Defineuser()
    {
        $instance = config('Moderateur.instance');
        $user = $_SESSION['email'];
        $listemoderateur = [];
        $listemoderateur = config('Moderateur.Moderateur');
        if (in_array($user, $listemoderateur)) {
            $type = "moderateur";
        } else {
            $type = " User";
        }

        $valeur = [$instance, $user, $type];
        return response()->json($valeur);
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
    public $date_update;
}
