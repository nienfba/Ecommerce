<?php

class CartController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        $flashbag = new FlashBag();
                
        return [
            'flashbag'=> $flashbag->fetchMessages()
        ];
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    
    }
}