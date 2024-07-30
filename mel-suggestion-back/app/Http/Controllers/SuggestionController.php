<?php

namespace App\Http\Controllers;

use App\Models\Notification;
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
    $session_user = $request->session()->get('utilisateur');

    if (config('suggestion.notification') && config('moderator.moderator')[0] !== "") {
      foreach (config('moderator.moderator') as $email) {
        if ($email != $session_user->email) {
          $class = config('suggestion.orm_path') . "\User";
          $user = new $class();
          $user->email = $email;
          $user->load();
          Notification::sendCreateSuggestionNotification($user, $session_user);
        }
      }
    }

    $request->validate([
      'title' => 'required|max:255',
      'description' => 'required',
      'user_firstname' => '',
      'user_lastname' => '',
      'user_email' => '',
    ]);

    // Récupérer la configuration d'anonymisation
    $anonymize = config('app.suggestion_anonymize');

    // Si l'anonymisation est activée, ne pas inclure les informations personnelles de l'utilisateur
    $user_email = $anonymize ? 'Anonyme' : $session_user->email;
    $user_firstname = $anonymize ? 'Anonyme' : explode(' ', $session_user->name)[0];
    $user_lastname = $anonymize ? ' ' : explode(' ', $session_user->name)[1];


    $newSuggestion = new Suggestion([
      'title' => $request->get('title'),
      'description' => $request->get('description'),
      'user_email' => $user_email,
      'user_firstname' => $user_firstname,
      'user_lastname' => $user_lastname,
      'state' => 'moderate',
      'instance' => config('suggestion.instance'),
    ]);

    $newSuggestion->save();
    $newSuggestion->votes_up = 0;
    $newSuggestion->votes_down = 0;

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

    if (config('suggestion.notification')) {
      Notification::sendUpdateSuggestionNotification($suggestion);
    }
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

  /**
   * Display a URL of Synonyms from the config
   *
   * @return \Illuminate\Http\Response
   */
  public function getUrl()
  {
    return response()->json(config('suggestion.synonyms_url'));
  }
}
