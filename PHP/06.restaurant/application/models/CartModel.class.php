<?php

class CartModel {

    const TVA = 20;


    public function decrease($mealId, $quantity = 1) {
        // récupération du panier
        $cart = $this->getAll();

        // Peut-on enlever cette quantité au panier ?
        if (!array_key_exists($mealId, $cart) or $cart[$mealId] - $quantity < 0)
            throw new DomainException("Vous n'avez pas suffisement de produit dans le panier");

        // modification des stocks
        $mealModel = new MealModel();
        $mealModel->increaseMeal($mealId, $quantity);

        // modification du panier
        $cart[$mealId] -= $quantity;

        // on sauvegarde les modifications
        $this->saveAll($cart);

        // on renvoi la nouvelle quantité
        return $cart[$mealId];
    }

    /**************************************************************************************
     *                      Récupération et sauvegarde du panier                          *
     **************************************************************************************/
    public function getAll() {
        $userSession = new UserSession();
        return $userSession->getCart();
    }

    public function saveAll($cart) {
        $userSession = new UserSession();
        $userSession->saveCart($cart);
    }

    public function updateQuantity($mealId, $quantity) {
        // récupération du panier
        $cart = $this->getAll();

        $current_quantity = array_key_exists($mealId, $cart) ? $cart[$mealId] : 0;

        $current_diff = $quantity - $current_quantity;

        $mealModel = new MealModel();
        if ($current_diff < 0) {
            $mealModel->decreaseMeal($mealId, abs($current_diff));
        } elseif ($current_diff > 0) {
            $mealModel->increaseMeal($mealId, $current_diff);
        }

        // on met à jour la quantité dans le panier
        // (ternaire pour interdire les quantité < 0)
        $cart[$mealId] = $quantity >= 0 ? $quantity : 0;

        // on sauvegarde les modifications
        $this->saveAll($cart);

        return $cart[$mealId];
    }

    public function increase($mealId, $quantity = 1) {
        // récupération du panier
        $cart = $this->getAll();

        // modification des stocks
        $mealModel = new MealModel();
        $mealModel->decreaseMeal($mealId, $quantity);

        // on vérifique si le produit existe déjà dans la base
        if (!array_key_exists($mealId, $cart)) {
            $cart[$mealId] = $quantity;
        } else {
            $cart[$mealId] += $quantity;
        }

        // on sauvegarde les modifications
        $this->saveAll($cart);

        // on renvoi la nouvelle quantité
        return $cart[$mealId];
    }

    public function clear() {
        $mealModel = new MealModel();

        // on remet les produits en stock
        foreach ($this->getAll() as $meal_id => $quantity) {
            $mealModel->increaseMeal($meal_id, $quantity);
        }

        // on enregistre un panier vide
        $this->saveAll([]);
    }

    public function getTotalPrices() {
        $totalHT = 0;

        foreach ($this->getAllMealInfos() as $meal) {
            $totalHT += $meal['Quantity'] * $meal['SalePrice'];
        }

        // self c'est l'équivalent de this sur une classe non Instanciée (cf le mode strict)
        $tva = $totalHT * self::TVA / 100;

        return [
            'ht' => $totalHT,
            'tva' => $tva,
            'ttc' => $tva + $totalHT,
        ];
    }

    public function getAllMealInfos() {
        $cart = $this->getAll();

        $newCart = [];

        $mealModel = new MealModel();

        foreach ($cart as $mealId => $quantity) {

            // si la quantité est nulle dans le panier, on enregistre pas les données
            if ($quantity == 0) {
                continue;
            }

            // récupération des informations sur le produit
            $mealInfos = $mealModel->getMeal($mealId);

            // ajout des infos sur le produits (titre, description...)
            $newCart[$mealId] = $mealInfos;

            // ajout de la quantité dans le panier;
            $newCart[$mealId]['Quantity'] = $quantity;
        }

        return $newCart;
    }

    public function getTotalQuantity() {
        $total = 0;

        foreach ($this->getAll() as $quantity)
            $total += $quantity;

        return $total;
    }

}