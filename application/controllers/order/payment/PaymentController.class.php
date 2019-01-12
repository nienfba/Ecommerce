<?php

class PaymentController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        if(!isset($queryFields['dataBankResponse']))
        {
            $userSession = new UserSession();
            $idCustomer = $userSession->getUserId();
            /** L'utilisateur a déjà validé sa commande
             * Si c'est le cas on la mettre à jour au lieu dans créer une nouvelle
             */
            $orderId = $userSession->getOrderId();

            /** On aura besoin de revenir sur cette page mais la commande en session n'existe plus */
            if($orderId == null && !isset($queryFields['orderId']))
                $http->redirectTo('/');
            elseif(isset($queryFields['orderId']))
                $orderId = $queryFields['orderId'];

            /** Model des commandes */
            $orderModel = new OrdersModel();
            
            /** On vérifie si on a la commande en base */
            if($order = $orderModel->findByIdAndCustomer($orderId,$idCustomer))
            {
                $orderdetailModel = new OrdersdetailModel();
                $orderTotal = $orderdetailModel->getTotalPrice($orderId);

                /** On valide un payement en attente s'il n'y en pas déjà eu ici ! */
                if(isset($queryFields['payment']) && $order['ord_status']==OrderStatus::ORDER_PENDING)
                {
                    /** On regarde le type de payment proposé */
                    switch ($queryFields['payment'])
                    {
                        case 'check':
                            $orderModel->updatePayment($orderId, OrderStatus::PAYMENT_PENDING_CHECK);
                            break;
                        case 'transfert':
                            $orderModel->updatePayment($orderId, OrderStatus::PAYMENT_PENDING_TRANSFERT);
                            break;
                        case 'cb':
                            $orderModel->updatePayment($orderId, OrderStatus::PAYMENT_PENDING_CB);
                            $http->redirectTo('http://urldepaiement?valueEncrypt=encrypt');
                            break;
                        case 'default':
                            $infoView = $order['status'];
                    }
                    /** On recharge la commande pour avoir le bon status sur la page View*/
                    $order = $orderModel->findByIdAndCustomer($orderId,$idCustomer);

                    /** On envoie un email à l'utilisateur pour récapituler tout ça !! 
                     * mail(); ou phpMailer (librairie !)
                    */


                    //On passe la session de commande à null
                    $userSession->createOrder(null);
                }

                return [ 
                    'order' => $order,
                    'orderTotal' => $orderTotal['total'],
                ]; 
            }
            else
            {
                /** Flashbag */
                $flashbag = new Flashbag();
                $flashbag->add('Une erreur s\'est produite');

                $http->redirectTo('/cart/');
            }
        }
        else
        {
            /** On a retour de la banque pour la paiement de la commande en CB
             * On décrypt la chaine fournie pour valider ou non la paiement par CB
             * On met à jour la base
             * On Affiche un message
             */

            /** On envoie un email à l'utilisateur pour récapituler sa commande 
                 * mail(); ou phpMailer (librairie !)
                */


        }  
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    }

}