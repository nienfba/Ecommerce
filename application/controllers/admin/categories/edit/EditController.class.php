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
        $categoryModel = new CategoriesModel();
        $categorie = $categoryModel->find($id);

        $form = new CategoriesForm();
        $form->bind(array('name'=>$categorie['cat_name'],'contents'=>$categorie['cat_description'],'id'=>$categorie['cat_id'],'originalpicture'=>$categorie['cat_picture']));
	
        return[
			'title'=>'Editer une catégorie',
			'active'=>'editCategory',
            '_form' => $form
        ];

    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        try
        {
            /** Récupération de la photo originale */
            if ($http->hasUploadedFile('picture')) {
                $picture = $http->moveUploadedFile('picture','/uploads/categories'); //On déplace la photo à l'endroit désiré(le chemin est relatif par rapport au dossier www)et on stocke dans la variable photo le nom du fichier
                /** On supprime l'ancienne image */
                if($formFields['originalpicture']!=NULL && file_exists(WWW_PATH.'/uploads/categories/'.$formFields['originalpicture'])){
                    unlink(WWW_PATH.'/uploads/categories/'.$formFields['originalpicture']);
                }
            } else {
                $picture = $formFields['originalpicture']; // Le nom de l'image reste le nom qui était là à l'origine
            }
            
            /** Vérification des données 
             * C'est le contrôleur qui contrôle les données et non le modèle !
             * Si les champs sont vides on lance un exception pour réafficher le formulaire et les erreurs !
            */
            if($formFields['name'] == '' ||  $formFields['contents'] == '')
                throw new DomainException('Merci de saisir le champ nom et contenu !');
            if(!isset($formFields['id']) || $formFields['id'] == '')
                throw new DomainException('Une erreur inatendu s\'est produite, la catégorie ne peut-être éditée !');
            
            /** Enregistrer les données dans la base de données */
            $categoryModel = new CategoriesModel();
            $categoryModel->update($formFields['id'], $formFields['name'], $formFields['contents'], $picture);
            
            /** Ajout du flashbag */
            $flashbag = new Flashbag();
            $flashbag->add('La catégorie a bien été modifiée');
            
            /** Redirection vers la liste */
            $http->redirectTo('admin/categories/');
        }
         catch(DomainException $exception)
        {
            /** DomainException est un type d'exception prédéfinie par PHP (valeur en dehors des limites selon la doc, on l'utilise donc ici pour ça !)
             *   On a choisi ce type d'exception dans l'arbre généalogique des exceptions fournies par PHP. On aurait pu faire notre propre class
             *   Exemple : class FormValideException extends Exception {}
             */

            /** Réaffichage du formulaire avec un message d'erreur. */
            $form = new CategoriesForm();
            /** On bind nos données $_POST ($formFields) avec notre objet formulaire */
            $form->bind($formFields);
            $form->setErrorMessage($exception->getMessage());

            return [ 
                'title'=>'Editer une catégorie',
			    'active'=>'editCategory',
                '_form' => $form 
            ]; 
        }

    }
}