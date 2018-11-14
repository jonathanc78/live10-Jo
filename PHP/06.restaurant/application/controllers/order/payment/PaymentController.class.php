<?php

class PaymentController
{
    function httpGetMethod(Http $http, Array $queryField)   {
        $userSession = new UserSession();
        $customerModel = new CustomerModel();
        $customer = $customerModel->getUser( $userSession->getId());

        $cartModel = new CartModel();
        $orderDetails = $cartModel->getAllMealInfos();

        $totalPrice = $cartModel->getTotalPrices();

        return [
            'ordersDetails'     => $orderDetails,
            'customer'          => $customer,
            'totalPrice'        => $totalPrice
        ];

    }
    function httpPostMethod(Http $http, Array $formFields)  {

    }


}