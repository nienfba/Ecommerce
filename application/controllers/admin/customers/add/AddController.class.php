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
			'title'=>'Ajouter un client',
            'active'=>'addCustomer',
            '_form' => new CustomersForm()
        ];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
       
        try
        {
            $customerModel = new CustomersModel();

            /** On vérifie que tous les champs sont remplis */
            foreach($formFields as $formField)
            {
                if ($formField == '')
                    throw new DomainException('Merci de remplir tous les champs !');
            }

            /** On vérifie que le client n'est pas déjà dans la base */
            if($customerModel->findByEmail($formFields['email']) !== false))
                throw new DomainException('Cet email est déjà utilisé. Merci de créer un compte avec une autre adresse ou de modifier le client existant.');


            /** Password et passwordverify */
            if($formFields['password'] != $formFields['passwordVerify'])
                 throw new DomainException('Erreur de confirmation de mot de passe !');

            /** On hash le mot de passe */
            $password = password_hash($formFields['password'],PASSWORD_BCRYPT);

            /** Date de création du client */
            $createdDate = date('Y-m-d H:i:s');

            /** Ajout des données dans la BDD grâce au modèle */
            $customerModel->add($formFields['firstname'], $formFields['lastname'], $formFields['email'], $password,  $formFields['address'], $formFields['cp'], $formFields['city'], $formFields['country'], $formFields['phone'], $createdDate, $formFields['birthdate']);
            /** Falshbag */
            $flashbag = new Flashbag();
            $flashbag->add('Le client a bien été ajoutée');

            /** Redirection vers la liste des clients */
            $http->redirectTo('admin/customers/');
        }
        catch(DomainException $exception)
        {
             /** DomainException est un type d'exception prédéfinie par PHP (valeur en dehors des limites selon la doc, on l'utilise donc ici pour ça !)
             *   On a choisi ce type d'exception dans l'arbre généalogique des exceptions fournies par PHP. On aurait pu faire notre propre class
             *   Exemple : class FormValideException extends Exception {}
             */

            /** Réaffichage du formulaire avec un message d'erreur. */
            $form = new CustomersForm();
            /** On bind nos données $_POST ($formFields) avec notre objet formulaire */
            $form->bind($formFields);
            $form->setErrorMessage($exception->getMessage());
            
            return [ 
                'title'=>'Ajouter un client',
			    'active'=>'addCustomer',
                '_form' => $form 
            ]; 
        }
    }
}