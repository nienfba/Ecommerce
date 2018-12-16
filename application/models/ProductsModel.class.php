<?php
/**
 * prod_id 	prod_name 	prod_subtitle 	prod_description 	prod_createdDate 	prod_price 	prod_picture 	category_cat_id 
 */
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
     * @param string $createdDate description
     * @param string $price description
     * @param string $picture nom de l'image
     * @param string $categoryId id de la catégorie auquel est rattaché le produit
     */
    public function add($name, $subtitle, $description, $createdDate, $price, $picture, $categoryId) 
    {
        return $this->dbh->executeSQL('INSERT INTO '.$this->table.' (prod_name, prod_subtitle,prod_description,prod_createdDate,prod_price,prod_picture,category_cat_id) VALUES (?,?,?,?,?,?,?)',[$name, $subtitle, $description, $createdDate, $price, $picture, $categoryId]);
    }

    /** Trouve une catégorie avec son ID
     *
     * @param integer $id identifiant du produit
     * @return Array Jeu d'enregistrement comportant le produit
     */
    public function find($id)
    {
        return $this->dbh->queryOne('SELECT * FROM '.$this->table.' WHERE prod_id = ?',[$id]);
    }

   
    /** Modifie un produit en base
     *
     * @param integer $id identifiant du produit
     * @param string $name nom 
     * @param string $subtitle description
     * @param string $description description
     * @param string $price description
     * @param string $picture nom de l'image
     * @param string $categoryId id de la catégorie auquel est rattaché le produit
     * @return void
     */
    public function update($id, $name, $subtitle, $description, $price, $picture, $categoryId)
    {
        $this->dbh->executeSQL('UPDATE '.$this->table.' SET prod_name=?, prod_subtitle=?,prod_description=?,prod_price=?,prod_picture=?,category_cat_id=? WHERE prod_id=?',[$name, $subtitle, $description, $price, $picture, $categoryId, $id]); 
    }

    /** Supprime un produit avec son ID
     *
     * @param integer $id identifiant du produit
     * @return void
     */
    public function delete($id)
    {
        $this->deleteVariation($id);
        $this->dbh->executeSQL('DELETE FROM '.$this->table.' WHERE prod_id=?',[$id]);
    }

     /** Supprime toutes les variation d'un produit
     *
     * @param integer $id identifiant du produit
     * @return void
     */
    public function deleteVariation($id)
    {
       /* EN PASSANT PAR LE MODELE
        $productVariations = new ProductsVariationsModel();
        $variations = $productVariations->deleteFromProduct($id); */
        $this->dbh->executeSQL('DELETE FROM '.$this->table.'variation WHERE product_prod_id=?',[$id]);
    }
}