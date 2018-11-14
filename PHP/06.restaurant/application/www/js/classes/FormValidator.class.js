"use strict";

/**
 * Classe de Validation des formulaires,
 *  - doit s'instancier les les pages contenant un <form>
 *  - il faut appliquer (soigneusement 3x par jour) les attribut-data suivant :
 *      data-required
 *      data-min-length
 *      data-max-length
 *      data-type = email|positiveInteger|password
 *      data-name
 */

var FormValidator = function (form) {
    this.form = form;
    this.errorMessage = this.form.find('.error-message');
    this.errors = [];
};

FormValidator.prototype.checkFieldRequired = function () {
    var field, name;
    var requiredFields = $('[data-required]');

    // on boucle sur tous les champs qui possèdent l'attribut data-required
    for (var index = 0; index < requiredFields.length; index++) {
        field = $(requiredFields[index]);
        name = field.data('name');

        // si la longueur de la value de l'input est de zero
        if (field.val().length == 0) {
            // on ajoute le message d'erreur
            this.errors.push('Le champ <strong>' + name + '</strong> est requis');
        }
    }
};

FormValidator.prototype.checkFieldLength = function () {
    var field, name, length;
    var maxLengthFields = $('[data-max-length]');
    var minLengthFields = $('[data-min-length]');

    // on boucle sur tous les champs qui possèdent l'attribut data-max-length
    for (var index = 0; index < maxLengthFields.length; index++) {
        field = $(maxLengthFields[index]);
        name = field.data('name');
        length = field.data('maxLength');

        // si la longueur de la value de l'input > à maxLength
        if (field.val() != "" && field.val().length > length) {
            // on ajoute le message d'erreur
            this.errors.push('Le champ <strong>' + name + '</strong> doit être plus petit que ' + length);
        }
    }

    // on boucle sur tous les champs qui possèdent l'attribut data-required
    for (index = 0; index < minLengthFields.length; index++) {
        field = $(minLengthFields[index]);
        name = field.data('name');
        length = field.data('minLength');

        // si la longueur de la value de l'input < à minLength
        if (field.val() != "" && field.val().length < length) {
            // on ajoute le message d'erreur
            this.errors.push('Le champ <strong>' + name + '</strong> doit être plus grand que ' + length);
        }
    }
};

FormValidator.prototype.checkFieldTypes = function () {
    var fields, type, value, name;
    fields = $('[data-type]');

    for (var index = 0; index < fields.length; index++) {
        value = $(fields[index]).val();
        type = $(fields[index]).data('type');
        name = $(fields[index]).data('name');

        switch (type) {
            case 'positiveInteger' :
                if (!isInteger(value) || value < 0)
                    this.errors.push('Le champ <strong>' + name + '</strong> doit être un entier positif');
                break;
            case 'email' :
                var regexp = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if (!value.match(regexp))
                    this.errors.push('Vous devez choisir un <strong>' + name + '</strong> valide');
                break;
            case 'password' :
                var regexp = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{4,})/;

                if (!value.match(regexp))
                    this.errors.push('Le <strong>' + name + '</strong> doit contenir au moins 1 caractère majuscule,minuscule,spécial un chiffre');
                break;
        }
    }

};

FormValidator.prototype.displayErrors = function () {

    // création d'un <ul> en mode bac à sable
    var ul = $('<ul>');

    // boucle sur toutes les erreurs
    for (var index = 0; index < this.errors.length; index++) {
        // insertion des erreurs avec un <li> dans le <ul>
        ul.append($('<li>').html(this.errors[index]));
    }

    // enfin remplace l'<ul> déjà existant dans le DOM
    this.errorMessage.fadeIn().find('ul').replaceWith(ul);

};

FormValidator.prototype.onSubmitForm = function (event) {
    // remise des erreurs à zero
    this.errors = [];

    // vérification du formulaire
    this.checkFieldRequired();
    this.checkFieldTypes();
    this.checkFieldLength();


    // action s'il y a des erreurs sur la page
    if (this.errors.length > 0) {
        event.preventDefault();
        this.displayErrors();
    }
};

FormValidator.prototype.validateForm = function () {
    this.form.submit(this.onSubmitForm.bind(this));
};

