<?php

class UserSession {

    function __construct() {
        // il faut vérifier que la session n'as pas déjà été démarré
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    function create($id, $firstName, $lastName) {
        // génération de la session utilisateur
        $_SESSION['user'] = ['id' => $id, 'fullName' => "$firstName $lastName"];
        $_SESSION['isLogged'] = true;
        $_SESSION['cart'] = [];
    }

    function delete() {
        // purge de la session utilisateur
        $_SESSION = [];
        session_destroy();
    }


    /*****************************************************************
     *                          ENCAPSULATIONS
     *****************************************************************/
    function isLogged() {
        if (array_key_exists('isLogged', $_SESSION))
            return $_SESSION['isLogged'];
        return false;

        // version courte : return array_key_exists('isLogged', $_SESSION) AND $_SESSION['isLogged'];
    }

    function getId() {
        return $_SESSION['user']['id'];
    }

    function getFullName() {
        return $_SESSION['user']['fullName'];
    }

    function getCart() {

        return $_SESSION['cart'];

    }

    function saveCart($cart) {
        $_SESSION['cart'] = $cart;
    }
}