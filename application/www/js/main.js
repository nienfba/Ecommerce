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




    /*   //on sÃ©lectionne tous les boutons delete
      var boutonsDelete = document.querySelectorAll('.delete');
  
      //Box de confirmation
      var confirm = document.getElementById('confirm');
  
      //Les 2 boutons close de la fenÃªtre
      var close = document.querySelectorAll('.close');
  
      //Le bouton valide
      var valide = document.querySelector('.valid');
  
      //Tous les delete affichent la fenÃªtre
      boutonsDelete.forEach(function (boutonDelete) {
          boutonDelete.addEventListener('click', function (e) {
              e.preventDefault();
              valide.href = this.href;
              confirm.classList.toggle('open');
          });
      });
  
      //Tous les boutons close ferment la fenÃªtre
      close.forEach((boutonClose) => {
          boutonClose.addEventListener('click', () => {
              confirm.classList.toggle('open');
          });
      }); */

});


