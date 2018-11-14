<?php
/**
 * Created by PhpStorm.
 * User: jochevalier
 * Date: 13/11/2018
 * Time: 09:24
 */

class AdminController{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        /*
         * Méthode appelée en cas de requête HTTP GET
         *
         * L'argument $http est un objet permettant de faire des redirections etc.
         * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
         */

        return [
            '_form' => new MealForm()];
    }

    public function httpPostMethod(Http $http, array $formFields) {
        /*
         * Méthode appelée en cas de requête HTTP POST
         *
         * L'argument $http est un objet permettant de faire des redirections etc.
         * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
         */
        try {
            // variables indispensables
            $name = trim($formFields['name']);
            $description = trim($formFields['description']);

            $quantityInStock = $formFields['quantityInStock'];
            $buyPrice = trim($formFields['buyPrice']);
            $salePrice = trim($formFields['salePrice']);


           if (empty($name) OR empty($description) OR empty($quantityInStock) OR empty($buyPrice)OR empty($salePrice))
                throw new DomainException("Les champs marqués d'une * sont obligatoires");

           if ($http->hasUploadedFile('photo') ){
              $photo = $http->moveUploadedFile('photo', '/images/meals' );
           } else {
               $photo = 'no-photo.png';
           }

            // ajout d'un nouveau produit
            $mealModel = new MealModel();
            $mealModel->createMeal($name, $description, $photo, $quantityInStock, $buyPrice, $salePrice);

           // création du message de confirmation
            $flashbag = new FlashBag();
            $flashbag->add('Votre produit a bien été ajouté');

        } catch (DomainException $exception) {

            // on instancie la classe MealForm et
            // on utilise la méthode bind pour lui injècter les valeurs utilisateur
            $mealForm = new MealForm();
            $mealForm->bind($formFields);
            $mealForm->setErrorMessage($exception->getMessage());
            return [
                '_form' => $mealForm
            ];
        }

        // redirection vers la page d'accueil
        $http->redirectTo('/');
    }
}

