<p align="center">
  <img src="https://github.com/messagerie-melanie2/mel-suggestion/assets/144009217/171d6031-9e23-4a04-b3e4-6dae90a9e8a2" alt="Image Suggestion Mèl">
</p>

# mel-Suggestion

**mel-Suggestion** est une application web simple qui permet aux utilisateurs de proposer de nouvelles fonctionnalités ou améliorations. Les utilisateurs peuvent également voter pour les suggestions existantes afin de les prioriser.

## Fonctionnalités

- **Proposer des Suggestions** : Les utilisateurs peuvent soumettre leurs idées de nouvelles fonctionnalités ou améliorations.
- **Voter pour des Suggestions** : Les utilisateurs peuvent voter pour les suggestions existantes pour indiquer leur intérêt ou leur priorité.
- **Consulter les Suggestions** : Tous les utilisateurs peuvent consulter la liste des suggestions ainsi que leur nombre de votes.
- **Trier les Suggestions** : Les suggestions peuvent être triées selon divers critères comme les plus votées, les plus récentes, celles qui ont été retenues, ou celles qui ont été refusées.
- **Rechercher des Suggestions** : Les utilisateurs peuvent rechercher si des suggestions similaires aux leurs, existent déjà, à l'aide de mots clés.
- **Connexion OIDC** : Les utilisateurs peuvent s'identifier à l'aide d'opérateur OpenID Connect.
- **Choix des connexions OIDC** : Possibilité de configurer les Opérateurs OpenID Connect disponibles pour les utilisateurs.
- **Anonymisation des suggestions** : Il est possible d'activer ou de désactiver l'anonymisation des suggestions pour les utilisateurs.

## Technologies Utilisées

- **Frontend** : HTML, CSS, JavaScript (Vue.js)
- **Backend** : PHP, Laravel
- **Base de Données** : PostgreSQL
- **Authentification** : Authentification en Session. En cas d'échec possibilité de se connecter via OpenID Connect (Google, Microsoft)

## Pour Commencer

 1. Télécharger la release :
	 Sans proxy : `wget https://github.com/messagerie-melanie2/mel-suggestion/releases/download/[TAG]/suggestion.tar.gz` 
	 
	Avec proxy : `wget -e use_proxy=yes http_proxy=[PROXY] -e https_proxy=[PROXY] https://github.com/messagerie-melanie2/mel-suggestion/releases/download/[TAG]/suggestion.tar.gz `

 2. Extraire le dossier tar
 `tar -xzvf suggestion.tar.gz`

 3. Passage des droits de user à apache (www-data) :
    `sudo chown -R www-data:www-data (dossier)`

 4. Générer une clé d'application avec `php artisan key:generate`

 5. Configurer Apache pour /public :
```
Alias "/suggestion" "[chemin]/mel-suggestion-back/public"
<Directory "[chemin]/mel-suggestion-release/mel-suggestion-back/public">
	Options Indexes FollowSymLinks MultiViews
	AllowOverride All
	Require all granted
</Directory>
```

 6. Modifier le fichier .env dans mel-suggestion-back (clé secrète, connexion à la base, anonymisation des données, instance, etc...) puis valider avec `php artisan config:clear`

Si problème d'écriture dans laravel.log :
`sudo chmod -R 775 storage`
`sudo chmod -R 775 bootstrap/cache`

## Utilisation

1. Accéder à la rubrique "Aide", puis cliquer sur "Proposer une amélioration".
<p align="center">
  <kbd><img src="https://github.com/messagerie-melanie2/mel-suggestion/assets/144009217/9acd9483-6379-48c9-8d5b-e74332f4b29d" alt="Proposer une amélioration"></kbd>
</p>

2. Parcourez les suggestions existantes sur la page d'accueil.

  
3. Avant de soumettre votre suggestion, veuillez vérifier préalablement si une proposition similaire n'existe pas déjà en utilisant le champ 'Rechercher ou créer une suggestion'. Pour cela entrer le mot-clé de votre suggestion pour procéder à cette vérification.
<p align="center">
  <kbd><img src="https://github.com/messagerie-melanie2/mel-suggestion/assets/144009217/c3b7967f-195d-43eb-a615-9c998c9d4b87" alt="Recherche de suggestion"></kbd>
</p>

4. Soumettez votre propre suggestion.
<p align="center">
  <kbd><img src="https://github.com/messagerie-melanie2/mel-suggestion/assets/144009217/259384e9-feb5-4571-8625-4d13e0d9f3f4" alt="Formulaire de suggestion"></kbd>
</p>

5. Votez pour les suggestions existantes en cliquant sur les boutons de vote.

6. Triez les suggestions selon vos préférences.

