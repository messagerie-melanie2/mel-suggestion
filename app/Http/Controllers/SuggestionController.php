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
use PhpParser\Builder\Function_;

class SuggestionController extends  BaseController
{
    /**
     * Initialise un utilisateur connecter pour pouvoir utiliser
     */
    public function __construct()
    {
        $this->user = new User();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * 
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
            'created_date' => $dateto,

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
    public function Detailsugestion($id)
    {
        $sugestion = Suggestion::where('id', `$id`)->get();
        return response()->json($sugestion);
    }
    public function Recupsugestion()
    {
    
        $LesSugestions = new Collection();
        $Sugestions = Suggestion::where('id', $this->user->instance)->get();
        $Listvote = new Collection();
        $list = [];
        $nombre = 0;
        foreach ($Sugestions as $Suggestion) {
            $this->$list->array_push($Suggestion->id);
        }
        
        $result = Suggestion::select('SELECT * FROM votes where suggestion_id IN', $this->$list);
        foreach ($Sugestions as $Suggestion) {
            $AdaptSugestion = new Suggestionsend();
            foreach ($result as $votes) {
                if ($votes->sugestion_id == $Suggestion->id) {
                    $Listvote->push($votes);
                }
                if ($votes->user_email === $this->user->user) {
                    $nombre = $nombre + 1;
                } else {
                    $nombre = $nombre;
                }
                if ($nombre == 0) {
                    $AdaptSugestion->etvote = 'non';
                } else {
                    $AdaptSugestion->etvote = 'oui';
                }
               
            }
            $AdaptSugestion->nbvote = Count($Listvote);
            $AdaptSugestion->title = $Suggestion->title;
            $AdaptSugestion->description = $Suggestion->description;
            $AdaptSugestion->start_date = $Suggestion->created_date;

            $AdaptSugestion->state = $Suggestion->state;
            if ($Suggestion->User == $this->user->user) {
                $AdaptSugestion->appartient = 'oui';
            } else {
                $AdaptSugestion->appartient = 'non';
            }
            if ($this->user->fonction == '1') {
                $AdaptSugestion->createur = $Sugestions->user_email;
            }

            $LesSugestions->push($AdaptSugestion);
           
        }
        return response()->json($LesSugestions);
    }
}
