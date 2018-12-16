<?php

class CategoriesController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */
		$categoryModel = new CategoriesModel();
		//Récupération des données de la BDD
		$categories = $categoryModel->listAll();
		//Création du flashbag
		$flashbag = new FlashBag();
		//création d'un tableau ->renvoie un tableau de variable pour l'envoyer à la vue
        return [
			'title'=>'Liste des catégories',
			'active'=>'listCategory',
			'categories' => $categories,
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