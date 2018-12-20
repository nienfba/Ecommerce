<?php
/** Ce contrôleur va permettre à un client de créer son compte ! */
class CustomerController
{
     public function httpGetMethod(Http $http, array $queryFields)
    {
        return [
            '_form' => new CustomersForm()
        ];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
       
        try
        {
            $customerModel = new CustomersModel();

            /** On vérifie que email et password fournis */
            if($formFields['email'] == '' || $formFields['password']=='')
                throw new DomainException('Merci de remplir tous les champs !');


            /** Ajout des données dans la BDD grâce au modèle */
            $customer = $customerModel->findByEmail($formFields['email']);

            if(!$customer || !password_verify($formFields['password'],$customer['cust_password']))
                throw new DomainException('Email ou mot de passe incorrects !');

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