<?php

class ListeController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {

		$categoryModel = new CategoriesModel();
        $categories = $categoryModel->listAll();
		
		/** Pour chaque catégorie on va chercher les produis associés et on rajoute un index 'products[cat_id]'=> {liste produit} 
		 * Cette solution est bien prise de tête  mais on respecte ici la place du contrôleur
		 * On aurait pu faire un accès au modèle et un calcul du prix dans la vue... voir commentaire dans la vue !
		 * De mon côté je préfère bien séparer les tâches, le controleur garde le controle sur les données
		*/
		$productModel = new ProductsModel();
		$products = array();
		foreach($categories as $categorie)
		{
			$products[$categorie['cat_id']] = $productModel->findByCategory($categorie['cat_id']);
			/** On calcule le prix TTC à partir de du produit et on le met dans le tableau à l'index priceTTC */
			foreach($products[$categorie['cat_id']] as $index=>$product)
				$products[$categorie['cat_id']][$index]['priceTTC'] = number_format($product['prod_price'] + ($product['prod_price'] * $product['prod_tva']/100),2);
		}
		

        return [
			'categories' => $categories,
			'products' => $products
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