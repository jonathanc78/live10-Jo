<?php

class BookingController {

    function httpGetMethod(Http $http) {
        $userSession = new UserSession();
        if (!$userSession->isLogged()) {
            $flashBag = new FlashBag();
            $flashBag->add('Merci de vous connecter pour faire une réservation');
            $http->redirectTo('user/login');
        }
    }

    function httpPostMethod(Http $http, array $formFields) {
        $quantity = intval($formFields['quantity']);
        $date = $formFields['date'] . ' ' . $formFields['hour'] . ':' . $formFields['minute'];

        // récupération de l'id user
        $userSession = new UserSession();
        $customer_id = $userSession->getId();

        //enregistrement de la réservation
        $bookingModel = new BookingModel();
        $bookingModel->add($customer_id, $date, $quantity);

        // message de confirmation et redirections
        $flashBag = new FlashBag();
        $flashBag->add('Merci ! Votre reservation à bien été enregistré');
        $http->redirectTo('/');
    }
}