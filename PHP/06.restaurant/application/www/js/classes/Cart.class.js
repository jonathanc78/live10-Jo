"use strict";


var Cart = function () {
    this.cart = $('#cart');
    this.cart_item = $('#cart-item').find('span');
};

// permet d'insérer un message dans le panier
Cart.prototype.createMessage = function (target, message) {
    var tr = $('<tr>').append(
        $('<td>').attr('colspan', 4).addClass('center').text(message)
    );
    target.append(tr)
};

// met à jour les prix totaux
Cart.prototype.updateTotalPrice = function (htPrice, tva, ttcPrice) {
    this.cart.find('#htPrice').text(formatMoneyAmount(htPrice));
    this.cart.find('#tva').text(formatMoneyAmount(tva));
    this.cart.find('#ttcPrice').text(formatMoneyAmount(ttcPrice));
};

Cart.prototype.updateCartQuantity = function (quantity) {
    this.cart_item.text(quantity);
};

// affichage des données en retour d'ajax
Cart.prototype.onAjaxSuccess = function (answer) {
    var meal_tr, quantity_input;

    // identification des éléments du dom
    meal_tr = this.cart.find('#' + answer['mealId']);
    quantity_input = meal_tr.find('.quantity input');

    // mise à jour des quantités
    quantity_input.val(answer['quantityInCart']);
    this.updateCartQuantity(answer['totalQuantityInCart']);

    // suppression d''une ligne quand plus de produits
    if (answer['quantityInCart'] == 0) {
        meal_tr.remove();
    }

    // ajout d'un message quand panier vide
    if (answer['totalQuantityInCart'] == 0) {
        this.createMessage(this.cart, "Vous n'avez pas encore de produit dans votre panier");
    }

    // mise à jour des totaux
    this.updateTotalPrice(answer['htPrice'], answer['tva'], answer['ttcPrice']);
};

// lancement de la requête ajax sur l'ajout d'un produit au panier depuis la page d'accueil
Cart.prototype.onHomeAjaxSuccess = function (answer) {
    event.preventDefault();

    // mise à jour du récapitulatif panier
    this.updateCartQuantity(answer['totalQuantityInCart']);

    // affichage du petit  modal
    $('#modal').text(answer['quantity'] + ' ' + answer['mealName'] + ' ont été ajouté(s) au panier')
        .fadeIn().delay(3000).fadeOut();
};

// lancement de la requête ajax
Cart.prototype.onClickAddToCart = function (event) {
    event.preventDefault();

    // on identifie si la provenance est "home" ou pas.
    var callback = event.currentTarget.dataset.home ? this.onHomeAjaxSuccess : this.onAjaxSuccess;

    // lancement de la requête ajax
    $.get(event.currentTarget.href, 'ajax', callback.bind(this), 'json');
};

Cart.prototype.onAjaxChangeInput = function (answer) {
    // mise à jour des totaux
    this.updateCartQuantity(answer['totalQuantityInCart']);
    this.updateTotalPrice(answer['htPrice'], answer['tva'], answer['ttcPrice']);
};

// lorsqu'on change manuellement les quantités dans l'input
Cart.prototype.onChangeInput = function (event) {
    var datas = {
        action: 'updateQuantity',
        mealId: event.currentTarget.dataset.mealId,
        quantity: parseInt(event.currentTarget.value),
        ajax: true
    };

    $.get(getRequestUrl(), datas, this.onAjaxChangeInput.bind(this), 'json');
};

Cart.prototype.init = function () {
    $('.add-to-cart').click(this.onClickAddToCart.bind(this));

    // on utilise la méthode jQuery .bind qui déclenche  l'évênement quand on change sa valeur
    $('input').blur(this.onChangeInput.bind(this))

};