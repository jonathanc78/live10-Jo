<?php
/**
 * Created by PhpStorm.
 * User: jochevalier
 * Date: 13/11/2018
 * Time: 11:39
 */

class OrderController
{
    function httpGetMethod(Http $http, Array $queryField) {
        $userSession = new UserSession();
        $customerModel = new CustomerModel();
        $customer = $customerModel->getUser( $userSession->getId());



        $addressForm  = new AddressForm();
        $addressForm->bind($customer);
        return [

            '_form' => $addressForm
        ];
    }

    function httpPostMethod(Http $http, Array $formFields) {
        //var_dump($formFields); exit;

        try {
            // variables indispensables
            $FirstName = trim($formFields['FirstName']);
            $LastName = trim($formFields['LastName']);
            $Phone = trim($formFields['Phone']);
            $Address = trim($formFields['Address']);
            $City = trim($formFields['City']);
            $ZipCode = intval($formFields['ZipCode']);

            if (empty($FirstName) OR empty($LastName) OR empty($Phone) OR empty($Address) OR empty($City) OR empty($ZipCode))
                throw new DomainException("Les champs marqués d'une * sont obligatoires");
            $userSession = new UserSession();

            $customerModel = new CustomerModel();
            $customerModel->updateUser($FirstName, $LastName, $Address, $Phone,  $City, $ZipCode, $userSession->getId());

            $http->redirectTo("/order/payment");

        } catch (DomainException $exception) {

            // on instancie la classe UserForm et
            // on utilise la méthode bind pour lui injècter les valeurs utilisateur
            $addressForm = new AddressForm();
            $addressForm->bind($formFields);
            $addressForm->setErrorMessage($exception->getMessage());
            return [
                '_form' => $addressForm
            ];
        }

    }
}