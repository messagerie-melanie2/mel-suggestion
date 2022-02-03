<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vote;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;

class VotesController extends BaseController
{
    public function __construct()
    {
        $this->user = new User();
        $this->user->__setfonction();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
    
     */
    public function create(Request $request)
    {

        $date = $request->input('today_date');        
        $id_sugestion = $request->input('id');
        $vote = new Vote;
        $vote->user_email = $this->user->user;
        $vote->voting_day = $date;
        $vote->sugestion_id = $request->id_sugestion;
        $vote->save();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroybysugestion($id)
    {
        $vote = Vote::where('sugestion_id', `$id`)->get();
        $vote->delete();
    }
}
