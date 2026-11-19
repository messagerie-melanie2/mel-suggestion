<?php

namespace App\Support;

use App\Models\Suggestion;

/**
 * Utilisateur courant et règles d'autorisation sur les suggestions (audit C2).
 *
 * Les règles reprennent celles de l'interface :
 *  - modification : bouton « Éditer » de Suggestion.vue ;
 *  - suppression : Suggestion.vue (auteur) et ModeratorCommands.vue (modérateur).
 * Le front masque les boutons ; seul le serveur interdit réellement l'action.
 */
final class CurrentUser
{
    /**
     * Utilisateur en session, ou 401 s'il est absent ou incomplet.
     */
    public static function get(): object
    {
        $user = json_decode((string) session()->get('suggestion_user'));
        abort_if(!$user || empty($user->email), 401);

        return $user;
    }

    public static function isModerator(object $user): bool
    {
        return !empty($user->moderator);
    }

    public static function owns(object $user, Suggestion $suggestion): bool
    {
        return hash_equals(
            strtolower((string) $suggestion->user_email),
            strtolower((string) $user->email)
        );
    }

    /**
     * Modification : uniquement en modération, par l'auteur ou un modérateur.
     */
    public static function canEdit(object $user, Suggestion $suggestion): bool
    {
        return $suggestion->state === 'moderate'
            && (self::isModerator($user) || self::owns($user, $suggestion));
    }
    

    /**
     * Suppression : un modérateur en modération ou refusée, l'auteur en modération.
     */
    public static function canDelete(object $user, Suggestion $suggestion): bool
    {
        if (self::isModerator($user)) {
            return in_array($suggestion->state, ['moderate', 'refused'], true);
        }

        return self::owns($user, $suggestion) && $suggestion->state === 'moderate';
    }
}