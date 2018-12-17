<?php
class VariationsModel
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
        $this->table = 'productvariation';
    }

    /** Retourne un tableau de toutesla variations pour un produit
     *
     * @param integer $productId identifiant du produit
     * @return Array Jeu d'enregistrement représentant tous les produits en base associé à leur catégorie en base
     */
    public function listAll($productId) 
    {
        return $this->dbh->query('SELECT * FROM '.$this->table.' WHERE product_prod_id=?',[$productId]);
    }

    /** Ajoute une variation produit en base
     * 
     * @param string $name nom 
     * @param float $price prix
     * @param integer $quantity nom de l'image
     * @param integer $productId id du produit auquel est rattachée la variation
     */
    public function add($name, $price, $quantity, $productId) 
    {
        return $this->dbh->executeSQL('INSERT INTO '.$this->table.' (prodv_name, prodv_price,prodv_quantity,product_prod_id) VALUES (?,?,?,?)',[$name, $price, $quantity, $productId]);
    }

    /** Trouve une variation avec son ID
     *
     * @param integer $id identifiant variation 
     * @return Array Jeu d'enregistrement comportant le produit
     */
    public function find($id)
    {
        return $this->dbh->queryOne('SELECT * FROM '.$this->table.' WHERE prodv_id = ?',[$id]);
    }

    /** Trouve des variations avec id d'un produit
     *
     * @param integer $id identifiant variation 
     * @return Array Jeu d'enregistrement comportant les produits
     */
    public function findByProduct($productId)
    {
        return $this->dbh->query('SELECT * FROM '.$this->table.' WHERE product_prod_id = ?',[$productId]);
    }

   
    /** Modifie une variation en base
     *
     * @param integer $id identifiant de la variation
     * @param string $name nom 
     * @param float $price prix
     * @param integer $quantity nom de l'image
     * @param integer $productId id du produit auquel est rattachée la variation
     * @return void
     */
    public function update($id, $name, $price, $quantity, $productId)
    {
        $this->dbh->executeSQL('UPDATE '.$this->table.' SET prodv_name=?, prodv_price=?,prodv_quantity=?,product_prod_id=? WHERE prodv_id=?',[$name, $price, $quantity, $productId, $id]); 
    }

    /** Supprime une variation avec son ID
     *
     * @param integer $id identifiant variation
     * @return void
     */
    public function delete($id)
    {
        $this->dbh->executeSQL('DELETE FROM '.$this->table.' WHERE prodv_id=?',[$id]);
    }

      /** Supprime toutes les cariations d'un produit
     *
     * @param integer $productId identifiant d'un produit
     * @return void
     */
    public function deleteByProduct($productId)
    {
        $this->dbh->executeSQL('DELETE FROM '.$this->table.' WHERE product_prod_id=?',[$productId]);
    }

}