<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class VoteController extends Controller
{
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

    if (Config::get('app.suggestion_anonymize') === true) {
      $user_email = isset($request->session()->get('utilisateur')->sub) ? $request->session()->get('utilisateur')->sub : $request->session()->get('utilisateur')->email;
    } else {
      $user_email = $request->session()->get('utilisateur')->email;
    }

    $newVote = new Vote([
      'user_email' => $user_email,
      'suggestion_id' => $request->get('suggestion_id'),
      'type' => $request->get('type'),
    ]);

    $newVote->save();

    return response()->json($newVote);
  }

  /**
   * update an existing vote in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $vote = Vote::findOrFail($id);
    $vote->type = $request->get('type');
    $vote->save();

    return response()->json($vote);
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
