<?php

/**
 * Created by PhpStorm.
 * User: Alan
 * Date: 07/11/2018
 * Time: 00:19
 */
class LogoutController {
    function httpGetMethod(Http $http) {
        $userSession = new UserSession();
        $userSession->delete();

        $flashbag = new FlashBag();
        $flashbag->add('A bientôt, quel dommage...');

        $http->redirectTo('/');
    }
}