<?php

class CartController {

    private $ajaxMessage = "";

    function httpGetMethod(Http $http, array $queryFields) {
        $userSession = new UserSession();
        if (!$userSession->isLogged()) {
            $flashBag = new FlashBag();
            $flashBag->add('Merci de vous connecter pour voir votre panier');
            $http->redirectTo('user/login');
        }


        if (array_key_exists('action', $queryFields)) {
            $action = $queryFields['action'];
            $mealId = array_key_exists('mealId', $queryFields) ? intval($queryFields['mealId']) : null;
            $quantity = array_key_exists('quantity', $queryFields) ? intval($queryFields['quantity']) : null;

            // on empèche de mettre des quantités négatives dans le panier
            $quantity = $quantity < 0 ? 0 : $quantity;

            try {
                $this->cartAction($action, $mealId, $quantity);
            } catch (DomainException $exception) {
                echo $exception->getMessage();
            }
        }

        if (array_key_exists('ajax', $queryFields)) {
            echo $this->ajaxMessage;
            exit;
        }

        if (array_key_exists('urlBack', $queryFields)) {
            $http->redirectTo($queryFields['urlBack']);
        }

    }

    private function cartAction($action, $mealId = null, $quantity = null) {
        // récupération des données pour ajax
        $cartModel = new CartModel();
        $quantityInCart = 0;
        $mealModel = new MealModel();
        $mealInfo = $mealModel->getMeal($mealId);

        switch ($action) {
            case "decrease":
                $quantityInCart = $cartModel->decrease($mealId, $quantity);
                break;

            case "increase":
                $quantityInCart = $cartModel->increase($mealId, $quantity);
                break;

            case "updateQuantity":
                $quantityInCart = $cartModel->updateQuantity($mealId, $quantity);
                break;

            case "clearCart":
                $cartModel->clear();
                break;
        }


        // récupération du prix total pour le panier
        $totalPrice = $cartModel->getTotalPrices();

        // finalisation de la réponse ajax
        $this->ajaxMessage = json_encode([
            'mealName' => $mealInfo['Name'],
            'mealId' => $mealInfo['Id'],
            'quantityInCart' => $quantityInCart,
            'quantity' => $quantity,
            'totalQuantityInCart' => $cartModel->getTotalQuantity(),
            'htPrice' => $totalPrice['ht'],
            'tva' => $totalPrice['tva'],
            'ttcPrice' => $totalPrice['ttc']
        ]);
    }

    function httpPostMethod() {

    }
}