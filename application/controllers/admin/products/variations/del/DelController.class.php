<?php

class DelController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {

        $id = $queryFields['id'];
        $productid = $queryFields['productid'];

        $variationModel = new VariationsModel();
        

        /** Suppression de la variation dans la base */
        $variationModel->delete($id);
        
        
        /**  Création du flashbag */
        $flashbag = new Flashbag();
        $flashbag->add('La variation a bien été supprimée');
        
        /** Redirige vers la liste des catégories */
        $http->redirectTo('admin/products/variations/?productid='.$productid);

    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        /** Rien ici on ne bient pas sur cette page en POST */
    }

}