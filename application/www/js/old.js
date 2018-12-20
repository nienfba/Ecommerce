/** Update l'affichage du panier
 * @param void 
 * @return void
 */
function updateCart() {
    let carTotalPrice = 0;
    cart.forEach((product) => {
        carTotalPrice += product.totalPrice;
    });
    //console.log(carTotalPrice)
    $('#cart span i').text(cart.length);
    $('#cart strong i').text(carTotalPrice);
}

/** Ajoute un produit au panier 
 * 
 * @param event e evènement
 * @return void
*/
function addCart(e) {
    e.preventDefault();

    let product = new Object();
    product.name = $(this).data('name');
    product.id = parseInt($(this).data('id'));

    /** Si on a des variations sur le produit */
    if ($('#variation').length) {
        product.variation = $('#variation').find(':selected').text();
        product.variationId = parseInt($('#variation').val());
        product.unitPrice = parseFloat($('#variation').find(':selected').data('price'));
    }
    else {
        product.variation = '';
        product.variationId = null;
        product.unitPrice = $(this).data('price');
    }
    product.quantity = parseInt($('#quantity').val());
    product.totalPrice = product.quantity * product.unitPrice;

    /** Product déja dans la panier ? */
    let alreadyInCart = false;
    cart.forEach((prod, index) => {
        if (prod.id == product.id && prod.variationId == product.variationId) {
            alreadyInCart = true;
            cart[index].quantity += product.quantity;
            cart[index].totalPrice = cart[index].quantity * cart[index].unitPrice;
        }
    });
    if (alreadyInCart == false)
        cart.push(product);

    /** Update display */
    updateCart();
    saveDataToDomStorage('cart', cart);
}

/** Affiche le panier sur la page panier
 * @param void
 * @return void
*/
function displayCart() {

    let carTotalPrice = 0;
    $('#cartTable tbody').empty();
    if (cart.length > 0) {
        cart.forEach((product, index) => {
            $('#cartTable tbody').append($('<tr>').append(
                $('<td>').text(`${product.name} - ${product.variation}`),
                $('<td>').text(product.quantity),
                $('<td>').text(`${formatMoneyAmount(product.unitPrice)}`),
                $('<td>').text(`${formatMoneyAmount(product.totalPrice)}`),
                $('<td>').html(`<button class="delete" data-id="${index}"><i class="fa fa-trash"></i><a/></button>`)
            ));

            carTotalPrice += product.totalPrice;
        });
        $('#cartTable tfoot th.total').text(`${formatMoneyAmount(carTotalPrice)}`);
        return;
    }

    $('#cartTable tbody').append($('<tr>').append($('<td colspan="5">').text('Votre panier est vide')));

}

/** Affiche le panier sur la page panier
 * @param void
 * @return void
*/
function deleteItemCart() {
    $('.valid').href = $(this).href();
    $('.valid').toggle('open');


    cart.splice($(this).data('id'), 1);
    updateCart();
    saveDataToDomStorage('cart', cart);
    displayCart();
}
