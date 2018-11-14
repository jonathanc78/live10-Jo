<?php

class AddressForm extends Form {

    function build() {
        $this->addFormField("FirstName");
        $this->addFormField("LastName");
        $this->addFormField("Phone");
        $this->addFormField("Address");
        $this->addFormField("City");
        $this->addFormField("ZipCode");
    }
}