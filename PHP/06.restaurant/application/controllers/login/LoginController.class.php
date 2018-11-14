<?php
/**
 * Created by PhpStorm.
 * User: jochevalier
 * Date: 05/11/2018
 * Time: 16:52
 */

class LoginController {
    function httpGetMethod(Http $http, Array $queryField) {

    }

    function httpPostMethod(Http $http, Array $formField)
    {
         $email = trim(strtolower($formField ['email']));

         $password = $formField ['password'];

        if (!empty($email) and !empty($password)){
            $customersModel= new CustomerModel();
            $customersModel->logincustomer($email, $password);

        }
    }

}