<?php

class MealModel {
    function getMeals() {
        $db = new Database();
        return $db->query("SELECT Id, Name, Description, Photo, QuantityInStock, BuyPrice, SalePrice FROM meals");
    }

    function createMeal($name, $description, $photo, $quantityInStock, $buyPrice, $salePrice) {
        $sql = "INSERT INTO meals (Name, Description, Photo, QuantityInStock, BuyPrice, SalePrice) VALUES (?,?,?,?,?,?)";
        $db = new Database();
        $db->executeSql($sql, [$name, $description, $photo, $quantityInStock, $buyPrice, $salePrice]);
    }

    function decreaseMeal($meal_id, $quantity = 1) {
        $meal = $this->getMeal($meal_id);

        if (!$meal)
            throw new DomainException("Produit introuvable");

        $quantity = $meal['QuantityInStock'] - $quantity;

        if ($quantity < 0)
            throw new DomainException("Il n'y à pas assez de produit en stock (" . $meal['QuantityInStock'] . ")");

        // finalement on fait la modification dans la base de données
        $this->updateMealQuantity($meal_id, $quantity);
    }

    function getMeal($meal_id) {
        $db = new Database();
        return $db->queryOne("SELECT Id, Name, Description, Photo, QuantityInStock, BuyPrice, SalePrice FROM meals WHERE id = ?", [$meal_id]);
    }

    function updateMealQuantity($meal_id, $quantity = 1) {
        $sql = "UPDATE meals SET QuantityInStock = ? WHERE Id = ? ";
        $db = new Database();
        $db->executeSql($sql, [$quantity, $meal_id]);
    }

    function increaseMeal($meal_id, $quantity = 1) {
        $meal = $this->getMeal($meal_id);

        if (!$meal)
            throw new DomainException("Produit introuvable");

        $quantity = $meal['QuantityInStock'] + $quantity;

        $this->updateMealQuantity($meal_id, $quantity);
    }

    function removeMeal($meal_id) {
        $db = new Database();
        $db->executeSql("DELETE FROM meals WHERE id=?", [$meal_id]);
    }
}
