<?php

class AdminController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
		/** On sélectionne les commandes en bases par ordre de date décroissant 
		 * TO DO !
		*/

		/** On sélectionne les derniers clients enregistrés !
		 * TO DO !
		*/
		

           return [
            'title' => "Administration",
            'active' => "home"
        ];
		
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    
    }
}