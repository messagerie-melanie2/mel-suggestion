<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vote;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;

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
   * @param  Illuminate\Contracts\Session\Session $session
   * @param  \Illuminate\Http\Request  $request
   * 
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request, Session $session)
  {
    $user = New User();
    if ($request->session()->has('suggestion_user')) {
      $encryptedUser = $request->session()->get('suggestion_user');
      $user = json_decode(Crypt::decryptString($encryptedUser));
    }
    $request->validate([
      'suggestion_id' => 'required'
    ]);

    if (Config::get('app.suggestion_anonymize') === true) {
      $user_email = isset($user->sub) ? $user->sub : $user->email;
    } else {
      $user_email = $user->email;
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
