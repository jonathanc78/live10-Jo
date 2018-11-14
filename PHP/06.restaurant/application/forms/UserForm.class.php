<?php

class UserForm extends Form {

    function build() {
        $this->addFormField("firstName");
        $this->addFormField("lastName");
        $this->addFormField("email");
        $this->addFormField("password");
        $this->addFormField("phone");
        $this->addFormField("address");
        $this->addFormField("city");
        $this->addFormField("zipCode");
    }

}