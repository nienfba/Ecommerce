<?php

class ProductController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
    	$productModel = new ProductsModel();
		$product = $productModel->find($queryFields['productid']);

		$variationModel = new VariationsModel();
		$variations = $variationModel->findByProduct($queryFields['productid']);

		/** Calcul des prix TTC pour le produit et les variation, ajout d'un index priceTTC
		 * On affichera dans la vue le prix TTC du produits ou s'il a des variations le prix de la première variation
		 */
		$product['priceTTC'] = number_format($product['prod_price'] + ($product['prod_price'] * $product['prod_tva']/100),2);

		foreach($variations as $index=>$variation)
			$variations[$index]['priceTTC'] = number_format($product['prod_price'] +$variation['prodv_price'] + (($product['prod_price'] +$variation['prodv_price']) * $product['prod_tva']/100),2);

        return [
			'product' => $product,
			'variations' => $variations
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