<?php

class HomeController {

    public function httpGetMethod(Http $http, array $queryFields) {
        /*
         * Méthode appelée en cas de requête HTTP GET
         *
         * L'argument $http est un objet permettant de faire des redirections etc.
         * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
         */

        // récupération de tous les repas par le modèle
        $mealModel = new MealModel();
        $meals = $mealModel->getMeals();

        return [
            'meals' => $meals
        ];
    }

    public function httpPostMethod(Http $http, array $formFields) {
        /*
         * Méthode appelée en cas de requête HTTP POST
         *
         * L'argument $http est un objet permettant de faire des redirections etc.
         * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
         */
    }
}