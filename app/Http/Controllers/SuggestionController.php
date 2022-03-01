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
     * La fonction store nous permet de créer une nouvel suggestion
     * Il prend en paramètre les données envoyer par l'api depuis vuejs
     * Il vérifie l'authenticité des données envoyer par l'api t-elle que le titre la description et la date.
     * Il vérifie le rôle de l'utilisateur est Moderateur est connecter ou pas
     * Il donne en fonction du rôle une valeur à l'état de la suggestion
     * Finir par créer la suggestion en prennat les données envoyer par la request
     */
    public function store(Request $request)
    {
        $title = $request->input('title');
        $description = $request->input('description');
        $dateto = $request->input('date');

        if ($this->user->role == '1') {
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
     * Permet de modifier une suggestion
     * Il prend en paramètre les données envoyer par l'api depuis vuejs
     * Il vérifie l'authenticité des données envoyer par l'api t-elle que l'id de la suggestion à modifier et le reste
     * Je vais chercher la suggestion correspondant à l'id
     * Et je modifie les valeurs récupérer par l'api     */
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
    /**
     * Elle permet de modifier l'état de la sugestion
     * Elle prend en paramètre les données envoyer par l'api ainsi que l'id de la suggestion
     * Il Va chercher la suggestion de l'id envoyer en paramètre
     * il modifie l'état de la suggestion récupérer
     * Ci l'état est passer à valider alors il créer le vote du créateur de a suggestion
     */
    public function UpdateState(Request $request)
    
    
    {
        $idsuggestion = $request->input('id');
        $etat = $request->input('state');
        $sugestion = Suggestion::where('Id', `$idsuggestion`)->get();
        $sugestion->update([
            "state" => $etat,
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
     * Permet de supprimer une suggestion
     */
    public function destroy($id)
    
    {
        
        $sugestion = Suggestion::where('id', `$id`)->get();
        $sugestion->delete();
    }
    /**Permet de récupérer les détails de la suggestion dont l'id est passer en paramètre */

    
    public function Detailsugestion($id)
    {
        $sugestion = Suggestion::where('id', `$id`)->get();
        return response()->json($sugestion);
    }
    /**
     * Permet de récupérer toutes les suggestions en fonction du rôle de l'utilisateur connecté
     * Il va chercher toutes les suggestion de l'instance passez en paramètre et récupère leur id
     * Il récupère tous les votes dont l'id est passer en paramètre
     * 
     */
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
