<?php
class OrdersdetailModel
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
        $this->table = 'orderdetail';
    }

    /** Retourne un tableau de toutes les lignes de commande
     *
     * @param $orderId id de la commande
     * @return Array Jeu d'enregistrement
     */
    public function listAll($orderId) 
    {
        return $this->dbh->query('SELECT * FROM '.$this->table.' WHERE order_ord_id=?',[$orderId]);
    }

      /** Ajoute une ligne de commande en base
     *
     * @param integer $quantity
     * @param string $price
     * @param integer $orderId
     * @param integer $productId
     * @param integer $productVariantionId
     */
    public function add($quantity, $price, $orderId, $productId, $productVariantionId) 
    {
        return $this->dbh->executeSQL('INSERT INTO '.$this->table.' (ordd_quantity, ordd_price,order_ord_id,product_prod_id,productvariation_prodv_id) VALUES (?,?,?,?,?)',[$quantity, $price, $orderId, $productId, $productVariantionId]);
    }

    /** Trouve une ligne de commande avec son ID
     *
     * @param integer $id identifiant
     * @return Array Jeu d'enregistrement
     */
    public function find($id)
    {
        return $this->dbh->queryOne('SELECT * FROM '.$this->table.' WHERE ordd_id = ?',[$id]);
    }

     /** Retourne un tableau de toutes les commandes en base appartenant à un client
     *
     * @param interger $orderId id commande
     * @return Array Jeu d'enregistrement représentant toutes les commandes en base appartenant à un client
     */
    public function findByOrder($orderId) 
    {
        return $this->dbh->query('SELECT * FROM '.$this->table.' INNER JOIN product on product.prod_id = '.$this->table.'.product_prod_id LEFT JOIN productvariation on '.$this->table.'.productvariation_prodv_id=productvariation.prodv_id WHERE order_ord_id = ?',[$orderId]);
    }

    /** Calcul la somme des ligne de commande
     *
     * @param interger $orderId id commande
     * @return Array Jeu d'enregistrement représentant toutes les commandes en base appartenant à un client
     */
    public function getTotalPrice($orderId) 
    {
        return $this->dbh->queryOne('SELECT SUM(ordd_price * ordd_quantity) as total FROM '.$this->table.' WHERE order_ord_id = ? GROUP BY order_ord_id ',[$orderId]);
    }

    /** Modifie un produit en base
     *
     * @param integer $id identifiant
     * @param integer $quantity
     * @param string $price
     * @param integer $orderId
     * @param integer $productId
     * @param integer $productVariantionId
     */
    public function update($id, $quantity, $price, $orderId, $productId, $productVariantionId)
    {
        $this->dbh->executeSQL('UPDATE '.$this->table.' SET ordd_quantity=?, ordd_price=?,order_ord_id=?,product_prod_id=?,productvariation_prodv_id=? WHERE ordd_id=?',[$quantity, $price, $orderId, $productId, $productVariantionId, $id]); 
    }

    /** Supprime une ligne commande
     *
     * @param integer $id identifiant
     * @return void
     */
    public function delete($id)
    {
        $this->dbh->executeSQL('DELETE FROM '.$this->table.' WHERE ordd_id=?',[$id]);
    }

    
    /** Supprime les lignes commandes par order
     *
     * @param integer $orderId identifiant order
     * @return void
     */
    public function deletebyOrder($orderId)
    {
        $this->dbh->executeSQL('DELETE FROM '.$this->table.' WHERE order_ord_id=?',[$orderId]);
    }
}