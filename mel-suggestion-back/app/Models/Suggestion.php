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
    'user_email',
    'instance',
    'state',
  ];

  public static function getAllSuggestionsByInstance($moderator = false)
  {
    $suggestions = Suggestion::where('instance', env('INSTANCE'))->get();

    $suggestions_ids = [];

    foreach ($suggestions as $key => $suggestion) {
      if (!$moderator) {
        if ($suggestion->state == 'moderate' && $suggestion->user_email != Session::get('email')) {
          unset($suggestions[$key]);
          continue;
        }
      }
      if ($suggestion->state == 'moderate') {
        $suggestion->updated_at = now();
      }
      if ($suggestion->user_email == Session::get('email')) {
        $suggestion->my_suggestion = true;
      }
      array_push($suggestions_ids, $suggestion->id);
    }

    $votes = Vote::whereIn('suggestion_id', $suggestions_ids)->get();
    foreach ($suggestions as $key => $suggestion) {
      $nb_votes = 0;
      foreach ($votes as $vote) {
        if ($vote->suggestion_id == $suggestion->id) {
          $nb_votes++;
          if ($vote->user_email == Session::get('email')) {
            $suggestion->voted = true;
            $suggestion->vote_id = $vote->id;
          }
        }
      }
      $suggestion->nb_votes = $nb_votes;
    }
    // dd($suggestions);

    return $suggestions;
  }

  public static function countVote($suggestion)
  {
    $votes = Vote::where('suggestion_id', $suggestion->id)->count();
    $suggestion->nb_votes = $votes;
    return $suggestion;
  }

  public static function isMySuggestion($suggestion)
  {
    if ($suggestion->user_email == Session::get('email')) {
      $suggestion->my_suggestion = true;
    }
    return $suggestion;
  }
}
