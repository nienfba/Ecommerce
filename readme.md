
# E-commerce


## New fonctionnality - Layout for Admin

* For using new layout for subController... exemple admin/ please add this in application/config/library.php :

    `$config['layouts'] = ['admin'=>'LayoutAdmin'];`

* Create new LayoutAdminView.phtml in folder application/www/

* Now all route with "/admin" use this Layout (ex : /admin/user)

# Download and Installation
To begin using this e-commerce system :
* Clone the repo on your web folder : `git clone https://github.com/nienfba/Ecommerce.git YOUR_FOLDER/` or download the ZIP file and extract !
* Create Database `cupoftea` on phpmyadmin and import `./cupoftea.sql`
* Configure file application/config/database.php with your database access

# TODO
* Secure admin access
* Order view in Admin
* Order front process not real - add payment CB emulation
* Debugs and tests 

# Documentation

## Créer un contrôleur
Dans le dossier `application/controllers/` il vous suffit de créer une classe Controller. Par exemple pour créer un contrôleur qui va afficher une liste de produits que l'on va nommer `ListeArticle` il faudra créer un fichier `ArticleController.class.php` dans le répertoire `application/controllers/admin/article/`
Dans ce fichier il faudra créer une classe `ListeArticleController` qui contiendra 2 méthodes :

 - public  function  httpGetMethod(Http  $http,  array  $queryFields)
 - public  function  httpPostMethod(Http  $http,  array  $formFields)

La première sera appelée en cas de requête GET vers le contrôleur et bien sûr la deuxième en cas de requête POST vers le contrôleur.
Pour créer un contôleur qui permettre d'ajouter un article et garder une logique dans le routing de l'application on pourra créer un contrôleur `addController` dans le dossier  `application/controllers/admin/article/add/`.

Exemple complet pour ArticleController :

    class  ArticleController
    {
    
	    public  function  httpGetMethod(Http  $http,  array  $queryFields)
	    {
		    /*
		    * Méthode appelée en cas de requête HTTP GET
		    *
		    * L'argument $http est un objet permettant de faire des redirections etc.
		    * L'argument $queryFields contient l'équivalent de $_GET en PHP natif.
		    */
	    }
	    public  function  httpPostMethod(Http  $http,  array  $formFields)
	    {
    
		    /*
		    * Méthode appelée en cas de requête HTTP POST
		    *
		    * L'argument $http est un objet permettant de faire des redirections etc.
		    * L'argument $formFields contient l'équivalent de $_POST en PHP natif.
		    */
    
	    }
    }

## Créer un modèle
Dans le dossier `application/models/` il vous suffit de créer une classe Model. Par exemple pour créer un modèle qui va gérer le modèle de données pour les articles `Catégorie` il faudra créer un fichier `CatégorieModel.class.php` dans le répertoire `application/models/`

