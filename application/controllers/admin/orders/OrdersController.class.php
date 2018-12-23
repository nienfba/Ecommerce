<?php

class OrdersController
{
    
    public function httpGetMethod(Http $http, array $queryFields)
    {
		/** On sélectionne les commandes en bases par ordre de date décroissant 
		 * TO DO !
        */

        $orderModel = new OrdersModel();
        $orders = $orderModel->listAll();

        $flashbag = new FlashBag();

        return [
            'title' => "Toutes les commandes",
            'active' => "order",
            'orders'=> $orders,
            'flashbag'=> $flashbag->fetchMessages()
        ];
		
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    
    }
}