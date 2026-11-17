<?php

namespace App\Console\Commands;

use App\Models\Suggestion;
use Illuminate\Console\Command;
/**
 * Repasse les suggestions existantes au filtre HTML (audit de sécurité, faille C1).
 *
 * Le filtrage ajouté dans SuggestionController::store() et update() ne s'applique
 * qu'aux suggestions créées ou modifiées après le correctif. Cette commande applique
 * les mêmes règles aux suggestions déjà en base, qui peuvent contenir du HTML
 * malveillant déposé avant :
 *  - titre : toutes les balises sont retirées (strip_tags) ;
 *  - description : filtrée par le profil "suggestion" de config/purifier.php.
 *
 * À lancer une fois après le déploiement, après une sauvegarde de la base :
 *   php artisan suggestions:sanitize --dry-run   # liste les suggestions concernées
 *   php artisan suggestions:sanitize             # applique le nettoyage
 *
 * La commande est idempotente 
 */

class SanitizeSuggestions extends Command
{
    protected $signature = 'suggestions:sanitize {--dry-run : Affiche les changements sans les enregistrer}';

    protected $description = 'Repasse titres et descriptions existants au filtre HTML (audit C1)';

    public function handle(): int
    {
        $dryRun  = (bool) $this->option('dry-run');
        $changed = 0;

        Suggestion::query()->orderBy('id')->chunkById(200, function ($suggestions) use ($dryRun, &$changed) {
            foreach ($suggestions as $suggestion) {
                $title       = trim(strip_tags((string) $suggestion->title));
                $description = clean((string) $suggestion->description, 'suggestion');

                if ($title === $suggestion->title && $description === $suggestion->description) {
                    continue;
                }

                $changed++;
                $this->line("Suggestion #{$suggestion->id} à nettoyer");

                if (!$dryRun) {
                    $suggestion->timestamps  = false; // garde updated_at, utilisé pour le tri « Récentes »
                    $suggestion->title       = $title;
                    $suggestion->description = $description;
                    $suggestion->save();
                }
            }
        });

        $this->info(($dryRun ? '[dry-run] ' : '') . "{$changed} suggestion(s) concernée(s).");

        return 0;
    }
}