Les méthodes de cet objet sont à votre convenance mais il semble nécessaire d'y placer les actions conventionnels d'un CRUD.
Exemple complet :

    <?php
    class CategoriesModel {
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

Exemple d'appel d'un modèle dans le contrôleur :

    $categorie = new CategorieModel();
    $categorie->add('titre','description','url/image');

## Transmettre des données à la vue à partir d'un contrôleur
Il suffit de retourner un tableau association à partir du contrôleur pour que chaque index du tableau devienne une variable pour la vue. Ces variables contiennent respectivement la valeur associé dans le tableau.
Exemple :

    return ["title"=>'Titre de la page',
    'articles'=>$jeuEnregistrement];

Dans la vue il sera donc possible d'appeler les variables `$title` et `$articles`

L'utilisation d'un index `_form` ou `_raw_template` dans le tableau retourné par le contrôleur va induire une attente du framework. Dans le premier cas la valeur transmise devra être un objet instance de la classe Form (plus précisément une classe fille de Form), dans le deuxième cas le framework attend un booléen (si `true`la vue sera chargée sans le layout).

Voir l'injection de variables pour les formulaires dans le chapitre suivant.

## La classe Form
Elle permet de créer des classes filles pour chaque formulaire de notre applications qui nécessitent une validation et une réinjection de données en cas d'erreur. 
Pour le formulaire d'ajout de catégorie par exemple, il vous faut créer une classe `CategoriesForm.class.php` dans le répertoire `application/Forms/`
Cette classe devra obligatoirement déclarer et compléter la méthode `build` de sa classe parent (méthode abstraite). Cette méthode permet d'ajouter à notre objet tous les noms des champs que nous souhaitons réinjecter en cas d'erreur dans le formulaire (en utilisant la méthode `addFormField`). 
Si nous transmettons alors une instance de cet objet au framework dans l'index `_form`du tableau de retour de notre contrôleur, le framework mettra à notre disposition une variable par champs ajouté.

**Exemple d'une classe form :** 

    <?php
    class CategoriesForm extends Form
    {
	    public function build()
	    {
	        $this->addFormField('name');
	        $this->addFormField('contents');
	        $this->addFormField('id');
	        $this->addFormField('originalpicture');
	    }
    }

**Exemple d'utilisation dans un contrôleur :** 
Injection des variables du formulaire sans valeur (méthode GET) :

    public function httpGetMethod(Http $http, array $queryFields)
    {
        return [
	    'title'=>'Ajouter une catégorie',
	    'active'=>'addCategory',
	    '_form' => new CategoriesForm()
        ];
    }
Injection des variables avec valeur en cas d'erreur (méthode POST) :

    public  function  httpPostMethod(Http  $http,  array  $formFields)
    {
	    try
	    {
		    /** Ici on traite les données du formulaire
		    et en cas d'erreur on lance un Exception de type 
		    DomainException*/
		    
	    }
	    catch(DomainException $exception)
        {
             /** DomainException est un type d'exception prédéfinie par PHP (valeur en dehors des limites selon la doc, on l'utilise donc ici pour ça !)
             *   On a choisi ce type d'exception dans l'arbre généalogique des exceptions fournies par PHP. On aurait pu faire notre propre class
             *   Exemple : class FormValideException extends Exception {} et faire ensuite un catch(FormValideException $exception)
             */

            /** Réaffichage du formulaire avec un message d'erreur. */
            $form = new CategoriesForm();
            /** On bind nos données $_POST ($formFields) avec notre objet formulaire */
            $form->bind($formFields);
            /** On affecte notre message d'erreur */
            $form->setErrorMessage($exception->getMessage());
            
            return [ 
                'title'=>'Ajouter une catégorie',
			    'active'=>'addCategory',
                '_form' => $form 
            ]; 
        }
     }

Nous aurons donc à disposition dans notre Vue dans ce cas les variables suivantes :

 - $id
 - $name
 - $contents
 - $originalPicture
 - et la variable $errorMessage pour afficher le message levé par l'exception

## La classe Http

* **Upload de fichier** exemple pour un champs input `picture`

		/** Image uploadée
		*   On la déplace sinon on affecte à NULL pour la saisie en base
		*/
		if ($http->hasUploadedFile('picture'))
			$picture = $http->moveUploadedFile('picture','/uploads/categories');
		else
			$picture = NULL;
                
* **Redirection** vers une autre page en fournissant une route du framework :
    
        /** Redirection vers la liste des catégories */
        $http->redirectTo('admin/categories/');
     
## Les filtres d'interception

* **Créer un filtre d'interception** exemple pour un filtre qui va activer la session sur toutes les pages

		class UserSessionFilter implements InterceptingFilter
		{
			public function run(Http $http, array $queryFields, array $formFields)
			{
				return
				[
					 'userSession' => new UserSession()
				];
			}
		}
		
Bien sur il faut rajouter ce Filtrer d'interception dans la configuration du Framework (config/library.php)
		
		$config['intercepting-filters'] = ['UserSession']
		
## Exemple pour la gestion de la session sur le Framewok.

Cette classe sera instanciée par le filtre d'interception ci-avant. Vous poureez ainsi avoir accès à cet objet dans vos vues et dans le layout. Il sera alors possible d'afficher des éléments spacifique selon que l'utilisateur soit connecté ou non par exemple.
	
* **Création de la classe Session et synopsis de ses méthodes **

Les méthodes notées final sont terminées... Les autre sont à compléter par vos soins !!

		class UserSession
		{
			final public function __construct()
			{
				if(session_status() == PHP_SESSION_NONE)
				{
					// Démarrage du module PHP de gestion des sessions.
					session_start();
				}
			}

			public function create($userId, $firstName, $lastName, $email)
			{
				// Construction de la session utilisateur.

			}
		
			final public function destroy()
			{
				// Destruction de l'ensemble de la session.
				$_SESSION = array();
				session_destroy();
			}

			final public function createOrder($orderId)
			{
				// Construction de la session utilisateur.
				$_SESSION['order'] = $orderId;
			}



			final public function getOrderId()
			{
				if($this->isAuthenticated() == false || !isset($_SESSION['order']))
				{
					return null;
				}

				return $_SESSION['order'];
			}

			public function getEmail(){}
			public function getFirstName(){}
			public function getFullName(){}
			public function getLastName(){}
			public function getUserId(){}
			public function isAuthenticated(){}
		}