<?php


class UserController {

    function httpGetMethod(Http $http, Array $queryField) {
        return [
            '_form' => new UserForm()
        ];
    }

    function httpPostMethod(Http $http, Array $formFields) {
        try {
            // variables indispenssables
            $firstName = trim($formFields['firstName']);
            $lastName = trim($formFields['lastName']);
            $email = trim($formFields['email']);
            $password = $formFields['password'];

            // variables optionnelles
            $phone = trim($formFields['phone']);
            $address = trim($formFields['address']);
            $city = trim($formFields['city']);
            $zipCode = intval($formFields['zipCode']);

            if (empty($firstName) OR empty($lastName) OR empty($email) OR empty($password))
                throw new DomainException("Les champs marqués d'une * sont obligatoires");

            // création du compte utilisateur
            $customerModel = new CustomerModel();
            $customerId = $customerModel->create($firstName, $lastName, $phone, $email, $password, $address, $city, $zipCode);

            // création de la session utilisateur
            $userSession = new UserSession();
            $userSession->create($customerId, $firstName, $lastName);

            // création du message de confirmation
            $flashbag = new FlashBag();
            $flashbag->add('Votre compte à bien été crée');

        } catch (DomainException $exception) {

            // on instancie la classe UserForm et
            // on utilise la méthode bind pour lui injècter les valeurs utilisateur
            $userForm = new UserForm();
            $userForm->bind($formFields);
            $userForm->setErrorMessage($exception->getMessage());
            return [
                '_form' => $userForm
            ];
        }

        // redirection vers la page d'accueil
        $http->redirectTo('/');
    }
}