<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Suggestion;
use Ramsey\Uuid\Type\Integer;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\Constraint\Count;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use App\Models\Suggestionsend;



class SuggestionController extends  BaseController
{
    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /* public function index()
    {
        $Sugestions = Suggestion::all();
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
        $title = $request->input('title');
        $description = $request->input('description');
        $dateto = $request->input('date');

        if ($this->user->fonction == 'Moderateur') {
            $state = '2';
        } else {
            $state = '1';
        }


        $sugestion = new Suggestion;
        $sugestion->title = $title;
        $sugestion->description = $description;
        $sugestion->user_email = $this->user->user;
        $sugestion->created_date = $dateto;
        $sugestion->state = $state;
        $sugestion->instance = $this->user->instance;
        $sugestion->update_date = $dateto;
        $sugestion->save();
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function UpdateSugestion(Request $request)
    {
        $idsuggestion = $request->input('id');
        $title = $request->input('title');
        $Description = $request->input('Description');
        $dateto = $request->input('date');
        $state = $request->input('state');
        $sugestion = Suggestion::where('id', `$idsuggestion`)->get();
        $sugestion->update([
            "title" => $title,
            "description" => $Description,
            "state" => $state,
            "date_update" => $dateto,
            'created_date'=>$dateto,

        ]);
    }
    public function UpdateState(Request $request, $id)
    {
        $sugestion = Suggestion::where('Id', `$id`)->get();
        $sugestion->update([

            "state" => $request->state,
        ]);
        if ($request->state == '2') {
            $vote = new Vote;
            $vote->email = $this->user->user;
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
        $sugestion = Suggestion::where('id', `$id`)->get();
        $sugestion->delete();
    }
    public function Recupsugestion($type)
    {

        $AdaptSugestion = new Suggestionsend();
        $LesSugestions = new Collection();
        if ($this->user->fonction == '1') {


            $nombre = 0;


            $Sugestions = Suggestion::where('id', $this->user->instance)->get();
            ;
            foreach ($Sugestions as $Suggestion) {

                $vote = Vote::where('id', `$Suggestion->id`)->get();
                foreach ($vote as $votes) {
                    if ($votes->user === $this->user->user) {
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
            $AdaptSugestion->title = $Suggestion->title;
            $AdaptSugestion->description = $Suggestion->description;
            $AdaptSugestion->start_date = $Suggestion->start_date;

            $AdaptSugestion->state = $Suggestion->state;
            if ($Suggestion->User == $this->user->user) {
                $AdaptSugestion->appartient = 'oui';
            } else {
                $AdaptSugestion->appartient = 'non';
            }

            $LesSugestions->push($AdaptSugestion);
        } else {
            $AdaptSugestion = new Suggestionsend();
            $user = $_SESSION['email'];
            $nombre = 0;


            $Sugestions = Suggestion::where('id', $this->user->instance)->get();
            foreach ($Sugestions as $Suggestion) {
                $vote = Vote::where('id', `$Suggestion->id`)->get();
                foreach ($vote as $votes) {
                    if ($votes->user_email === $user) {
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
            $AdaptSugestion->title = $Suggestion->title;
            $AdaptSugestion->description = $Suggestion->description;
            $AdaptSugestion->start_date = $Suggestion->created_date;
            $AdaptSugestion->state = $Suggestion->state;
            $AdaptSugestion->$user = $this->user->user;
            $AdaptSugestion->$user = $this->user->instance;
            $LesSugestions->push($AdaptSugestion);
        }
        return response()->json($LesSugestions);
    }
    public function Moderateurorparticipent()
    {

        $user = $_SESSION['email'];
        $listemoderateur = [];
        $listemoderateur = config('Moderateur.Moderateur');
        if (in_array($user, $listemoderateur)) {
            $type = 1;
        } else {
            $type = 2;
        }
        return response()->json($type);
    }
    public function Detailsugestion($id){
        $sugestion = Suggestion::where('id', `$id`)->get();
        return response()->json($sugestion);
    }
}
