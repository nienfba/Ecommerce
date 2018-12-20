<?php

class LogoutController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
        $userSession = new UserSession();
        $userSession->destroy();
        /** Redirection vers la liste des clients */
        $http->redirectTo('/');
    }

    public function httpPostMethod(Http $http, array $formFields)
    {
    }

}