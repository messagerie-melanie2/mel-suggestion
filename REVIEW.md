# Review

Code et project review de mel-suggestion

## Versions

* Vendredi 11 Février 2022 :
  * Mise à jour des review sur le code php

* Vendredi 28 janvier 2022 :
  * Ajout de bonnes pratiques
  * Review du javascript

* Lundi 24 janvier 2022 : 
  * Création du code review;
  * Ajout des Guidelines PHP et JS;
  * Review du schéma de base de données;
  * Review sur les routes, les controlleurs et les modèles.

## Code PHP

### Code guidelines

Voici quelques exemples de code Guidelines à suivre, dédiées notamment a Laravel :

* https://www.mindtwo.de/guidelines/coding/laravel
* https://github.com/spatie/guidelines.spatie.be/blob/master/content/code-style/laravel-php.md
* https://spatie.be/guidelines/laravel-php

### Bonnes pratiques

* Ne pas utiliser $_REQUEST, $_GET ou $_POST directement mais passer par le traitement des inputs de Laravel (https://laravel.com/docs/4.2/requests#basic-input)
* Utiliser des requêtes "prepared" pour le SQL : permet de protéger les paramètres des injections SQL
* Ne pas mettre de `use` si la classe n'est pas utilisée dans le code

### routes/web.php

```PHP
Route::get('/RecoverySugestion', [SugestionController::class, 'Recupsugestion']);
Route::get('/Detail',[SugestionController::class,'Detailsugestion']);
Route::post('/AddSugestion', [SugestionController::class, 'store']);
Route::put('/UpdateSugestion', [SugestionController::class, 'UpdateSugestion']);
Route::put('/Updateeta', [SugestionController::class, 'UpdateState']);
Route::delete('/Deletesugestion', [SugestionController::class, 'destroy']);
Route::post('/Voter', [VoteController::class, 'create']);
Route::delete('/Suprimervote', [VoteController::class, 'destroy']);
Route::delete('/GenerationCSV', [GeneratecsvController::class, 'export']);
Route::get('/role',[SugestionController::class,'Yesno']);
```

* Uniformiser le nom des routes (majuscules/minuscules, anglais/français, pluriel/singulier) :
  * `Sugestion` -> `Suggestion`, 
  * `Voter` -> `AddVote`, 
  * `Updateeta` -> `UpdateState`
  * `Suprimervote` -> `DeleteVote`, 
  * `GenerationCSV` -> `ExportCSV`,
  * `role` -> `Role` ? à quoi sert cette route ? Fonction 'Yesno' ?

### app/Http/Controllers/GeneratecsvController.php

* Améliorer l'indentation du code (c'est peu lisible actuellement)
* Ajouter des docblocks dans les méthodes

### app/Http/Controllers/SugestionsController.php

```PHP
    public function Yesorno()
```

* Ce nom de méthode n'est pas clair, qu'est ce que ça fait ? (ça semble lié au modérateur)

```PHP
class lsugestion
{
    public $title;
    public $description;
...
    public $appartient;
    public $instance;
}
```

* Sortir cette classe dans un fichier dédié (une classe par fichier)
* Ajouter des docblocks pour connaitre son usage

### app/Models/User.php

```PHP
    public function __setfonction()
```

* Choix de nom de méthode assez perturbant, on ne comprend pas ce que ça doit faire `isModerator` ou `setIsModerator` parait plus pertinent je pense
* Pas d'interet pour que le nom commence par `__` c'est en principe réservé aux méthodes "magiques" c'est à dire qui s'appellent toutes seules
* Pourquoi ne pas directement faire ce traitement dans le `__construct` plutot que de devoir appeler cette méthode à chaque fois ?


### app/Models/Vote.php

```PHP
    use HasFactory;
```

* Non nécessaire

### app/Models/sugestion.php

* `sugestion.php` -> `Suggestion.php` il faut rester cohérent avec les autres modèles

```PHP
    use HasFactory;
```

* Idem sur les `use`

```PHP
        'id',
        'Title',
        'Description',
        'User',
        'date_creat',
        'etat',
        'Url',
```

* Uniformiser les champs anglais, minuscules, ... (voir [la review du schéma](https://github.com/messagerie-melanie2/mel-suggestion/blob/main/REVIEW.md#table-sugestions))

## Code Javascript

### Code guidelines

Voici quelques exemples de code Guidelines à suivre, pour VueJS et javascript en général :

* https://vuejs.org/v2/style-guide/
* https://docs.gitlab.com/ee/development/fe_guide/style/vue.html
* https://github.com/airbnb/javascript#airbnb-javascript-style-guide-

### resources/js/App.vue

Est-ce que ce fichier est utile ?

### resources/js/components/Code.vue

* Séparer le code JS du template vue ?

```Javascript
      axios.post("http://127.0.0.1:8000/AddSugestion", [
```

* Ne pas utiliser l'URL `http://127.0.0.1:8000/` en dur dans le code. Soit passer par une configuration dans le backend pour le host et la fournir au frontend, soit le frontend doit déterminer tout seul quelle est la bonne url.


## Base de données

### Schema review

Ce schéma ne semble pas compatible avec une base de données *PostgreSQL* (syntaxe, utilisation d'objets non connus en PG) et semble plutôt avoir été fait pour du *MySQL*.

### Création d'index

Le schéma ne semble pas utiliser d'index. Il faut créer des index implicites (via les PRIMARY KEY) et aussi des index pour accélérer les recherches dans les données (par exemple le champ `user` de la table `suggestion` devrait probablement avoir un index)

### Table sugestions

```SQL
CREATE TABLE `sugestions` (
  `id` int(11) NOT NULL,
  `Title` text NOT NULL,
  `Description` text NOT NULL,
  `User` varchar(100) NOT NULL,
  `date_creation` date NOT NULL,
  `etat` int(11) NOT NULL,
  `Url` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

* Ne pas mélanger français et anglais (idéalement tout passer en anglais) : `etat` -> `state`, `date_creation` -> `created`
* Pas de majuscule (et pas de mélange entre majuscules et minuscules sur les différents champs) : `Title` -> `title`
* `sugestions` -> `suggestion` (en anglais et au singulier)
* `Url` ? quel est l'usage de ce champ ?
* `Url varchar(100)` : varchar(100) bien trop court pour une url, utiliser `TEXT`
* `User varchar(100)` : Idem trop court, soit utiliser au moins `VARCHAR(255)`, soit `TEXT`
* `int(11)` : utiliser `INTEGER` (je pense que int n'existe pas en PostgreSQL)

### Alter Table sugestions

```SQL
ALTER TABLE `sugestions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
```

* Pas de alter table dans un schéma initial
* `AUTO_INCREMENT` n'existe pas en postgresql il vaut mieux passer par des `SEQUENCE`