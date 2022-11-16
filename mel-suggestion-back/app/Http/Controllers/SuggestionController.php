<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
  /**
   * Display a listing of the moderate and user's suggestions.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $suggestions = Suggestion::getAllSuggestionsByInstance();

    return response()->json($suggestions);
  }

  /**
   * Store a newly created suggestion in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $user = new LibMelanie\Api\Mel\User();
    $user->uid = 'arnaud.goubier.i';

    $notification = new LibMelanie\Api\Mel\Notification($user);

    $notification->category = "webconf";
    $notification->title = "XXXXX vient d'ajouter une nouvelle suggestion'";
    $notification->content = "Vous pouvez rejoindre directement la webconférence en cours via le lien disponible ci-dessous";
    $notification->action = serialize([
      [
        'href' => "/bureau/?_task=workspace&_action=workspace&_uid=gmcd-1",
        'text' => "Rejoindre",
        'title' => "Cliquez pour rejoindre la webconférence",
      ]
    ]);

    // Ajouter la notification au User
    $user->addNotification($notification);

    $request->validate([
      'title' => 'required|max:255',
      'description' => 'required',
      'user_firstname' => '',
      'user_lastname' => '',
      'user_email' => '',
    ]);

    $session_user = $request->session()->get('utilisateur');

    $newSuggestion = new Suggestion([
      'title' => $request->get('title'),
      'description' => $request->get('description'),
      'user_email' => $session_user->email,
      'user_firstname' => explode(' ', $session_user->name)[0],
      'user_lastname' => explode(' ', $session_user->name)[1],
      'state' => 'moderate',
      'instance' => env('INSTANCE'),
    ]);

    $newSuggestion->save();
    $newSuggestion->nb_votes = 0;

    return response()->json($newSuggestion);
  }

  /**
   * Display the specified suggestion.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $suggestion = Suggestion::findOrFail($id);
    return response()->json($suggestion);
  }

  /**
   * Update the specified suggestion in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $suggestion = Suggestion::findOrFail($id);

    $request->validate([
      'title' => 'required|max:255',
      'description' => 'required',
    ]);

    $suggestion->title = $request->get('title');
    $suggestion->description = $request->get('description');

    $suggestion->save();

    $suggestion = Suggestion::isMySuggestion($suggestion);

    return response()->json(Suggestion::countVote($suggestion));
  }

  /**
   * Update the specified suggestion state in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function updateState(Request $request, $id)
  {
    $suggestion = Suggestion::findOrFail($id);

    $request->validate([
      'state' => 'required',
      'comment' => '',
    ]);

    $suggestion->state = $request->get('state');

    $suggestion->comment = $request->get('comment');

    $suggestion->save();

    return response()->json(Suggestion::countVote($suggestion));
  }

  /**
   * Remove the specified suggestion from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $suggestion = Suggestion::findOrFail($id);
    $suggestion->delete();

    return response()->json("ok");
  }

  /**
   * Display a listing of text from the config
   *
   * @return \Illuminate\Http\Response
   */
  public function getText()
  {
    return response()->json(config('text'));
  }
}
