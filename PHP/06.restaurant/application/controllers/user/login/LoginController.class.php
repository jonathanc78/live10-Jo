<?php

class LoginController {

    function httpGetMethod() {
        return [
            '_form' => new LoginForm()
        ];
    }

    function httpPostMethod(Http $http, Array $formField) {
        $email = trim($formField['email']);
        $password = $formField['password'];

        try {
            if (empty($email) OR empty($password))
                throw new DomainException('Veuillez remplir tous les champs');

            // test du login
            $customerModel = new CustomerModel();
            $customer = $customerModel->login($email, $password);

            // création de la session utilisateur
            $userSession = new UserSession();
            $userSession->create($customer['Id'], $customer['FirstName'], $customer['LastName']);

            // création du message de confirmation
            $flashbag = new FlashBag();
            $flashbag->add('bravo vous êtes connecté');

        } catch (DomainException $exception) {
            // gestion des erreurs et renvoi des valeurs dans le formulaire
            $loginForm = new LoginForm();
            $loginForm->bind($formField);
            $loginForm->setErrorMessage($exception->getMessage());

            return [
                '_form' => $loginForm
            ];
        }

        // redirection vers la page d'accueil
        $http->redirectTo('/');
    }
}