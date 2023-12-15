<?php

class DAO
{
    /* Paramètres de connexion à la base de données 
	Dans l'idéal, il faudrait écrire les getters et setters correspondants pour pouvoir en modifier les valeurs
	au cas où notre serveur change
	*/
    //paramètres de connexion à la base de donnée

    private $host = "127.0.0.1";
    private $user = "root";
    private $password = "";
    private $database = "greengarden";
    private $charset = "utf8";

    //instance courante de la connexion
    private $bdd;

    //stockage de l'erreur éventuelle du serveur mysql
    private $error;

    //constructeur de la classe

    public function __construct()
    {
    }

    /* méthode de connexion à la base de donnée */
    public function connexion()
    {

        try {

            // On se connecte à MySQL
          
            $this->bdd = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->database . ';charset=' . $this->charset, $this->user, $this->password);
        } catch (Exception $e) {
            // En cas d'erreur, on affiche un message et on arrête tout
            $this->error = 'Erreur : ' . $e->getMessage();
        }

    }

    /* méthode pour fermer la connexion à la base de données */
	public function disconnect()
	{
		$this->bdd = null;
	}

     //méthode pour récupérer les résultats d'une requête SQL
     public function getResults($query) {
        $results=array();

        $stmt = $this->bdd->query($query);

        if (!$stmt) {
            $this->error=$this->bdd->errorInfo();
            return false;
        } else {
            // fetch uniquement PDO associative 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    }
    //fonction pour ajouter un utilisateur dans la base de données
    public function AddUsers($Id_UserType, $Login, $Password) {
        $sql= "INSERT INTO t_d_user (`Id_UserType`, `Login`, `Password`) values (?,?,?)";
        $stmt = $this->bdd->prepare($sql);
        $stmt -> execute([$Id_UserType, $Login, $Password]); 
    }

    //fonction pour ajouter un client dans la base de données
    public function AddClient($societe,$nom,$prenom,$email,$phone,$Id_Commercial,$Id_Type_Client){
        $sql= "INSERT INTO t_d_client (`Nom_Societe_Client`,`Nom_Client`,`Prenom_Client`,`Mail_Client`,`Tel_Client`,`Id_Commercial`,`Id_Type_Client`) values (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->bdd->prepare($sql);
        $stmt -> execute([$societe,$nom,$prenom,$email,$phone,$Id_Commercial,$Id_Type_Client]); 


    }

    //fonction pour ajouter des produits dans la base de données
    public function AddProduct($tauxTVA,$nomLong,$nomCourt,$refFournisseur,$photo,$prixAchat,$idFournisseur,$idCategorie){
        $sql= "INSERT INTO t_d_produit (`Taux_TVA`,`Nom_Long`,`Nom_court`,`Ref_Fournisseur`,`Photo`,`Prix_Achat`,`Id_Fournisseur`,`Id_Categorie`) values (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->bdd->prepare($sql);
        $stmt -> execute([$tauxTVA,$nomLong,$nomCourt,$refFournisseur,$photo,$prixAchat,$idFournisseur,$idCategorie]); 

    }






    //fonction pour supprimer un produit de la base de données
    public function DelProduct($Id_Produit){
        $sql= "DELETE FROM t_d_produit WHERE Id_Produit = ?";
        $stmt = $this->bdd->prepare($sql);
        $stmt -> execute([$Id_Produit]); 

    }


    //fonction pour sécuriser les données entrées par l'utilisateur
    function valideDonnees($donnees)
    { $donnees = htmlentities(stripslashes(trim($donnees)));
        return $donnees;
    }

    
    public function getLogin($query) {                //fonction pour récupérer et parcourir les login de la BDD
        $results=array();
        
        $stmt = $this->bdd->query($query);              //on exécute la requête SQL

        if (!$stmt) {                                   //si la requête ne s'exécute pas, on affiche l'erreur
            $this->error=$this->bdd->errorInfo();       //stockage de l'erreur dans la variable error
            return false;                               //on retourne false
        } else {                                        //sinon, on retourne le résultat de la requête
             // fetch uniquement PDO associative 
            return $stmt->fetch(PDO::FETCH_ASSOC);      //on retourne le résultat de la requête
        }

    }

    //fonction pour vérifier si le login existe déjà dans la BDD:
    public function checkLogin($login) {                                               
        $sql = "SELECT * FROM t_d_user WHERE `Login` = '$login'";      
        return $this->getLogin($sql);                                                                             
    }

    public function checkMail($email) {                                             //mettre en paramètre l'email stocké en POST    
        $sql = "SELECT * FROM t_d_client WHERE `Mail_Client` = '$email'";      //requête SQL pour sélectionner l'email dans la BDD
        return $this->getLogin($sql);                                             //on retourne le résultat de la requête                                  
    }
    
    public function getProduct(){                            //fonction pour récupérer les produits de la BDD
        $sql = "SELECT * FROM t_d_produit";
        if (isset($_POST['selectedCategory']) && $_POST['selectedCategory'] != 'all') {
            $sql .= " WHERE Id_Categorie = " . $_POST['selectedCategory'];
        }
         //requête SQL pour sélectionner les produits dans la BDD
        return $this->getResults($sql);                                         
    }

    public function getFournisseur(){                            //fonction pour récupérer les fournisseurs de la BDD
        $sql = "SELECT * FROM t_d_fournisseur";                  //requête SQL pour sélectionner les fournisseurs dans la BDD
        return $this->getResults($sql);                                         
    }

    public function getCategorie(){                            //fonction pour récupérer les catégories de la BDD
        $sql = "SELECT * FROM t_d_categorie";                  //requête SQL pour sélectionner les catégories dans la BDD
        return $this->getResults($sql);                                         
    }

    public function getProductsByCategory($categoryId) {
        $sql = "SELECT Id_produit, Nom_court, Photo, SUM(Prix_Achat * Taux_TVA / 100) AS TVA,
        SUM(Prix_Achat + (SELECT SUM(Prix_Achat * Taux_TVA / 100) FROM t_d_produit)) AS TTC
        FROM t_d_produit
        WHERE Id_Categorie = :categoryId
        GROUP BY Nom_long";

        $stmt = $this->bdd->prepare($sql);
        $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>  