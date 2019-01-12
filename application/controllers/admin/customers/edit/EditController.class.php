<?php

class EditController
{
    public function httpGetMethod(Http $http, array $queryFields)
    {
    
        $id = $queryFields['id'];

        $customerModel = new CustomersModel();
        $customer = $customerModel->find($id);

        $form = new CustomersForm();
        $form->bind(array('id'=>$customer['cust_id'],'firstname'=>$customer['cust_firstname'], 'lastname'=>$customer['cust_lastname'], 'email'=>$customer['cust_email'], 'address'=>$customer['cust_address'], 'cp'=>$customer['cust_cp'], 'city'=>$customer['cust_city'], 'country'=>$customer['cust_country'], 'phone'=>$customer['cust_phone'], 'birthdate'=>$customer['cust_birthday']));
	
        return[
			'title'=>'Editer un client',
			'active'=>'editCustomer',
            '_form' => $form
        ];

    }

    public function httpPostMethod(Http $http, array $formFields)
    {
             
        try
        {
            $customerModel = new CustomersModel();


            /** On vérifie que tous les champs sont remplis sauf le mot de passe car on peut ne pas le modifier */
            foreach($formFields as $index=>$formField)
            {
                if ($index != 'password' && $index != 'passwordVerify' && empty($formField))
                    throw new DomainException('Merci de remplir tous les champs !'.$index);
            }
            /** Password et passwordverify */
            if($formFields['password'] != $formFields['passwordVerify'])
                throw new DomainException('Erreur de confirmation de mot de passe !');

            /** On hash le mot de passe s'il n'est pas vide sinon NULL */
            if($formFields['password']!= '')
                $password = password_hash($formFields['password'],PASSWORD_BCRYPT);
            else
                $password = NULL;

            /** Ajout des données dans la BDD grâce au modèle */
            $customerModel->update($formFields['id'], $formFields['firstname'], $formFields['lastname'], $formFields['email'], $password,  $formFields['address'], $formFields['cp'], $formFields['city'], $formFields['country'], $formFields['phone'], $formFields['birthdate']);
            
            /** Falshbag */
            $flashbag = new Flashbag();
            $flashbag->add('Le client a bien été modifié');

            /** Redirection vers la liste des clients */
            $http->redirectTo('admin/customers/');
         }
        catch(DomainException $exception)
        {
             /** DomainException est un type d'exception prédéfinie par PHP (valeur en dehors des limites selon la doc, on l'utilise donc ici pour ça !)
             *   On a choisi ce type d'exception dans l'arbre généalogique des exceptions fournies par PHP. On aurait pu faire notre propre class
             *   Exemple : class FormValideException extends Exception {}
             */

            /** Réaffichage du formulaire avec un message d'erreur. */
            $form = new CustomersForm();
            /** On bind nos données $_POST ($formFields) avec notre objet formulaire */
            $form->bind($formFields);
            $form->setErrorMessage($exception->getMessage());
            
            return [ 
                'title'=>'Editer un client',
			    'active'=>'addCustomer',
                '_form' => $form 
            ]; 
        }
    }
}