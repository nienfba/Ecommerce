'use strict';

/** Class Panier - Gestion du panier
 * 
 */
let Panier = function () {
    /** 
     * @var array collections d'item du panier
     */
    this.items = new Array();

    /** 
     * @var DOMObject Zone entête de mise à jour du panier Quantité
     * */
    this.quantityHtml = $('#cart span i');

    /** 
    * @var DOMObject Zone entête de mise à jour du panier prixTotal
    * */
    this.priceHtml = $('#cart strong');

    /** 
     * @var DOMObject Tableau pour l'affichage du panier
     * */
    this.tableHtml = $('#cartTable tbody');

    /**
     * @var DOMObject Zone pour le prix total dans le tableau
     * */
    this.totalPriceHtml = $('#cartTable tfoot th.total');

    /**
     * @var DOMObject Zone pour le prix total dans le tableau
     * */
    this.modalConfirm = $('#confirm');


    /** Gestion du panier - Update affichage et evenement ajout panier
     * On met le gestionnaire ici pour pouvoir le binder avec le contexte !
     * **************************************************************
     */
    $('#addCart').on('click', this.add.bind(this));

    /** Click suppression de produit 
    * ******************************************
    */
    $(document).on('click', '.delete', this.confirmDelete.bind(this));

    /** Click confirmation suppression de produit
    * ******************************************
    */
    this.modalConfirm.find('a.valid').on('click', this.delete.bind(this));
    /** Click close confirmation suppression de produit
    * ******************************************
    */
    this.modalConfirm.find('.close').on('click', this.closeConfirm.bind(this));

    /** Click plus moins quantité panier
    * ******************************************
    */
    $(document).on('click', '#qteDown', this.updateItemQuantity.bind(this));
    $(document).on('click', '#qteUp', this.updateItemQuantity.bind(this));


    /** On charge le panier en mémoire ! */
    this.load();

}

/** Charge le panier du local storage
 * @param void
 */
Panier.prototype.load = function () {
    this.items = loadDataFromDomStorage('cart');
    if (this.items == null)
        this.items = new Array();
    /** Update display */
    this.updateDisplayResume();
    /** Display Cart if necessary */
    this.displayCart();
}

/** Ajoute un élément au panier
 * @param void
 */
Panier.prototype.add = function (e) {
    e.preventDefault();

    console.log('ici');
    /** On a bindé le contexte this donc ici on a l'objet jquery dans this mais l'objet Panier 
     * On va donc cherche dans l'évènement l'ojet jquery
    */
    let buttonAdd = $(e.currentTarget);

    /** Création d'un objet produit pour ajouter ensuite au items du panier */
    let product = new Object();
    product.name = buttonAdd.data('name');
    product.id = parseInt(buttonAdd.data('id'));
    product.picture = buttonAdd.data('picture');

    /** Si on a des variations sur le produit */
    if ($('#variation').length) {
        product.variation = $('#variation').find(':selected').data('name');
        product.variationId = parseInt($('#variation').val());
        product.unitPrice = parseFloat($('#variation').find(':selected').data('price'));
    }
    else {
        product.variation = '';
        product.variationId = null;
        product.unitPrice = buttonAdd.data('price');
    }
    product.quantity = parseInt($('#quantity').val());
    product.totalPrice = product.quantity * product.unitPrice;

    /** Product déja dans la panier (même id même id variation? */
    let alreadyInCart = false;
    this.items.forEach((prod, index) => {
        if (prod.id == product.id && prod.variationId == product.variationId) {
            alreadyInCart = true;
            this.updateItemQuantity(null, index, product.quantity);
        }
    });
    if (alreadyInCart == false)
        this.items.push(product);

    /* On réinitialise la propriété !*/
    alreadyInCart = false;

    /** On sauve le panier */
    this.save();
}

/** Enregistre le panier dans le local storage
 * @param void
 */
Panier.prototype.save = function () {
    saveDataToDomStorage('cart', this.items);

    /** Update display */
    this.updateDisplayResume();
    /** Display Cart if necessary */
    this.displayCart();
}

