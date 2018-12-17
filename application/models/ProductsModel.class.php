<?php
class ProductsModel
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
        $this->table = 'product';
    }

    /** Retourne un tableau de tous les produits en base
     *
     * @param void
     * @return Array Jeu d'enregistrement représentant tous les produits en base associé à leur catégorie en base
     */
    public function listAll() 
    {
        return $this->dbh->query('SELECT * FROM '.$this->table.' INNER JOIN category ON '.$this->table.'.category_cat_id = category.cat_id');
    }

   

    /** Ajoute un produit en base
     *
     * @param string $name nom 
     * @param string $subtitle description
     * @param string $description description
     * @param date $createdDate description
     * @param float $price description
     * @param string $picture nom de l'image
     * @param integer $categoryId id de la catégorie auquel est rattaché le produit
     */
    public function add($name, $subtitle, $description, $createdDate, $price, $tva, $picture, $categoryId) 
    {
        return $this->dbh->executeSQL('INSERT INTO '.$this->table.' (prod_name, prod_subtitle,prod_description,prod_createdDate,prod_price,prod_tva,prod_picture,category_cat_id) VALUES (?,?,?,?,?,?,?,?)',[$name, $subtitle, $description, $createdDate, $price, $tva, $picture, $categoryId]);
    }

    /** Trouve un produit avec son ID
     *
     * @param integer $id identifiant du produit
     * @return Array Jeu d'enregistrement comportant le produit
     */
    public function find($id)
    {
        return $this->dbh->queryOne('SELECT * FROM '.$this->table.' WHERE prod_id = ?',[$id]);
    }

     /** Retourne un tableau de tous les produits en base appartenant à une catégorie
     *
     * @param interger $categoryId id de la catégorie
     * @return Array Jeu d'enregistrement représentant tous les produits en base associé à leur catégorie en base
     */
    public function findByCategory($categoryId) 
    {
        return $this->dbh->query('SELECT * FROM '.$this->table.' WHERE category_cat_id = ?',[$categoryId]);
    }

   
    /** Modifie un produit en base
     *
     * @param integer $id identifiant du produit
     * @param string $name nom 
     * @param string $subtitle description
     * @param string $description description
     * @param float $price description
     * @param string $picture nom de l'image
     * @param integer $categoryId id de la catégorie auquel est rattaché le produit
     * @return void
     */
    public function update($id, $name, $subtitle, $description, $price, $tva, $picture, $categoryId)
    {
        $this->dbh->executeSQL('UPDATE '.$this->table.' SET prod_name=?, prod_subtitle=?,prod_description=?,prod_price=?,prod_tva=?,prod_picture=?,category_cat_id=? WHERE prod_id=?',[$name, $subtitle, $description, $price, $tva, $picture, $categoryId, $id]); 
    }

    /** Supprime un produit avec son ID
     *
     * @param integer $id identifiant du produit
     * @return void
     */
    public function delete($id)
    {
        /** On supprime toutes les variations */
        $productVariations = new VariationsModel();
        $productVariations->deleteByProduct($id);
        $this->dbh->executeSQL('DELETE FROM '.$this->table.' WHERE prod_id=?',[$id]);
    }
}