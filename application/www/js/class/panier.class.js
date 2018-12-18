'use strict';


let Panier = function () {
    this.items = new Array();
    this.load();
}

Panier.prototype.add = function (nom = 'Test', quantity = 3) {
    let product = new Object();
    product.name = nom;
    product.quantity = quantity;

    console.log(product);

    this.items.push(product);

    saveDataToDomStorage('cart', this.items);


}

Panier.prototype.load = function () {
    this.items = loadDataFromDomStorage('cart');
}

/****DEMO */

let cart = new Panier();
cart.add('Produit32', 4);

console.log(cart.items);
cart.add('Produit2', 10);

console.log(cart.items);
console.log(cart);


