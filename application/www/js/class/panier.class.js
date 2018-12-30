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

    /** Déclanche les évènements */
    this.setEvents();

    /** On charge le panier en mémoire ! */
    this.load();

}

/** Déclanche les évènement pour la gestion du panier 
 *  @param void
*/
Panier.prototype.setEvents = function () {

    /** Gestion du panier - Ajout au panier */
    $('#addCart').on('click', this.add.bind(this));

    /** Click suppression de produit */
    $(document).on('click', '.delete', this.confirmDelete.bind(this));

    /** Click confirmation suppression de produit */
    this.modalConfirm.find('a.valid').on('click', this.delete.bind(this));

    /** Click close confirmation suppression de produit */
    this.modalConfirm.find('.close').on('click', this.closeConfirm.bind(this));

    /** Click plus moins quantité panier */
    $(document).on('click', '#qteDown', this.updateItemQuantity.bind(this));
    $(document).on('click', '#qteUp', this.updateItemQuantity.bind(this));

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
 * @param e event evènement click
 */
Panier.prototype.add = function (e) {
    e.preventDefault();

    /** On a bindé le contexte this donc ici on a pas l'objet jquery dans this mais l'instance du Panier
     * On va donc cherche dans l'évènement passé en paramètre l'objet jquery pour récupérer la cible
    */
    let buttonAdd = $(e.currentTarget);

    /** Création d'un objet produit pour ajouter ensuite au items du panier */
    let product = new Object();
    product.name = buttonAdd.data('name');
    product.id = parseInt(buttonAdd.data('id'));
    product.picture = buttonAdd.data('picture');

    /** Si on a des variations sur le produit 
     * On va cherche le prix de la variation, sinon on prend le prix du produit
    */
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

    console.log(buttonAdd.val());

    /** Si pas déjà dans le panier on l'ajoute ! */
    if (alreadyInCart == false) {
        this.items.push(product);
        /** On affiche un message pour confirmer l'ajout au panier */
        buttonAdd.text('Ajout au panier réussit !');
    }
    else
        buttonAdd.text('Modification du panier réussit !');

    /** On réinitialise le bouton après 3 secondes ! */
    window.setTimeout(function () { buttonAdd.text('Ajouter au panier'); }, 3000);

    /** On sauve le panier */
    this.save();
}

/** Enregistre le panier dans le local storage
 * @param void
 */
Panier.prototype.save = function () {

    /** Enregistrement dans le storage local */
    saveDataToDomStorage('cart', this.items);

    /** Update display */
    this.updateDisplayResume();

    /** Display Cart if necessary */
    this.displayCart();
}

/** Demande la  confirmation pour Supprimer un élément du panier
 * @param e event évènement si la suppression vient d'un click
 * @param id integer identifiant de l'élément à supprimer
 * @return void
*/
Panier.prototype.confirmDelete = function (e = null, id = null) {

    let deleteId;

    /** Si on a un évènement on récupère l'id dans les dataset du bouton 
     * Sinon l'id doit nous être transmis en paramère
    */
    if (e != null) {
        deleteId = $(e.currentTarget).data('id');
        e.preventDefault();
    }
    else
        deleteId = id;

    /** On affiche la modal pour demander la confirmatrion de suppresion */
    this.modalConfirm.css('top', e.pageY - 100 + 'px');

    /** On rajoute un data id au bouton valider de la modal 
     * Pour que 
    */
    this.modalConfirm.find('a.valid').data('id', deleteId);
}


/** Ferme la modal
 * @param void
 */
Panier.prototype.closeConfirm = function (e = null) {

    /** Si on a un évènement on stop sa propagation */
    if (e != null)
        e.preventDefault();

    /** On close le modal */
    this.modalConfirm.css('top', '-400px');
}


/** Supprime un élément du panier
 * @param e event evènement qui appel le delete
 * @param id integer identifiant (index) de l'item à supprimer
 * @return void
*/
Panier.prototype.delete = function (e = null, id = null) {

    let deleteId;

    if (e != null) {
        deleteId = $(e.currentTarget).data('id');
        e.preventDefault();
    }
    else
        deleteId = id;

    /** On supprime l'élément dans les items */
    this.items.splice(deleteId, 1);

    /** On sauve les items dans le dataStorage */
    this.save();

    /** On ferme la modal */
    this.closeConfirm();
}

/** Mise à jour de l'affichage du panier en résumé dans le header
 * @param void
 */
Panier.prototype.updateDisplayResume = function () {

    /** On calcule le prix total du panier */
    let carTotalPrice = 0;
    this.items.forEach((product) => {
        carTotalPrice += product.totalPrice;
    });

    /** On met à jour le DOM - Zone HTML du panier */
    this.quantityHtml.text(this.items.length);
    if (carTotalPrice > 0)
        this.priceHtml.text(formatMoneyAmount(carTotalPrice));
    else
        this.priceHtml.text('');
}

/** Mise à jour de la quantité
 * @param id index de l'item
 * @param quantity quantité à rajouter 
 */
Panier.prototype.updateItemQuantity = function (e = null, id = null, quantity = 0) {

    /** Si c'est un evnt qui nous emmène ici on récupère l'id de l'item dans le DataSet */
    if (id == null && e != null)
        id = $(e.currentTarget).data('id');

    /** Si la quantité n'est pas définie on la fixe a +1 ou -1 selon d'où vient l'évènement (up and down quantity) */
    if (quantity == 0 && e != null) {
        quantity = 1;
        if ($(e.currentTarget).attr('id') == 'qteDown')
            quantity = -1;
    }

    /** On met à jour la quantité de l'item */
    this.items[id].quantity += quantity;

    /** Si on atteind une quantité à 0 on propose de supprimer le produit */
    if (this.items[id].quantity <= 0) {
        this.items[id].quantity = 1;
        this.confirmDelete(e, id);
    }

    /** On met à jour le prix total de l'item */
    this.items[id].totalPrice = this.items[id].quantity * this.items[id].unitPrice;


    /** On enregistre dans le local storage */
    this.save();
}

/** Affiche le panier sur la page panier uniquement si l'objet DOM this.tableHtml existe !
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
        /** On cache le button commander si panier vide */
        $('#cartSubmit').hide();
        this.tableHtml.append($('<tr>').append($('<td colspan="6">').text('Votre panier est vide')));
    }

}

