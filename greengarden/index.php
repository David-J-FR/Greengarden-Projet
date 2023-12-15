<?php
session_start();
require_once("dao.php");
$dao = new DAO();
$dao->connexion();



if (isset($_POST['btn_supprimer'])){ 
    $dao->DelProduct($_POST['btn_supprimer']); 
}

// Récupérer les catégories
$list_categories = $dao->getCategorie();

$category= isset($_POST['selectedCategory']) ? $_POST['selectedCategory'] : 'all';   // Récupérer la catégorie sélectionnée

// Récupérer les produits en fonction de la catégorie sélectionnée
if ($category == 'all') {
    $products = $dao->getProduct();
} else {
    $products = $dao->getProductsByCategory($category);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,500,0,200" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital@1&display=swap" rel="stylesheet">
    <title>Accueil</title>
    <style>
        img{
            
            height: 200px !important;
            width: 200px !important;
        }

        body {
            background-color: #F5F5F5;
            font-family: 'Poppins', sans-serif;
        }

        footer {height : 8vh !important;}

        main {min-height: 50vh !important;}
    </style>
</head>

<body>
    <!-- partie navbar -->
    <nav class="navbar navbar-expand-lg bg-dark mb-5">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">GreenGarden</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon "></span>
            </button>
            <div class="collapse navbar-collapse text-white " id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active text-white" aria-current="page" href="index.php">Accueil</a>
                    </li>
                <?php if ((isset($_SESSION['login']) == true) && ($_SESSION['Id_UserType']== 2)) { ?> 
                   <li class="nav-item">
                        <a class="nav-link text-secondary" href="pannelAdmin.php">Pannel admin</a>
                    </li>
                <?php } ?>

                </ul>

                <?php if (isset($_SESSION['login']) == false) { ?>
                    <a style="color:white;" href="connexion.php">Se connecter</a>
                <?php } else { ?>
                    <!-- <a href="#" class="btn btn-secondary d-flex justify-content-center me-2 btn-block text-center ">
                    <span class="material-symbols-outlined me-1 ">shopping_cart</span> Panier
                    </a> -->
                    <button class="btn btn-secondary d-flex justify-content-center me-2 btn-block text-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><span class="material-symbols-outlined me-1 ">shopping_cart</span> Panier</button>
       
                    <a style="color:red;" class="d-flex justify-content-center " title="Cliquez ici pour vous déconnecter" href='deco.php'>Déconnexion</a>

                <?php } ?>

            </div>
        </div>
    </nav>
    <!-- Fin de la partie navbar  -->


<div class="Titre d-flex justify-content-center mb-5 ">
    <div class="d-flex flex-column text-center">
    <h1 class="fw-bold"> Bienvenue sur GreenGarden ! </h1> <br>
    <h3> Votre magasin de jardinage</h3>
    </div>
</div>

    <!-- partie card produits -->
 
<form method="POST" class="d-flex justify-content-center mb-5">
    <div class="d-flex flex-column">
        <label class="mb-2" for="category">Choisir une catégorie : </label>
        <select name="selectedCategory" id="category" onchange="submit()">
            <option value= "all" <?php echo ($category === 'all') ? 'selected' : ''; ?>>Tous les produits</option>
            <?php foreach ($list_categories as $list_categorie) { ?>
                <option value="<?php echo $list_categorie['Id_Categorie']; ?>" <?php echo ($category == $list_categorie['Id_Categorie']) ? 'selected' : ''; ?>>
                    <?php echo $list_categorie['Libelle']; ?>
                </option>
            <?php } ?>
        </select>
    </div>    
</form>

    <main class="container d-flex flex-content-center flex-row flex-wrap mb-5">
   
        <?php foreach ($dao->getProduct() as $product) { ?>
            <div class="col-lg-4 col-md-6 col-12 mb-4 mx-auto d-flex justify-content-center">
            <div class="card m-3 d-flex flex-wrap justify-content-center mt-2 border border-secondary-subtle" id=" <?php echo $product['Id_Produit']?>" style="width: 18rem;">
                <h5 class="card-title d-flex justify-content-center mt-2"><?php echo $product['Nom_court']?></h5>
                <div class="image d-flex justify-content-center">
                <img src="<?php echo $product['Photo'] ?>" class="card-img-top" alt="...">
                </div>
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <p class="card-text text-center ">  <?php echo $product['Nom_Long'] ?> </p>
                    <p class="card-text d-flex justify-content-center fw-semibold"> <?php echo $product['Prix_Achat'] ?> €</p>

            <form method="POST">
                <div class="d-grid">
                    <button type="input" name="btn_ajoutPanier" class="btn btn-secondary btn-block" >Ajouter au panier</button>
                    <?php if ((isset($_SESSION['login']) == true) && ($_SESSION['Id_UserType']== 2)) { ?>                              
                       <button type="input" name="btn_supprimer" value="<?php echo $product['Id_Produit']?>" class="btn btn-dark btn-block mt-2">Supprimer</button>
                    <?php } ?>
                </div>
            </form>
                </div>
            </div>
            </div>
        <?php } ?>

    </main>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title d-flex justify-content-center me-2 btn-block text-center" id="offcanvasRightLabel"> <span class="material-symbols-outlined me-1 ">shopping_cart</span> Panier</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    ...
  </div>
</div>

    <!-- fin partie card produits -->

    <!-- Footer -->
    <footer class="navbar navbar-expand-lg bg-dark text-white mt-5 ">
        <div class="container-fluid d-flex justify-content-center ">
            <span class="navbar-brand text-white fs-6 text"> GreenGarden - 2023 </span>
        </div>
    </footer> 


    <?php
    $dao->disconnect();
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>