## Activation des fonctionnalités d'Anonymisation et de connexion OpenID Connect

### :key: OpenID Connect

#### mel-suggestion-back

#### :page_with_curl: Fichier external-connector.php

Configuration des différents opérateurs OpenID Connect proposés qui renvoie vers les informations présentes dans le .env

>**Critères Nécessaires :**

Critères généraux :
- external_connector
- external_proxy

Critères par Opérateurs :
- client_url
- client_id
- client_secret
- client_fields

#### :page_with_curl: Fichier .env

Configuration des différentes données opérateurs.

>**Critères Nécessaires :**

- EXTERNAL_PROXY=
- OPERATEUR_URL=
- OPERATEUR_ID=
- OPERATEUR_SECRET=
- OPERATEUR_FIELDS=
- APPLICATION_URL=

> [!NOTE]
> _A la place de OPERATEUR renseigner le nom de l'opérateur OpenID Connect (exemple : GOOGLE_URL=, GOOGLE_ID=, ...)_

#### :page_with_curl: Fichier LoginController.php

Permet de configurer les fonctions qui renvoient vers la vue souhaitée et qui permettent de renvoyer l'utilisateur sur l'opérateur OpenID Connect qu'il a sélectionné pour s'authentifier.

#### :page_with_curl: Fichier connection.blade.php

Correspond à la vue utilisateur qui lui permet de sélectionner l'opérateur OpenID Connect de son choix.

#### :page_with_curl: Fichier web.php

Configuration de la route qui renvoie vers la vue de sélection des différents opérateurs, aux utilisateurs.

``Route::get('/', [LoginController::class, 'index'])->name('connection')``

#### :gear: Configuration **GoogleCloud**

<https://console.cloud.google.com/apis/credentials/oauthclient/373526053027-1c5c94afpibf81hb6rdsqm6lbrcbm68k.apps.googleusercontent.com?authuser=1&project=suggestion-362609>

Dans Identifiants on retrouve les informations qui permettent de configurer le .env :

- ID client
- Secret client

mais également l'URI globale de l'application mel-suggestion et les URI de redirection autorisée.

GOOGLE_URL=https://accounts.google.com/

#### :gear: Configuration Microsoft entra

<https://entra.microsoft.com/?culture=fr-fr&country=fr#home>

Dans Applications > Inscription des applications.

Puis dans Applications détenues > Mel Suggestion

on retrouve les informations à renseigner dans le fichier .env :
- MICROSOFT_ID
- ainsi que le Tenant ID (_ID de l'annuaire_) qui doit être ajouté à l'URL du champ MICROSOFT_ID (https://login.microsoftonline.com/tenant_id/)

> [!WARNING]
> Pour le champ **``MICROSOFT_SECRET=``** il faut renseigner la valeur et non le ID de secret disponible. Cette valeur est lisible unique lorsqu'on créé un ID secret. Si l'on oublie de le configurer lors de la création du ID secret, il est nécessaire de créer un nouvel ID secret, et donc de modifier en conséquence le fichier .env

### 👀 Anonymisation

L'activation ou la désactivation de l'anonymisation s'effectue depuis le fichier ``.env`` :

:heavy_check_mark: Pour anonymiser les suggestions, modifier la variable d'environnement **``SUGGESTION_ANONYMIZE``** comme ci-dessous :

<kbd><img src="https://github.com/messagerie-melanie2/mel-suggestion/assets/144009217/b14926bc-beb8-4d54-b3ee-1b92a70ad34c" alt="Activation anonymisation"></kbd>

Visuel dans l'application :

<kbd><img src="https://github.com/messagerie-melanie2/mel-suggestion/assets/144009217/c466cdfe-3065-479f-8156-cbca12704142" alt="Visuel suggestion anonyme"></kbd>

:x: Pour désactiver l'anonymisation des suggestions, modifier la variable d'environnement **``SUGGESTION_ANONYMIZE``** comme ci-dessous :

<kbd><img src="https://github.com/messagerie-melanie2/mel-suggestion/assets/144009217/5584399a-966f-443d-a889-5901ca550d5a" alt="Désactivation anonymisation"></kbd>

Visuel dans l'application :

<kbd><img src="https://github.com/messagerie-melanie2/mel-suggestion/assets/144009217/8c8d4464-4ebb-444a-ba82-05a6aacf74f6" alt="Visuel suggestion non anonymisée"></kbd>


## A venir
- Mise en place de l'option de connexion OpenID Connect via Agent Connect.

## Démo

**A envisager si besoin**
Vous pouvez essayer une démo en direct de l'application [ici](#).

## Licence

**A compléter avec les bonnes informations**
Ce projet est sous licence MIT - voir le fichier [LICENSE](LICENSE) pour plus de détails.

