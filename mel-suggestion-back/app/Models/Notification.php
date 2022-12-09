<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
  use HasFactory;

  public static function sendCreateSuggestionNotification($user, $session_user)
  {
    $class = env("ORM_PATH") . "\Notification";
    $notification = new $class($user);

    $notification->category = "suggestion";
    $notification->title = " $session_user->name vient d'ajouter une nouvelle suggestion";
    $notification->content = "Vous pouvez voir la suggestion via le lien disponible ci-dessous";
    $notification->action = serialize([
      [
        'text' => "Aller aux suggestions",
        'title' => "Cliquez pour aller aux suggestions",
        'command' => 'open_suggestion'
      ]
    ]);

    // Ajouter la notification au User
    $user->addNotification($notification);
  }

  public static function sendUpdateSuggestionNotification($suggestion_owner)
  {
    $class = env("ORM_PATH") . "\User";
    $user = new $class();
    $user->email = $suggestion_owner->user_email;
    $user->load();

    $class = env("ORM_PATH") . "\Notification";
    $notification = new $class($user);


    switch ($suggestion_owner->state) {
      case 'validate':
        $notification->title = "Un modérateur vient de valider votre suggestion";
        break;
      case 'refused':
        $notification->title = "Un modérateur vient de refuser votre suggestion";
        break;
      case 'vote':
        $notification->title = "Un modérateur vient d'accepter votre suggestion";
        break;
      default:
        $notification->title = "Un modérateur vient de mettre à jour votre suggestion";
        break;
    }

    $notification->category = "suggestion";
    $notification->content = 'Votre suggestion "' . $suggestion_owner->title . '" à été modifiée.';
    $notification->action = serialize([
      [
        'text' => "Aller aux suggestions",
        'title' => "Cliquez pour aller aux suggestions",
        'command' => 'open_suggestion'
      ]
    ]);

    // Ajouter la notification au User
    $user->addNotification($notification);
  }
}
