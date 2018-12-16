<?php

class DelController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {

        $id = $queryFields['id'];

        $productModel = new ProductsModel();
        
        /** Suppression de la photo attachée à la catégorie */
        $picture = $productModel->find($id);
        $image = $picture['prod_picture'];
        if($image != NULL && file_exists(WWW_PATH.'/uploads/products/'.$image)){
            unlink(WWW_PATH.'/uploads/products/'.$image);
        }

        /** Suppression du produit dans la base */
        $productModel->delete($id);
        
        
        /**  Création du flashbag */
        $flashbag = new Flashbag();
        $flashbag->add('Le produit et ses déclinaisons a bien été supprimée');
        
        /** Redirige vers la liste des catégories */
        $http->redirectTo('admin/products/');

    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        /** Rien ici on ne bient pas sur cette page en POST */
    }

}