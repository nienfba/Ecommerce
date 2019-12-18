<?php

class AddController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        /** En méthode GET on passe les variables nécessaire à la vue pour éviter les erreurs NOTICE variable undefined
         * Donc title, active (pour la page active dans le menu) et le formulaire pour que le framework 
         * nous fournisse les variables du formulaie dans la vue
         */
        return [
			'title'=>'Ajouter une catégorie',
            'active'=>'addCategory',
            '_form' => new CategoriesForm()
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
                $picture = $http->moveUploadedFile('picture','/uploads/categories');
            else 
                $picture = NULL;

            /** Vérification des données 
             * C'est le contrôleur qui contrôle les données et non le modèle !
             * Si les champs sont vides on lance un exception pour réafficher le formulaire et les erreurs !
            */
            if($formFields['name'] == '' ||  $formFields['contents'] == '')
                throw new DomainException('Merci de saisir le champ nom et contenu !');

            /** Enregistrer les données dans la base de données */
            $categoryModel = new CategoriesModel();
            $categoryModel->add($formFields['name'], $formFields['contents'], $picture);
            
            /** Ajout du flashbag */
            $flashbag = new Flashbag();
            $flashbag->add('La catégorie a bien été ajoutée');

            /** Redirection vers la liste des catégories */
            $http->redirectTo('admin/categories/');
        }
        catch(DomainException $exception)
        {
             /** DomainException est un type d'exception prédéfinie par PHP (valeur en dehors des limites selon la doc, on l'utilise donc ici pour ça !)
             *   On a choisi ce type d'exception dans l'arbre généalogique des exceptions fournies par PHP. On aurait pu faire notre propre class
             *   Exemple : class FormValideException extends Exception {} et faire ensuite un catch(FormValideException $exception)
             */

            /** Réaffichage du formulaire avec un message d'erreur. */
            $form = new CategoriesForm();
            /** On bind nos données $_POST ($formFields) avec notre objet formulaire */
            $form->bind($formFields);
            $form->setErrorMessage($exception->getMessage());
            
            return [ 
                'title'=>'Ajouter une catégorie',
			    'active'=>'addCategory',
                '_form' => $form 
            ]; 
        }
    }
}