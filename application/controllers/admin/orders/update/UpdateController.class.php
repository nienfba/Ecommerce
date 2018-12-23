<?php

class UpdateController
{
    
    public function httpGetMethod(Http $http, array $queryFields)
    {

        $orderModel = new OrdersModel();
        $order = $orderModel->find($queryFields['id']);

        $form = new OrdersForm();
        $form->bind(array('id'=>$order['ord_id'],'date'=>$order['ord_date'],'datePayment'=>$order['ord_datePayment'],'dateShipped'=>$order['ord_dateShipped'],'dateDelivery'=>$order['ord_dateDelivery'],'status'=>$order['ord_status'],'comment'=>$order['ord_comment'],'customerId'=>$order['customer_cust_id']));
	

        return [
            'title' => "Editer une commandes",
            'active' => "order",
            '_form' =>  $form
        ];
		
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
        try
        {
            $orderModel = new OrdersModel();
            $order = $orderModel->find($formFields['id']);

            /** Ajout des données dans la BDD grâce au modèle 
             * 
             * update($id, $status, $datePayment, $dateShipped, $dateDelivery, $comment, $customerId)
            */
            if($formFields['dateDelivery'] == '')
                $formFields['dateDelivery'] = null;
            if($formFields['dateShipped'] == '')
                $formFields['dateShipped'] = null;
            if($formFields['datePayment'] == '')
                $formFields['datePayment'] = null;

            if($formFields['comment'] != '')
                $formFields['comment']= date('d-m-Y H:i:s').' : '.$formFields['comment'].'<br>'.$formFields['commentOld'];
                
            $orderModel->update($formFields['id'],$formFields['status'],$formFields['datePayment'],$formFields['dateShipped'],$formFields['dateDelivery'],$formFields['comment'],$formFields['customerId'] );
            
            /** Falshbag */
            $flashbag = new Flashbag();
            $flashbag->add('La commande a bien été modifié');

            /** Redirection vers la liste des clients */
            $http->redirectTo('admin/orders/');
        }
        catch(DomainException $exception)
        {

            /** Réaffichage du formulaire avec un message d'erreur. */
            $form = new OrdersForm();
            /** On bind nos données $_POST ($formFields) avec notre objet formulaire */
            $form->bind($formFields);
            $form->setErrorMessage($exception->getMessage());
            
            return [ 
                'title'=>'Modifier une commande',
			    'active'=>'order',
                '_form' => $form 
            ]; 
        }
    }
}