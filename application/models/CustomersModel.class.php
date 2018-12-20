<?php

class CustomersModel
{
     /**
     * @var Database Objet Database pour effectuer des requête
     */
    private $dbh;

    /**
     * @var string Database table utilisée pour les requête
     */
    private $table;

    /**  Constructeur
     *
     * @param void
     * @return void
     */
    public function __construct()
    {
        $this->dbh = new Database();
        $this->table = 'customer';
    }

    /** Retourne un tableau de tous les clients en base
     *
     * @param void
     * @return Array Jeu d'enregistrement représentant tous les clients en base
     */
    public function listAll() 
    {
        return $this->dbh->query('SELECT * FROM '.$this->table);
    }

    /** Ajoute une catégorie en base
     *
     * @param string $firstname prenom
     * @param string $lastname nom
     * @param string $email
     * @param string $password
     * @param string $address
     * @param string $city
     * @param string $phone
     * @param string $createdDate
     * @param string $birthday
     * @return integer Id de l'élément enregistré
     */
    public function add($firstname, $lastname, $email, $password, $address, $cp, $city, $country, $phone, $createdDate,$birthday) 
    {
        return $this->dbh->executeSQL('INSERT INTO '.$this->table.' (cust_firstname,cust_lastname,cust_email,cust_password,cust_address,cust_cp,cust_city,cust_country,cust_phone,cust_createdDate,cust_birthday) VALUES (?,?,?,?,?,?,?,?,?,?,?)',
        [$firstname, $lastname, $email, $password, $address, $cp, $city, $country, $phone, $createdDate,$birthday]);
    }
    	
    /** Trouve une catégorie avec son ID
     *
     * @param integer $id identifiant du client
     * @return Array Jeu d'enregistrement comportant le client trouvé
     */
    public function find($id)
    {
        return $this->dbh->queryOne('SELECT * FROM '.$this->table.' WHERE cust_id = ?',[$id]);
    }

    /** Trouve un client avec son Email
     *
     * @param string $email email du client
     * @return Array Jeu d'enregistrement comportant le client trouvé
     */
    public function findByEmail($email)
    {
        return $this->dbh->queryOne('SELECT * FROM '.$this->table.' WHERE cust_email = ?',[$email]);
    }

   
    /** Modifie un client en base
     *
     * @param integer $id identifiant du client
     * @param string $firstname prenom
     * @param string $lastname nom
     * @param string $email
     * @param string $password
     * @param string $address
     * @param string $city
     * @param string $phone
     * @param string $birthday
     * @return void
     */
    public function update($id, $firstname, $lastname, $email, $password, $address, $cp, $city, $country, $phone,$birthday)
    {
        $fieldsArray = ['firstname'=>$firstname, 'lastname'=>$lastname, 'email'=>$email, 'address'=>$address, 'cp'=>$cp, 'city'=>$city, 'country'=>$country, 'phone'=>$phone,'birthday'=>$birthday, 'id'=>$id];
        $fields = 'cust_firstname=:firstname,cust_lastname=:lastname,cust_email=:email,cust_address=:address,cust_cp=:cp,cust_city=:city,cust_country=:country,cust_phone=:phone,cust_birthday=:birthday';
        
        if ($password != NULL)
        {
            $fieldsArray['password'] = $password;
            $fields.=',cust_password=:password';
        }
        $this->dbh->executeSQL('UPDATE '.$this->table.' SET '.$fields.' WHERE cust_id=:id',$fieldsArray); 
    }

    /** Supprime un client avec son ID
     *
     * @param integer $id identifiant du client
     * @return void
     */
    public function delete($id)
    {
        $this->dbh->executeSQL('DELETE FROM '.$this->table.' WHERE cust_id=?',[$id]);
    }


    /** Supprime un client avec son ID
     *
     * @param integer $id identifiant du client
     * @return bool true si commande false sinon
     */
    public function hasOrder($id)
    {
        if($this->dbh->queryOne('SELECT ord_id FROM `order` WHERE customer_cust_id = ?',[$id]))
            return true;
        
        return false;
    }
}