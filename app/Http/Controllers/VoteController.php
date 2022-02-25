<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vote;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;

class VotesController extends BaseController
/**
 * J'initialise mon objet User qui correspondra à l'utilisateur connecter.
 * Pour ainsi pouvoir utiliser ces données dans les functions
 */
{
    public function __construct()
    {
        $this->user = new User();
     
   
    }
    /**
     * La function create permet de creer un vote.
     * il prend en paramètre ce qu'envoie la requête api faite par le fronte.
     * IL récupère et vérifie les données envoyer par la requête api t-elle que la date du jour  et l'id qui correspond à l'id de la suggestion sur laquelle il à voter
     * Je déclare un nouvel objet Vote , avec lequelle je définir ces atributs
     * J'execute la requête de création du vote
     */
    public function Creat(Request $request)
    {

        $date = $request->input('today_date');        
        $id_sugestion = $request->input('id');
        $vote = new Vote;
        $vote->user_email = $this->user->user;
        $vote->voting_day = $date;
        $vote->sugestion_id = $id_sugestion;
        $vote->save();
    }

    /**
     * Cette fonction ce déclanche des qu"on à besoin de suprimer un vote
     * C'est à dire lors de la supresion d'une suggestion 
     * Elle prend un id qui correspond à l'id de la suggestion 
     * J'initialise tous les votes dont suggestion-id est égale à suggestion id
     * je lance la supression de ces vote
     */
    public function destroybysugestion(Request $request)
    {
        $id = $request->input('suggestion_id');
        $vote = Vote::where('sugestion_id', `$id`)->get();
        $vote->delete();
    }
}
