<?php

class CartFilter implements InterceptingFilter {

    public function run(Http $http, array $queryFields, array $formFields) {

        return [
            'cart' => new CartModel()
        ];
    }
}