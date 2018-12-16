<?php

class CategoriesModel
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
        $this->table = 'category';
    }

    /** Retourne un tableau de toutes les catégories en base
     *
     * @param void
     * @return Array Jeu d'enregistrement représentant toutes les catégories en base
     */
    public function listAll() 
    {
        return $this->dbh->query('SELECT * FROM '.$this->table);
    }

    /** Ajoute une catégorie en base
     *
     * @param string $name nom de la catégorie
     * @param string $description description de la cétégorie
     * @param string $picture nom de l'image
     */
    public function add($name, $description, $picture) 
    {
        return $this->dbh->executeSQL('INSERT INTO '.$this->table.' (cat_name, cat_description,cat_picture) VALUES (?,?,?)',[$name, $description, $picture]);
    }

    /** Trouve une catégorie avec son ID
     *
     * @param integer $id identifiant de la catégorie
     * @return Array Jeu d'enregistrement comportant la catégorie trouvée
     */
    public function find($id)
    {
        return $this->dbh->queryOne('SELECT * FROM '.$this->table.' WHERE cat_id = ?',[$id]);
    }

   
    /** Modifie une catégorie en base
     *
     * @param integer $id identifiant de la catégorie
     * @param interger $id nom de la catégorie
     * @param string $name nom de la catégorie
     * @param string $description description de la cétégorie
     * @param string $picture nom de l'image
     * @return void
     */
    public function update($id, $name, $description, $picture)
    {
        $this->dbh->executeSQL('UPDATE '.$this->table.' SET cat_name=?, cat_description=? ,cat_picture =? WHERE cat_id=?',[$name, $description, $picture, $id]); 
    }

    /** Supprime une catégorie avec son ID
     *
     * @param integer $id identifiant de la catégorie
     * @return void
     */
    public function delete($id)
    {
        $this->dbh->executeSQL('DELETE FROM '.$this->table.' WHERE cat_id=?',[$id]);
    }
}