<?php
class OrdersModel
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
        $this->table = '`order`';
    }

    /** Retourne un tableau de toutes les commandes en base
     *
     * @param void
     * @return Array Jeu d'enregistrement représentant tous les produits en base associé à leur catégorie en base
     */
    public function listAll() 
    {
        return $this->dbh->query('SELECT * FROM '.$this->table.' INNER JOIN customer ON '.$this->table.'.customer_cust_id = customer.cust_id ORDER BY ord_date DESC');
    }

    /** Ajoute une commande en base
     *
     * @param date $orderDate
     * @param string $status
     * @param date $dateShipped
     * @param date $dateDelivery
     * @param string $comment
     * @param string $customerId
     */
    public function add($orderDate, $status, $datePayment, $dateShipped, $dateDelivery, $comment, $customerId) 
    {
        return $this->dbh->executeSQL('INSERT INTO '.$this->table.' (ord_date, ord_status, ord_datePayment, ord_dateShipped, ord_dateDelivery, ord_comment, customer_cust_id) VALUES (?,?,?,?,?,?,?)',[$orderDate, $status, $datePayment, $dateShipped, $dateDelivery, $comment, $customerId]);
    }

    /** Trouve une commande avec son ID
     *
     * @param integer $id identifiant du produit
     * @return Array Jeu d'enregistrement comportant le produit
     */
    public function find($id)
    {
        return $this->dbh->queryOne('SELECT * FROM '.$this->table.' WHERE ord_id = ?',[$id]);
    }

     /** Retourne un tableau de toutes les commandes en base appartenant à un client
     *
     * @param interger $customerId id du client
     * @return Array Jeu d'enregistrement représentant toutes les commandes en base appartenant à un client
     */
    public function findByCustomer($customerId) 
    {
        return $this->dbh->query('SELECT * FROM '.$this->table.' WHERE customer_cust_id = ?',[$customerId]);
    }
    
    /** Retourne la commande correspondante avec un idcommande et un idcustomer
     *
     * @param integer $id identifiant du produit
     * @param integer $customerId id du client
     * @return Array Jeu d'enregistrement représentant toutes les commandes en base appartenant à un client
     */
    public function findByIdAndCustomer($id, $customerId) 
    {
        return $this->dbh->queryOne('SELECT * FROM '.$this->table.' WHERE ord_id = ? AND customer_cust_id = ?',[$id, $customerId]);
    }
    
  
    /** Modifie une commande en base
     *
     * @param integer $id identifiant de la commande
     * @param string $status
     * @param date $dateShipped
     * @param date $dateDelivery
     * @param string $comment
     * @param string $customerId
     */
    public function update($id, $status, $datePayment, $dateShipped, $dateDelivery, $comment, $customerId)
    {
        $this->dbh->executeSQL('UPDATE '.$this->table.' SET ord_status=?, ord_datePayment=?, ord_dateShipped=?,ord_dateDelivery=?,ord_comment=?,customer_cust_id=? WHERE ord_id=?',[$status, $datePayment, $dateShipped, $dateDelivery, $comment, $customerId, $id]); 
    }

     /** Modifie le status d'une commande
     *
     * @param integer $id identifiant de la commande
     * @param string $status
     */
    public function updatePayment($id,$status)
    {
         $this->dbh->executeSQL('UPDATE '.$this->table.' SET ord_status=? WHERE ord_id=?',[$status, $id]); 
    }

    /** Supprime une commande
     *
     * @param integer $id identifiant du produit
     * @return void
     */
    public function delete($id)
    {
        /** On supprime toutes les variations */
        $orderDetail= new Orderdetailodel();
        $orderDetail->deleteByProduct($id);
        $this->dbh->executeSQL('DELETE FROM '.$this->table.' WHERE ord_id=?',[$id]);
    }
}