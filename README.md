# Exercice Todolist

## Objectifs

- Vous devez prendre le relais d'un développeur sur le codage d'une todolist [donc les captures sont visibles ici](https://github.com/O-clock-Alumni/fiches-recap/tree/master/symfony/themes/img/todolist).
- Symfony website-skeleton 4.3 est installé.
- Les contrôleurs nécessaires sont créés.
- Vous devrez terminer la configuration des routes, et de coder certaines méthodes de contrôleurs.
- Vous trouverez le modèle de données `TodoModel.php` qui propose une gestion des tâches via le composant de Session Symfony.
- Les templates sont créés mais vous devrez les faire évoluer.

> Note: L'intégration HTML/CSS utilise Bootstrap **dans sa version 3** et la librairie Font Awesome.

## Les tâches à réaliser

### Contrôleurs

- Prendre connaissance de chaque méthode des contrôleurs fournis et en analyser le code.

- **Ajouter les pré-requis sur les méthodes GET, POST ou les deux pour chaque méthode de contrôleur**, voir [requirements => Adding HTTP method requirements](https://symfony.com/doc/current/routing.html#matching-http-methods).

- Formulaire nouvelle tâche : [**Passer par l'objet Request**](http://symfony.com/doc/current/controller.html#the-request-and-response-object) pour récupérer les paramètres POST.
   - Crée le code manquant.
    > Note: **Nous n'utilisons pas les formulaires Symfony pour le moment**.
   - Puis [rediriger vers la liste des tâches via la méthode appropriée](https://symfony.com/doc/current/controller.html#redirecting).


- **Pour définir le statut d'une tâche (disons _done_ et _undone_), une seule méthode a été créée** qui prend en paramètre l'id et le nouveau statut de la tâche _todoSetStatus($id, $status)_. Contraindre la valeur du statut aux 2 paramètres _done_ et _undone_ seulement (via les [_requirements_](https://symfony.com/doc/current/routing.html#parameters-validation) et les Regex).

- **Ajouter des _[Flash Messages](https://symfony.com/doc/current/controller.html#flash-messages)_** dans le contrôleur sur les actions de changements de statut d'une tâche, de suppression d'une tâche, de création d'une tâche ou autre.

> _Les messages flash sont des variables stockées en session. Leur particularité est que, dès qu’ils ont été récupérés, ils sont aussitôt supprimés de la session. Parfait pour envoyer une notification juste après une opération)_. Cela fait en outre partie des bonnes pratiques Opquast :wink: cf BP 97 et BP 98 (formulaires : message et redirection)

 - Puis [rediriger vers la liste des tâches](https://symfony.com/doc/current/controller.html#redirecting).
 
 - Afficher les flash messages sur la destination de la redirection (ou dans le layout global mais cela peut être moins précis). Tenter d'appliquer le style [Alert Bootstrap](https://getbootstrap.com/docs/3.3/components/#alerts) correspondant.

- **Effectuer les vérifications de validité de certaines actions** (comme _/todo/show/4_ par ex.) : [Renvoyer une 404](https://symfony.com/doc/current/controller.html#managing-errors-and-404-pages) si la tâche demandée n'existe pas. Identifier toutes les routes où la 404 peut être envoyée.

- Pour l'action _delete_ **utiliser une méthode POST** (un formulaire donc).

> Note: **N'utilisez pas les formulaires Symfony pour le moment**, un formulaire HTML classique suffira.

### Templates

- Jetez un oeil du côté de [l'include de Twig](https://symfony.com/doc/current/templates.html#including-templates) (partial) pour mutualiser le formulaire d'ajout d'une tâche (sur les pages d'accueil et de liste).

- Mettre un titre de page différent à chaque page (héritage !).

### Model

- Ajouter la méthode `reset()` au modèle Todo, afin de remettre les données par défaut du modèle (on vide la session et/ou on remet les données de base).

  - Lien _Réinitialiser les tâches (dev)_ : Ajouter le lien sous la liste et créer l'action de contrôleur correspondante. Ajouter éventuellement un message flash pour cette action.

#### Bonus

- Rendre la sélection du menu dynamique, selon la route de la page courante.
   - Twig et la variable globale [`app`](https://symfony.com/doc/current/templates.html#the-app-global-variable)
   > Note: La fonction `{% dump() %}` et `{{ dump() }}` fonctionne aussi dans les templates.
   - PS : Il existe peut-être une autre solution, si quelqu'un a une idée...
- L'action _todoSetStatus()_ pourrait être codée plus simplement que ce qui a été proposé.
- Modifier le code actuel pour pouvoir réaliser la mise à jour du statut ou/et la suppression d'une tâche avec AJAX.
