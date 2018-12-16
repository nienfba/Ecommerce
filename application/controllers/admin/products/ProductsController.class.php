<?php

class ProductsController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {

		$productModel = new ProductsModel();

		$products = $productModel->listAll();

		$flashbag = new FlashBag();

        return [
			'title'=>'Liste des produits',
			'active'=>'listProduct',
			'products' => $products,
			'flashbag'=> $flashbag->fetchMessages()
        ];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP POST
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
    	 */
    }
}