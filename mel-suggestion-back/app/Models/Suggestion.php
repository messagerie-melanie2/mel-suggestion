<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Suggestion extends Model
{
  use HasFactory;
  protected $fillable = [
    'id',
    'title',
    'description',
    'user_firstname',
    'user_lastname',
    'user_email',
    'instance',
    'state',
    'comment'
  ];

  public static function getAllSuggestionsByInstance()
  {
    $suggestions = Suggestion::where('instance', env('INSTANCE'))->get();

    $session_user = Session::get('utilisateur') != null ? Session::get('utilisateur') : new User();

    $suggestions_ids = [];

    foreach ($suggestions as $key => $suggestion) {
      if (!$session_user['moderator']) {
        if ($suggestion->state == 'moderate' && $suggestion->user_email != $session_user->email) {
          unset($suggestions[$key]);
          continue;
        }
      }
      
      if ($suggestion->user_email == $session_user->email) {
        $suggestion->my_suggestion = true;
      }
      array_push($suggestions_ids, $suggestion->id);
    }

    $votes = Vote::whereIn('suggestion_id', $suggestions_ids)->get();
    foreach ($suggestions as $key => $suggestion) {
      $votes_up = 0;
      $votes_down = 0;
      foreach ($votes as $vote) {
        if ($vote->suggestion_id == $suggestion->id) {
          if ($vote->type == "down") {
            $votes_down++;
          } else {
            $votes_up++;
          }
          if ($vote->user_email == $session_user->email) {
            $suggestion->voted = true;
            $suggestion->voted_type = $vote->type;
            $suggestion->vote_id = $vote->id;
          }
        }
      }
      $suggestion->votes_up = $votes_up;
      $suggestion->votes_down = $votes_down;
    }

    return $suggestions;
  }

  public static function countVote($suggestion)
  {
    $suggestion->votes_up = Vote::where([
      ['suggestion_id', '=', $suggestion->id],
      ['type', '=', 'up']
    ])->count();
    $suggestion->votes_down = Vote::where([
      ['suggestion_id', '=', $suggestion->id],
      ['type', '=', 'down']
    ])->count();
    return $suggestion;
  }

  public static function isMySuggestion($suggestion)
  {
    if ($suggestion->user_email == Session::get('utilisateur')->email) {
      $suggestion->my_suggestion = true;
    }
    return $suggestion;
  }
}
