<?php

class OrderStatus
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

    public static function getStatusText($status)
    {
        switch ($status)
        {
            case self::ORDER_PENDING :
                return 'Commande en attente de paiement';
                break;
            case self::PAYMENT_PENDING_CHECK:
                return 'Attente de paiement par chèque';
                break;  
            case self::PAYMENT_VALID_CHECK:
                return 'Paiement validé par chèque';
                break;  
            case self::PAYMENT_PENDING_TRANSFERT:
                return 'Attente de paiement par virement';
                break;  
            case self::PAYMENT_VALID_TRANSFERT:
                return 'Paiement validé par virement';
                break;  
            case self::PAYMENT_PENDING_CB:
                return 'Attente de paiement par CB';
                break;  
            case self::PAYMENT_VALID_CB:
                return 'Paiement validé par CB';
                break;  
            case self::PAYMENT_ERROR_CB:
                return 'Erreur de paiement validé par CB';
                break;
        }
    }

    /** Return an HTML option liste for form update */
    public static function getStatusOption($currentStatus)
    {
        $oClass = new ReflectionClass(__CLASS__);
        $status =  $oClass->getConstants();

        $htmlOption = '';
        foreach($status as $const=>$val)
        {
            $htmlOption.= '<option value="'.$val.'" '.(($val == $currentStatus)?'selected':'').'>'.self::getStatusText($val).'</option>';
        }
        return $htmlOption;
    }
}
