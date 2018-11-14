<?php
/**
 * Created by PhpStorm.
 * User: jochevalier
 * Date: 13/11/2018
 * Time: 09:38
 */

class MealForm extends Form {
    function build() {
        $this->addFormField("name");
        $this->addFormField("description");
        $this->addFormField("photo");
        $this->addFormField("quantityInStock");
        $this->addFormField("buyPrice");
        $this->addFormField("salePrice");
    }
}