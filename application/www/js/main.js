'use strict';

/////////////////////////////////////////////////////////////////////////////////////////
// FONCTIONS                                                                           //
/////////////////////////////////////////////////////////////////////////////////////////



/////////////////////////////////////////////////////////////////////////////////////////
// CODE PRINCIPAL                                                                      //
/////////////////////////////////////////////////////////////////////////////////////////

/**
  * @var Panier cart panier 
  */
let cart;

$(function () {

    cart = new Panier();

    /** Update product variations on page Product 
     * ******************************************
    */
    $('#variation').on('change', function () {
        $('#price span').text($(this).find(':selected').data('price'));
    });

    /** Validation du panier - Submit form to send json cart
     * ******************************************
    */
    $("#formCart").submit(function () {
        $('#cartInput').val(JSON.stringify(cart.items));
    });

});


