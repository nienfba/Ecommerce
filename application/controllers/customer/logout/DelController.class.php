<?php

class DelController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {

        $id = $queryFields['id'];

        $customerModel = new CustomersModel();

        /** On vérifie qu'un client n'a pas de commande */
        if($customerModel->hasOrder($id))
        {
            /** Création du flashbag */
            $flashbag = new Flashbag();
            $flashbag->add('Le client a des commandes associées il ne peut-être supprimé !');
        }
        else
        {
             /** Suppression du client */
            $customerModel->delete($id);
            
            /** Création du flashbag */
            $flashbag = new Flashbag();
            $flashbag->add('Le client a bien été supprimée');
        }
        
        //redirige vers la liste des catégories
        $http->redirectTo('admin/customers/');

    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    }

}