/** Demande la  confirmation pour Supprimer un élément du panier
 * @param void
 * @return void
*/
Panier.prototype.confirmDelete = function (e = null, id = null) {
    let deleteId;
    if (e != null) {
        deleteId = $(e.currentTarget).data('id');
        e.preventDefault();
    }
    else
        deleteId = id;
    /** On affiche la modal */
    this.modalConfirm.css('top', e.pageY - 100 + 'px');
    //this.modalConfirm.toggleClass('open');

    /*On rajoute un data id au bouton valider de la modal */
    this.modalConfirm.find('a.valid').data('id', deleteId);
}

Panier.prototype.closeConfirm = function (e = null) {
    if (e != null) {
        e.preventDefault();

    }
    /** On close le modal */
    this.modalConfirm.css('top', '-400px');
}


/** Supprime un élément du panier
 * @param e evnement qui appel le delete
 * @param id identifiant (index) de l'item à supprimer
 * @return void
*/
Panier.prototype.delete = function (e = null, id = null) {

    /** On a bindé le contexte this donc ici on a pas l'objet jquery dans this mais l'objet Panier 
     * On va donc cherche dans l'évènement l'objet jquery
     * @var integer 
    */
    let deleteId;
    if (e != null) {
        deleteId = $(e.currentTarget).data('id');
        e.preventDefault();
    }
    else
        deleteId = id;

    this.items.splice(deleteId, 1);
    /** Save */
    this.save();
    this.closeConfirm();
}

/** Mise à jour de l'affichage du panier en résumé dans le header
 * @param void
 */
Panier.prototype.updateDisplayResume = function () {
    let carTotalPrice = 0;
    this.items.forEach((product) => {
        carTotalPrice += product.totalPrice;
    });
    this.quantityHtml.text(this.items.length);
    this.priceHtml.text(formatMoneyAmount(carTotalPrice));
}

/** Mise à jour de la quantité
 * @param id index de l'item
 * @param quantity quantité à rajouter 
 */
Panier.prototype.updateItemQuantity = function (e = null, id = null, quantity = 0) {

    if (id == null)
        id = $(e.currentTarget).data('id');

    if (quantity == 0) {
        quantity = 1;
        if ($(e.currentTarget).attr('id') == 'qteDown')
            quantity = -1;
    }

    this.items[id].quantity += quantity;
    this.items[id].totalPrice = this.items[id].quantity * this.items[id].unitPrice;

    if (this.items[id].quantity <= 0) {
        this.items[id].quantity = 1;
        this.confirmDelete(e, id);
    }

    this.save();
}

/** Affiche le panier sur la page panier
 * @param void
 * @return void
*/
Panier.prototype.displayCart = function () {

    let carTotalPrice = 0;

    /** Si on a le tableau dans le DOM alors on le met à jour */
    if (this.tableHtml.length) {
        this.tableHtml.empty();
        if (this.items.length > 0) {
            this.items.forEach((product, index) => {
                this.tableHtml.append($('<tr>').append(
                    $('<td>').html(`<img src="${getWwwUrl()}/uploads/products/${product.picture}" width="100">`),
                    $('<td>').text(`${product.name} - ${product.variation}`),
                    $('<td>').html(`${product.quantity} <button id="qteDown" data-id="${index}">-</button><button id="qteUp" data-id="${index}">+</button>`),
                    $('<td>').text(`${formatMoneyAmount(product.unitPrice)}`),
                    $('<td>').text(`${formatMoneyAmount(product.totalPrice)}`),
                    $('<td>').html(`<button class="delete" data-id="${index}"><i class="fa fa-trash"></i><a/></button>`)
                ));

                carTotalPrice += product.totalPrice;
            });
            this.totalPriceHtml.text(`${formatMoneyAmount(carTotalPrice)}`);
            return;
        }

        //Si pas d'item panier vide
        this.tableHtml.append($('<tr>').append($('<td colspan="5">').text('Votre panier est vide')));
    }

}

