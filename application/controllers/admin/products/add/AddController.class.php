<?php

class AddController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
       
        /** On sélectionne toutes les catégories pour les afficher dans le form */
        $modelCat = new CategoriesModel();
        $categories = $modelCat->listAll();


        return [
			'title'=>'Ajouter un produit',
            'active'=>'addProduct',
            '_form' => new ProductsForm(),
            'categories' => $categories
        ];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
       
        try
        {
            /** Image uploadée
            *   On la déplace sinon on affecte à NULL pour la saisie en base
            */
            if ($http->hasUploadedFile('picture'))
                $picture = $http->moveUploadedFile('picture','/uploads/products');
            else 
                $picture = NULL;


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

            $createdDate = date('Y-m-d');

            /** Enregistrer les données dans la base de données */
            $productModel = new ProductsModel();
            $productModel->add($formFields['name'], $formFields['subtitle'],$formFields['description'], $createdDate,$formFields['price'],$formFields['tva'], $picture,$formFields['categoryId']);
            
            /** Ajout du flashbag */
            $flashbag = new Flashbag();
            $flashbag->add('Le produit a bien été ajoutée');

            /** Redirection vers la liste des catégories */
            $http->redirectTo('admin/products/');
        }
        catch(DomainException $exception)
        {
             /** DomainException est un type d'exception prédéfinie par PHP (valeur en dehors des limites selon la doc, on l'utilise donc ici pour ça !)
             *   On a choisi ce type d'exception dans l'arbre généalogique des exceptions fournies par PHP. On aurait pu faire notre propre class
             *   Exemple : class FormValideException extends Exception {} et faire ensuite un catch(FormValideException $exception)
             */

              /** On sélectionne toutes les catégories pour les afficher dans le form */
            $modelCat = new CategoriesModel();
            $categories = $modelCat->listAll();

            /** Réaffichage du formulaire avec un message d'erreur. */
            $form = new ProductsForm();
            /** On bind nos données $_POST ($formFields) avec notre objet formulaire */
            $form->bind($formFields);
            $form->setErrorMessage($exception->getMessage());
            
            return [ 
                'title'=>'Ajouter un produit',
			    'active'=>'addProduct',
                '_form' => $form,
                'catgories' => $categories
            ]; 
        }
    }
}