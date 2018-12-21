<?php

class OrderController
{
     /**
     * STATUS DES COMMANDES GERES PAR L'APPLICATION
     * Ce n'est pas optimal. Il faudrait une table payment pour enregistrer les paiement.
     * Mais ici on va gérer simplement
     * 
     * ORDER_PENDING : commande en attente
     * PAYMENT_PENDING_CHECK : payment par chèqe en attente
     * PAYMENT_VALID_CHECK : paiement par chèque validé
     * PAYMENT_PENDING_TRANSFERT : payment par virement en attente
     * PAYMENT_VALID_TRANSFERT : paiement par virement validé
     * PAYMENT_PENDING_CB : paiement par cd validé
     * PAYMENT_VALID_CB : paiement par cd validé
     * PAYMENT_ERROR_CB : paiement par cb error
     */
    const ORDER_PENDING = 0;
    const PAYMENT_PENDING_CHECK = 1;
    const PAYMENT_VALID_CHECK = 2;
    const PAYMENT_PENDING_TRANSFERT = 3;
    const PAYMENT_VALID_TRANSFERT = 4;
    const PAYMENT_PENDING_CB = 5;
    const PAYMENT_VALID_CB = 6;
    const PAYMENT_ERROR_CB = 7;

    public function httpGetMethod(Http $http, array $queryFields)
    {
        /** On est redirigé ici une fois la commande enregistrée en base
         * On affiche la commande dans un tableau et on propose les moyens de paiement !
         */
        $userSession = new UserSession();
        $idCustomer = $userSession->getUserId();

        if(!isset($queryFields['id']))
        {
            /** Flashbag */
            $flashbag = new Flashbag();
            $flashbag->add('Une erreur s\'est produite');
            $http->redirectTo('/cart/');
        }

        $orderId = $queryFields['id'];
        /** On va chercher ici une commande avec son numéro mais aussi le numéro client
         * Comme ça pas de risque de piratage par num de commande 
         */
        $orderModel = new OrderModel();
        $order = $orderModel->findByIdAndCustomer($orderId,$idCustomer);

        if($order)
        {
            if($order['ord_status'] !=  self::ORDER_PENDING)
            {
                /** Si la commande n'est plus en attente on redirige vers la page de payment */
                $http->redirectTo('/order/payment/');
            }
            else
            {
                $orderdetailModel = new OrderdetailModel();
                $orderDetails = $orderdetailModel->findByOrder($orderId);
                $orderTotal = $orderdetailModel->getTotalPrice($orderId);
                return [ 
                    'order' => $order,
                    'orderDetails'=>$orderDetails,
                    'orderTotal' => $orderTotal['total']
                ]; 
            }
        }
        else
        {
            /** Flashbag */
            $flashbag = new Flashbag();
            $flashbag->add('Une erreur s\'est produite');
            $http->redirectTo('/cart/');
        }
        
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        $items = json_decode($formFields['cartInput']);

        if(count($items) > 0)
        {
        
            $userSession = new UserSession();
            $idCustomer = $userSession->getUserId();
            /** L'utilisateur a déjà validé sa commande
             * Si c'est le cas on la mettre à jour au lieu dans créer une nouvelle
             */
            $orderId = $userSession->getOrderId();
            
            /** Model des commandes */
            $orderModel = new OrderModel();
            
            /** Model des lignes de commandes */
            $orderdetailModel = new OrderdetailModel();

            /** Pour le moment on a pas trouvé une commande existante */
            $order = false;

            /** Si on a un orderID en mémoire session on vérifie si la commande est déjà en base */
            if(!is_null($orderId))
                $order = $orderModel->find($orderId);
            
            /** Si on a trouvé la commande en base */
            if($order)
            {
                //...on supprime tous les détails
                $orderdetailModel->deletebyOrder($orderId);
            }
            else
                 $orderId = $orderModel->add(date('Y-m-d h:i:s'),self::ORDER_PENDING, null, null, '', $idCustomer);
               
            /** On ajoute les détails des lignes commande
             * 
             */
            foreach($items as $item)
                $orderdetailModel->add($item->quantity,$item->unitPrice,$orderId,$item->id,$item->variationId);
            
            /** 
             * On enregistre la commande en Session en cas de changement ! */
            $userSession->createOrder($orderId);

            /** On redirige sur la page order pour éviter le double POST 
             * 
             */
            $http->redirectTo('/order/?id='.$orderId);
        }
        else
        {
             /** Flashbag */
            $flashbag = new Flashbag();
            $flashbag->add('Une erreur s\'est produite');

            $http->redirectTo('/cart/');
        }
       
    }

}