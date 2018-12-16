<?php

class AddController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        
        /** Get produit id */
        $productId = $queryFields['productid'];
        /** On a aussi besoin du produit pour l'afficher dans le titre et garder le lien */
		$productModel = new ProductsModel();
		$product = $productModel->find($productId);

        $form = new VariationsForm();
        $form->bind(['productid'=>$productId]);
        
        return [
			'title'=> 'Ajouter une variation',
            'active'=>'addProduct',
            'product'=>$product,
            '_form' => $form
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
            $variationModel->add($formFields['name'], $formFields['price'],$formFields['quantity'], $productId);
            
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