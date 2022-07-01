<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VoteController extends Controller
{
  public function __construct()
  {
    if (env('APP_ENV') == "development") {
      Session::put('email', 'Arnaud@goubier.fr');
    } else {
      session_id($_COOKIE['roundcube_sessid']);
      session_start();
      Session::put('email', $_SESSION['email']);
    }
  }
  /**
   * Display a listing of the votes.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $votes = Vote::all();

    return response()->json($votes);
  }

  /**
   * Store a newly vote in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $request->validate([
      'suggestion_id' => 'required'
    ]);

    $newVote = new Vote([
      'user_email' => Session::get('email'),
      'suggestion_id' => $request->get('suggestion_id')
    ]);

    $newVote->save();

    return response()->json($newVote);
  }

  /**
   * Display the specified vote.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $vote = Vote::findOrFail($id);
    return response()->json($vote);
  }

  /**
   * Remove the specified vote from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $vote = Vote::findOrFail($id);
    $vote->delete();

    return response()->json("destroyed");
  }
}
