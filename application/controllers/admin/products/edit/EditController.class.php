<?php

class EditController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
    	/*
    	 * Méthode appelée en cas de requête HTTP GET
    	 *
    	 * L'argument $http est un objet permettant de faire des redirections etc.
    	 * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
    	 */
        $id = $queryFields['id'];
        //Récupération des données dans la vue
        $productModel = new ProductsModel();
        $product = $productModel->find($id);

        /** On sélectionne toutes les catégories pour les afficher dans le form */
        $modelCat = new CategoriesModel();
        $categories = $modelCat->listAll();

        $form = new ProductsForm();
        $form->bind(array('name'=>$product['prod_name'],'subtitle'=>$product['prod_subtitle'],'description'=>$product['prod_description'],'price'=>$product['prod_price'],'tva'=>$product['prod_tva'],'categoryId'=>$product['category_cat_id'],'id'=>$product['prod_id'],'originalpicture'=>$product['prod_picture']));
	
        return[
			'title'=>'Editer un produit',
			'active'=>'editProduct',
            '_form' => $form,
            'categories' => $categories
        ];

    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        try
        {
            /** Récupération de la photo originale */
            if ($http->hasUploadedFile('picture')) {
                $picture = $http->moveUploadedFile('picture','/uploads/products'); //On déplace la photo à l'endroit désiré(le chemin est relatif par rapport au dossier www)et on stocke dans la variable photo le nom du fichier
                /** On supprime l'ancienne image */
                if($formFields['originalpicture']!=NULL && file_exists(WWW_PATH.'/uploads/products/'.$formFields['originalpicture'])){
                    unlink(WWW_PATH.'/uploads/products/'.$formFields['originalpicture']);
                }
            } else {
                $picture = $formFields['originalpicture']; // Le nom de l'image reste le nom qui était là à l'origine
            }
            
            /** Vérification des données 
             * C'est le contrôleur qui contrôle les données et non le modèle !
             * Si les champs sont vides on lance un exception pour réafficher le formulaire et les erreurs !
            */
             /** On vérifie que tous les champs sont remplis sauf subtitle*/
            foreach($formFields as $index=>$formField)
            {
                if (empty($formField) && $index != 'subtitle')
                    throw new DomainException('Merci de remplir tous les champs !');
            }

            
            /** Enregistrer les données dans la base de données */
            $productModel = new ProductsModel();
            $productModel->update($formFields['id'], $formFields['name'], $formFields['subtitle'],$formFields['description'], $formFields['price'],$formFields['tva'], $picture,$formFields['categoryId']);
            
            /** Ajout du flashbag */
            $flashbag = new Flashbag();
            $flashbag->add('Le produit a bien été modifiée');
            
            /** Redirection vers la liste */
            $http->redirectTo('admin/products/');
        }
         catch(DomainException $exception)
        {
            /** DomainException est un type d'exception prédéfinie par PHP (valeur en dehors des limites selon la doc, on l'utilise donc ici pour ça !)
             *   On a choisi ce type d'exception dans l'arbre généalogique des exceptions fournies par PHP. On aurait pu faire notre propre class
             *   Exemple : class FormValideException extends Exception {}
             */

            /** Réaffichage du formulaire avec un message d'erreur. */
            $form = new ProductsForm();
            /** On bind nos données $_POST ($formFields) avec notre objet formulaire */
            $form->bind($formFields);
            $form->setErrorMessage($exception->getMessage());

             /** On sélectionne toutes les catégories pour les afficher dans le form */
            $modelCat = new CategoriesModel();
            $categories = $modelCat->listAll();

            return [ 
               	'title'=>'Editer un produit',
			    'active'=>'editProduct',
                '_form' => $form,
                'categories' => $categories
            ]; 
        }

    }
}