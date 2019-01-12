<?php
/** Ce contrôleur va permettre à un client de créer son compte ! */
class CreateController
{
     public function httpGetMethod(Http $http, array $queryFields)
    {
        /** En méthode GET on passe les variables nécessaire à la vue pour éviter les erreurs NOTICE variable undefined
         * Donc title, active (pour la page active dans le menu) et le formulaire pour que le framework 
         * nous fournisse les variables du formulaie dans la vue
         */
        return [
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
            if($customerModel->findByEmail($formFields['email']) !== false)
                throw new DomainException('Cet email est déjà utilisé. Merci de créer un compte avec une autre adresse de vous connecter avec votre email.');

            /** Password et passwordverify */
            if($formFields['password'] != $formFields['passwordVerify'])
                 throw new DomainException('Erreur de confirmation de mot de passe !');

            /** On hash le mot de passe */
            $password = password_hash($formFields['password'],PASSWORD_BCRYPT);

            /** Date de création du client */
            $createdDate = date('Y-m-d H:i:s');

            /** Ajout des données dans la BDD grâce au modèle */
            $customerId = $customerModel->add($formFields['firstname'], $formFields['lastname'], $formFields['email'], $password,  $formFields['address'], $formFields['cp'], $formFields['city'], $formFields['country'], $formFields['phone'], $createdDate, $formFields['birthdate']);
            
            $customer = $customerModel->find($customerId);
            /** Falshbag */
            /** On logue le client */
             // Construction de la session utilisateur.
            $userSession = new UserSession();
            $userSession->create
            (
                $customer['cust_id'],
                $customer['cust_firstname'],
                $customer['cust_lastname'],
                $customer['cust_email']
            );

            /** Falshbag */
            $flashbag = new Flashbag();
            $flashbag->add('Vous êtes maintenant connecté !');

            /** Redirection vers la liste des clients */
            $http->redirectTo('cart/');
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
                '_form' => $form 
            ]; 
        }
    }
}