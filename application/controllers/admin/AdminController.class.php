<?php

class AdminController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
		/** On sélectionne les commandes en bases par ordre de date décroissant 
		 * TO DO !
		*/
        $orderModel = new OrdersModel();
        $orders = $orderModel->listAll();

        
		

        return [
            'title' => "Accueil - Dashboard",
            'active' => "home",
            'orders'=>$orders
        ];
		
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    
    }
}