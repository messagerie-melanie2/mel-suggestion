<?php

/**
 * Configuration de HTML Purifier (paquet mews/purifier).
 *
 * Le profil "suggestion" filtre le titre et la description des suggestions
 * (audit de sécurité, faille C1). Il est utilisé par :
 *  - App\Http\Controllers\SuggestionController::store() et update() ;
 *  - la commande php artisan suggestions:sanitize.
 *
 * La liste blanche correspond à la barre d'outils par défaut de vue2-editor
 * (Quill 1.3.7). Volontairement exclus : iframe (vidéos), cases à cocher
 * (attribut data-checked), scripts, attributs on*, liens javascript:.
 *
 * Toute balise ou classe ajoutée ici doit aussi être compatible avec
 * l'affichage du front (Accordion.vue).
 */

return [
    'encoding'      => 'UTF-8',
    'finalize'      => true,
    'cachePath'     => storage_path('app/purifier'),
    'cacheFileMode' => 0755,

    'settings' => [

        // Profil par défaut du paquet, utilisé si clean() est appelé sans profil
        'default' => [
            'HTML.Doctype'             => 'HTML 4.01 Transitional',
            'HTML.Allowed'             => 'div,b,strong,i,em,u,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src]',
            'CSS.AllowedProperties'    => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align',
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty'   => true,
        ],

        // Profil des suggestions (audit C1)
        'suggestion' => [
            'HTML.Doctype' => 'XHTML 1.0 Transitional',

            'HTML.Allowed' => implode(',', [
                'p[class]', 'br', 'span[style]',
                'strong', 'em', 'u', 's',
                'h1[class]', 'h2[class]', 'h3[class]', 'h4[class]', 'h5[class]', 'h6[class]',
                'blockquote[class]', 'pre[class]',
                'ol[class]', 'ul[class]', 'li[class]',
                'a[href|title]',
                'img[src|alt|width|height]',
            ]),

            // Alignement, retrait et bloc de code de Quill
            'Attr.AllowedClasses' => [
                'ql-align-center', 'ql-align-right', 'ql-align-justify',
                'ql-indent-1', 'ql-indent-2', 'ql-indent-3', 'ql-indent-4',
                'ql-indent-5', 'ql-indent-6', 'ql-indent-7', 'ql-indent-8',
                'ql-syntax',
            ],

            // Couleur du texte et du fond uniquement
            'CSS.AllowedProperties' => ['color', 'background-color'],

            // data: n'accepte que des images (png, gif, jpeg) : images collées dans Quill
            'URI.AllowedSchemes' => [
                'http'   => true,
                'https'  => true,
                'mailto' => true,
                'data'   => true,
            ],

            // Liens ouverts dans un nouvel onglet, sans accès à la page d'origine
            'HTML.TargetBlank'    => true,
            'HTML.TargetNoopener' => true,

            // Ne pas réorganiser la mise en page produite par Quill
            'AutoFormat.AutoParagraph' => false,
            'AutoFormat.RemoveEmpty'   => false,
        ],
    ],
];