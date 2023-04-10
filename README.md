# Test Technic Folks
Pour commencer, vous pouvez fork ce projet dans un autre repo et vous faire une branche à partir de là.

##  Mise en contexte
Le but du test technique est de créer la partie back-end de notre super application SaaS de todo. 
Le backend doit être une API REST, et nous allons l'utiliser avec le front-end qui se trouve dans le dossier `FrontEnd`.
Étant donné qu'il s'agit d'une API REST, il doit être possible d'y accèder via un client http tel que Postman.
## Installation
Nous avons fournis un fichier `docker-compose.yml` afin d'utiliser un docker sur votre poste. Nous n'imposons pas la techno, alors vous pouvez utiliser l'environement que vous désirez. 

Voici les spécifications nécessaire si vous désirez utiliser ce projet en local:
- PHP 8
- MySQL 8
- Composer
- Node v14.0
- NPM 8

Sinon, vous devrez vous faire un fichier `.env` à partir du fichier `.env.example` qui se trouve dans le dossier `docker`.
vous pourrez, ensuite, démarrer le docker en executant la commande suivante: 
```shell
docker-compose up -d
```

vous devez ensuite faire les étapes suivantes.  Si vous prenez docker, vous devez les exécuter à l'intérieur du conteneur. Pour vous connecter au conteneur, exécuter la commande suivante: 
```shell
docker exec -it folks-technic_apache /bin/bash
```


Premièrement, vous allez devoir vous faire un fichier `.env` à l'aide du fichier `.env.example` qui se trouve à la racine du projet. Vous allez, ensuite, devoir modifier ce fichier afin de mettre à jour les différentes variables d'environnement selon votre environnement. 

À noter que si vous utilisez docker, les informations dans le fichier `.env.example` sont déjà correctement configurées avec la base de données.

Ensuite, vous allez devoir installer les dépendances du projets à l'aide de composer. 
```shell
composer install
```

Ensuite, vous allez devoir générer les clés de projet 
```shell
php artisan key:generate
```

Normalement, après ces modifications, votre environnement devrait être prêt à être utilisé.
Si vous êtes sous docker, vous pouvez y accèder via l'url suivante:
```
http://localhost:{port que vous avez mis dans docker/.env}


ex: http://localhost:8081
```


Nous vous avons fournis un contrôleur de base avec les méthodes déjà crées, il ne vous reste qu'à les remplir.

## Tâches

### Initialisation de la DB 
Notre application utilise un model "Todo", qui permet de garder les informations des todo. Vous devez, donc, créer la table dans la DB, et le model associé.

Critères d'acceptance: 
- Le modèle doit contenir les colonnes suivantes: 
  - ID
  - titre
  - description
  - done ( boolean )
  - date de création
  - date de modification
  - date de suppression

- Le titre ne peut pas avoir plus de 100 caractères
- Le titre doit être unique
- La description peut avoir jusqu'a 1024 caractères
- La description est nullable.
- Les models doivent être soft-delete.


### Création du controller Todo
vous devez créer un controller CRUD pour la ressource TODO. Ce contrôleur doit prendre en compte la création, la modification, l'index et la suppression des modèles "Todo".

critères d'acceptance: 
- Lors de la création et de la modification, les données envoyées doivent être validées à l'aide d'une `FormRequest`.
- Les méthodes update et create doivent retourner l'objet créer / modifier avec le bon status. ( 201 pour create et 200 pour update)
- Les objets retournés doivent être formattés selon ce que le front-end s'attend à recevoir, à l'aide d'une resource. Voir l'exemple dans le fichier `FrontEnd/db.json`
- Le contrôleur est disponible à la route `/todos`.
- Une suite de test est disponible afin de valider les différentes méthodes du controller.
- Lors d'une erreur de validation dans les méthodes update / create, un code 422 doit être retourné.
- La méthode "destroy" doit retourner une réponse vide et un code 204.

## Précisions

### TDD
Nous recommandons l'utilisation de la méthode TDD lors de la création de feature. Vous pouvez vous baser sur les critères d'acceptances pour écrire vos tests, mais cela ne vous empêche pas d'en ajouter d'autre que vous jugez pertinent.

### Model
Lorsqu'on parle de model, dans ce document,  nous faisons référence au model eloquent que Laravel offre. À ne pas confondre avec un model comme dans MVC. D'ailleurs, nous recommendons l'usage des modèles Eloquent au lieux des modèles MVC.

### Data wrapping
Laravel offre une features pour "wrapper" les données d'une collection sous une clé, lorsque retournée en JSON. Dans le cas de notre tests, ceci n'est pas nécessaire, vous pouvez garder le comportement de base de laravel. Si vous vous fiez au fichier `db.json`, vous verrez que l'ont utilise une clé "todos". Cette clé est nécessaire afin de mapper la route "/todos" vers les données, lorsque l'on utilise la fausse base de données. Il ne s'agit pas d'un requirement.

## Front-end
Vous n'avez pas à faire de front-end. Celui-ci se trouve déjà dans le dossier Front-end.
Initialement, le front-end est connecté sur une fausse base de données, qui est gérée par le fichier `db.json`. Si vous voulez tester votre backend directement avec le front-end, vous devrez changer la variable `VUE_API_URL` pour pointer vers votre application. Dans le cas du docker, il s'agit de `http://localhost:8081/api`.

Pour lancer le front-end vous devez avoir node et npm d'installer sur votre poste, et suivre la procédure suivante:.
- Installer les dépendances ( `npm install` )
- Lancer le serveur de developpement ( `npm run serve` )


## PHPUnit
Dans le dossier `.run`, vous devriez avoir une configuration de base afin de rouler tous les tests dans le dossier `tests/features`.

P.S. la configuration suivante prend pour acquis que vous êtes sur PHPStorm.

Vous allez devoir configurer votre interpréteur PHP. Si vous êtes sous docker, vous allez devoir le configurer avec votre fichier `docker-compose.yml`. Si ce n'est pas le cas, vous pouvez cibler votre interpréteur local.

Pour configurer l'interpreter docker, vous devez aller dans settings -> PHP -> puis les trois petit point à coté du select "CLI Interpreter"

Dans cette modale, vous aurez l'option d'ajouter un nouvel interpréteur à l'aide du bouton +, puis en utilisant l'option "From docker, vagrant, etc."

Dans la nouvelle modale qui va s'ouvrir, choisissez "docker-compose", puis votre fichier de config qui se trouve dans le dossier `docker`. 

Vous pourrez ensuite choisir le service ( apache dans notre cas ) et votre interpréteur devrait être configuré correctement.
