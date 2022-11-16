<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
  use HasFactory;

  public static function sendCreateSuggestionNotification($user, $session_user)
  {
    $notification = new \LibMelanie\Api\Mel\Notification($user);

    $notification->category = "suggestion";
    $notification->title = " $session_user->name vient d'ajouter une nouvelle suggestion";
    $notification->content = "Vous pouvez voir la suggestion via le lien disponible ci-dessous";
    $notification->action = serialize([
      [
        'href' => "/bureau/?_task=settings&_action=plugin.mel_suggestion_box",
        'text' => "Aller aux suggestions",
        'title' => "Cliquez pour voir la suggestion",
      ]
    ]);

    // Ajouter la notification au User
    $user->addNotification($notification);
  }

  public static function sendUpdateSuggestionNotification($suggestion_owner)
  {
    $user = new \LibMelanie\Api\Mel\User;
    $user->email = $suggestion_owner->user_email;
    $user->load();
    $notification = new \LibMelanie\Api\Mel\Notification($user);

    $notification->category = "suggestion";
    $notification->title = "Un modérateur vient de mettre à jour votre suggestion";
    $notification->content = "Vous pouvez voir votre suggestion via le lien disponible ci-dessous";
    $notification->action = serialize([
      [
        'href' => "/bureau/?_task=settings&_action=plugin.mel_suggestion_box",
        'text' => "Aller aux suggestions",
        'title' => "Cliquez pour voir la suggestion",
      ]
    ]);

    // Ajouter la notification au User
    $user->addNotification($notification);
  }
}
