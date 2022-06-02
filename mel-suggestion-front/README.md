# mel-suggestion-front

## Project setup
```
npm install
```

### Compiles and hot-reloads for development
```
npm run serve
```

### Compiles and minifies for production
```
npm run build
```

### Lints and fixes files
```
npm run lint
```

### Customize configuration
See [Configuration Reference](https://cli.vuejs.org/config/).



README.md

mel-suggestion Outil de boite à idées pour Mél

But du projet Le but de ce projet est de réaliser une application de gestion de Sugestion

Clonez le repository git git clone https://github.com/messagerie-melanie2/mel-suggestion.git composer install npm install Creer un fichier .env avec vos données de connexion à la base de données éxécuter le script sql dans une base de données postgresql

Projet Le projet est répartir en trois parties: parties base de données: app: controlleur et model parties route dans le dossier route: web.php parties vue dans le dossier routes/js/components/

Mise en place de la base de données Prend le fichier dans le dossier script sql , connecte toi à ta base de données en te placant sur ta bdd . Lance le script pour créer les tables établissement de la connexion à la base de données 2 possibilités: soit tu créeer un fichier .env avec en y mettant les information de connexion à la base de données .

soit tu vas dans le fichier ./config/database.php et tu rempli tes informations de connexion directement en en enlevant ke

Lancement sur le serveur de laravel rennomage du fichier index.php en serve.php déplacement du fichier .htaccess dans le dossier public php artisan serve Lancement du projet ci il faut réinstaller les package et les fichier .lock composer install

docker-npm npm install

php artisan serve Lancement avec Appache ou un autre serveur Verifier qu'il y à la racine du projet les fichiers index.php et .htaccess Dans ce cas le metttre dans le serveur, gérer les autorisations.

Configuration de l'application Dans le fichier ./app/Models/User: $user attribuer une valeur à $user l'email que vous voulez fixer.

Dans le fichier ./config/Moderateur.php: Mettez la valeur de l'instance de l'endroit du serveur où est lancer l'application

docker-compose -rm composer install

Configuration
Dans le dossier allez dans le dossier config/Moderateur.php :
Mettez le nom de l’instance qui sera utiliser.
Ci vous voulez tester l’application en tant que Modérateur mettez votre adresse mail dans le tableau des Modérateur sinon laissez comme elle est

Dans le fichier app/Model/user
Mettez une adresse mail comme valeur de User que vous voulez utilisez .
Ci vous voulez l’utilisez en tant que Moderateur il faut qu’elle ce retrouve dans le tableau des Modérateurs  comme fais ci-dessus



```sh
 ```sh
 utiliser la commande php artisan key:generate pour générer une clés.
 Données les accès à l'applicatiou au serveur
 déplacer le htacces du du dossier public à la racine
 rennomer le server.php en index.php

dans le fichier .env dans AppUrl mettre l'adresse du serveur plus la route sur laquelle le projet est sancer ce lancer



```sh
--sh
l'application est programmer pour obliger l'utilisateur à faire une recherche avant de pouvoir faire une suggestion. Pour cela il à une barre de recherche. 
Il peut voire les suggestions qui ont déjà  été proposer par des gens et il peut voter à l'aide des boutons vote.
Il peut aussi voire les suggestion qu'il à déjà fait mais qui ne sont pas encore valider 
--sh