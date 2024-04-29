# Configuration OpenID Connect

## mel-suggestion-back

### Fichier external-connector.php

Configuration des différents opérateurs OpenID Connect proposés qui renvoie vers les informations prtésentes dans le .env

>**Critères Nécessaires :**

Critères généraux :
- external_connector
- external_proxy

Critères par Opérateurs :
- client_url
- client_id
- client_secret
- client_fields

### Fichier .env

Configuration des différentes données opérateurs.

>**Critères Nécessaires :**

- EXTERNAL_PROXY=
- OPERATEUR_URL=
- OPERATEUR_ID=
- OPERATEUR_SECRET=
- OPERATEUR_FIELDS=
- APPLICATION_URL=

_A la place de OPERATEUR renseigner le nom de l'opérateur OpenID Connect (exemple : GOOGLE_URL=, GOOGLE_ID=, ...)_

### Fichier LoginController.php

Permet de configurer les fonctions qui renvoient vers la vue souhaitée et qui permettent de renvoyer l'utilisateur sur l'opérateur OpenID Connect qu'il a sélectionné pour s'authentifier.

### Fichier connection.blade.php

Correspond à la vue utilisateur qui lui permet de sélectionner l'opérateur OpenID Connect de son choix.

### Fichier web.php

configuration de la route qui renvoie vers la vue de sélection des différents opérateurs, aux utilisateurs.

``Route::get('/', [LoginController::class, 'index'])->name('connection')``

### Configuration **GoogleCloud**

<https://console.cloud.google.com/apis/credentials/oauthclient/373526053027-1c5c94afpibf81hb6rdsqm6lbrcbm68k.apps.googleusercontent.com?authuser=1&project=suggestion-362609>

Dans Identifiants on retrouve les informations qui permettent de configurer le .env :

- ID client
- Secret client

mais également l'URI globale de l'application mel-suggestion et les URI de redirection autorisée.

GOOGLE_URL=https://accounts.google.com/

### Configuration Microsoft entra

<https://entra.microsoft.com/?culture=fr-fr&country=fr#home>

Dans Applications > Inscription des applications.

Puis dans Applications détenues > Mel Suggestion

on retrouve les informations à renseigner dans le fichier .env :
- MICROSOFT_ID
- ainsi que le Tenant ID (_ID de l'annuaire_) qui doit être ajouté à l'URL du champ MICROSOFT_ID (https://login.microsoftonline.com/tenant_id/)

ATTENTION : Pour le champ MICROSOFT_SECRET= il faut renseigner la valeur et non le ID de secret disponible. Cette valeur est lisible unique lorsqu'on créé un ID secret. Si l'on oublie de le configurer lors de la création du ID secret, il est nécessaire de créer un nouvel ID secret, et donc de modifier en conséquence le fichier .env