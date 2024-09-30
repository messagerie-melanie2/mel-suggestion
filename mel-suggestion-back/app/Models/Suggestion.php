<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Throwable;

/**
 * Class Suggestion
 *
 * @package App\Models
 */
class Suggestion extends Model
{
  use HasFactory;

  /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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


  /**
     * Get all suggestions by instance.
     *
     * This method retrieves all suggestions belonging to the current instance from the database.
     * It filters the suggestions based on the user's moderator status and whether they are the owner of the suggestion.
     * It also retrieves vote information for each suggestion and updates suggestion attributes accordingly.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
  public static function getAllSuggestionsByInstance($session_user)
  {
    try {
      $suggestions = Suggestion::where('instance', config('suggestion.instance'))->get();
    } catch (Throwable $e) {
      Log::error($e);
    }

    $suggestions_ids = [];

    foreach ($suggestions as $key => $suggestion) {
      if (!$session_user->moderator) {
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

  /**
     * Count the votes for a suggestion.
     *
     * This method counts the number of upvotes and downvotes for a given suggestion.
     *
     * @param  \App\Models\Suggestion  $suggestion The suggestion for which to count the votes.
     * @return \App\Models\Suggestion
     */
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

  /**
     * Check if a suggestion belongs to the current user.
     *
     * This method checks if a given suggestion belongs to the current user based on their session data.
     *
     * @param  \App\Models\Suggestion  $suggestion The suggestion to check ownership.
     * @return \App\Models\Suggestion
     */
  public static function isMySuggestion($suggestion)
  {
    if ($suggestion->user_email == Session::get('utilisateur')->email) {
      $suggestion->my_suggestion = true;
    }
    return $suggestion;
  }
}
