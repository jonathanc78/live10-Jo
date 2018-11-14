'use strict';

/////////////////////////////////////////////////////////////////////////////////////////
// FONCTIONS                                                                           //
/////////////////////////////////////////////////////////////////////////////////////////
function checkForms() {
    var form = $('form');
    if (form.length) {
        var formValidator = new FormValidator(form);
        formValidator.validateForm();
    }
}

function cartManagement() {
    // écouteur d'évênement sur le bouton addToCart

    if (typeof Cart == "function"){
        var cart = new Cart();
        cart.init();
    }
}

/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////

$(function () {

    // affichage des erreurs dans les formulaires
    var errorMessage = $('.error-message');
    if (errorMessage.find('li').length > 0) {
        errorMessage.fadeIn();
    }

    // affichage du flashBag
    var notice = $('.notice');
    if (notice.find('p').length) {
        notice.delay(2345).fadeOut(3210);
    }

    // vérification des formulaires
    checkForms();

    // gestion du panier
    cartManagement();
});