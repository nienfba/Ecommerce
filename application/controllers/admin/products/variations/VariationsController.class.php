<?php

class VariationsController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
		/** Get produit id */
		$productId = $queryFields['productid'];

		$variationModel = new VariationsModel();
		$variations = $variationModel->listAll($productId);

		/** On a aussi besoin du produit pour l'afficher dans le titre et garder le lien */
		$productModel = new ProductsModel();
		$product = $productModel->find($productId);

		$flashbag = new FlashBag();

        return [
			'title'=>'Liste des variations',
			'active'=>'listProduct',
			'variations' => $variations,
			'product' => $product,
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