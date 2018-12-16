<?php

class EditController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        $id = $queryFields['id'];
        /** Get produit id */
        $productId = $queryFields['productid'];

        /** On a aussi besoin du produit pour l'afficher dans le titre et garder le lien */
		$productModel = new ProductsModel();
        $product = $productModel->find($productId);
        
        /** Et bien sûr des données de la variations */
        $variationModel = new VariationsModel();
        $variation = $variationModel->find($id);

        $form = new VariationsForm();
        $form->bind(['name'=>$variation['prodv_name'],'price'=>$variation['prodv_price'],'quantity'=>$variation['prodv_quantity'],'id'=>$variation['prodv_id'],'productid'=>$productId]);

        return[
			'title'=>'Editer une variation',
            'active'=>'editProduct',
            'product'=>$product,
            '_form' => $form,
        ];

    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        try
        {

             /** Get produit id */
            $productId = $formFields['productid'];

            /** Vérification des données 
             * C'est le contrôleur qui contrôle les données et non le modèle !
             * Si les champs sont vides on lance un exception pour réafficher le formulaire et les erreurs !
            */
             /** On vérifie que tous les champs sont remplis sauf subtitle*/
            // var_dump($formFields);exit();
            foreach($formFields as $index=>$formField)
            {
                if ($formField == '')
                    throw new DomainException('Merci de remplir tous les champs !');
            }

            /** Enregistrer les données dans la base de données */
            $variationModel = new VariationsModel();
            $variationModel->update($formFields['id'], $formFields['name'], $formFields['price'],$formFields['quantity'], $productId);
            
            /** Ajout du flashbag */
            $flashbag = new Flashbag();
            $flashbag->add('La variation a bien été ajoutée');

            /** Redirection vers la liste des catégories */
            $http->redirectTo('admin/products/variations/?productid='.$productId);
        }
         catch(DomainException $exception)
        {
             /** On a aussi besoin du produit pour l'afficher dans le titre et garder le lien */
            $productModel = new ProductsModel();
            $product = $productModel->find($productId);

            /** Réaffichage du formulaire avec un message d'erreur. */
            $form = new VariationsForm();
            /** On bind nos données $_POST ($formFields) avec notre objet formulaire */
            $form->bind($formFields);
            $form->setErrorMessage($exception->getMessage());
            
            return [ 
                'title'=>'Ajouter une variation',
                'active'=>'addProduct',
                'product'=>$product,
                '_form' => $form,
            ]; 
        }

    }
}