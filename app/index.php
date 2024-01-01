<?php
session_start();
require_once("dao.php");
$dao = new DAO();
$dao->connexion();



if (isset($_POST['btn_supprimer'])) {
    $dao->DelProduct($_POST['btn_supprimer']);
}





// Récupérer les catégories
$list_categories = $dao->getCategorie();

$category = isset($_POST['selectedCategory']) ? $_POST['selectedCategory'] : 'all';   // Récupérer la catégorie sélectionnée

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
        img {

            height: 200px !important;
            width: 200px !important;
        }

        body {
            background-color: #F5F5F5;
            font-family: 'Poppins', sans-serif;
        }

        footer {
            height: 8vh !important;
        }

        main {
            min-height: 50vh !important;
        }
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
                    <?php if ((isset($_SESSION['login']) == true) && ($_SESSION['Id_UserType'] == 2)) { ?>
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
            <label class="mb-2" for="category" class="form-label">Choisir une catégorie : </label>
            <select name="selectedCategory" id="category" class="form-select" onchange="submit()"> <!-- onchange="submit()" permet de soumettre le formulaire à chaque changement de catégorie -->
                <option value="all" <?php echo ($category === 'all') ? 'selected' : ''; ?>>Tous les produits</option> <!-- Si la catégorie est 'all', on met l'attribut 'selected' -->


                <?php foreach ($list_categories as $parentCategory) { ?> <!-- On parcourt les catégories parentes -->
                    <?php if ($parentCategory['Id_Categorie_Parent'] === null) { ?> <!-- On n'affiche comme catégorie parente que ceux qui ont l'id catégorie NULL car c'est comme cela qu'on les sait dans la bdd -->
                        <optgroup label="<?php echo $parentCategory['Libelle']; ?>"> <!-- On affiche le libellé de la catégorie parente -->
                            <?php foreach ($list_categories as $subCategory) { ?> <!-- On parcourt les sous catégories par catégorie parente -->
                                <?php if ($subCategory['Id_Categorie_Parent'] == $parentCategory['Id_Categorie']) { ?> <!-- Si l'id catégorie parente est égal à l'id catégorie parente de la sous catégorie, on affiche la sous catégorie -->
                                    <option value="<?php echo $subCategory['Id_Categorie']; ?>" <?php echo ($category == $subCategory['Id_Categorie']) ? 'selected' : ''; ?>> <!-- Si la catégorie est égale à la sous catégorie, on met l'attribut 'selected' -->
                                        <?php echo $subCategory['Libelle']; ?> <!-- On affiche le libellé de la sous catégorie -->
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>

            </select>
        </div>
    </form>




    <main class="container d-flex flex-content-center flex-row flex-wrap mb-5 ">

        <?php foreach ($dao->getProduct() as $product) {
            
            if(isset($_POST['modif_'.$product['Id_Produit']])){
//SET Taux_TVA = :tva, Nom_Long = noml, Nom_court = :nomc, Ref_Fournisseur =:ref, Photo = :img, Prix_Achat = :prix, Id_Fournisseur = :Ifour, Id_Categorie = :iCat WHERE Id_Produit = :Iprod
                $dao->ModifProduct(["tva"=>$_POST['tauxTVA-modif'],"noml"=>$_POST['nomLong-modif'],"nomc"=>$_POST['nomCourt-modif'],"ref"=>$_POST['refFournisseur-modif'],"img"=>$_POST['photo-modif'],"prix"=>$_POST['prixAchat-modif'],"Ifour"=>$_POST['idFournisseur-modif'],"iCat"=>$_POST['idCategorie-modif'],"Iprod"=>$product['Id_Produit']]);
            }

            ?>
            <div class="col-lg-4 col-md-6 col-12 mb-4 mx-auto d-flex justify-content-center ">
                <div class="card m-3 d-flex flex-wrap justify-content-center mt-2 border  shadow p-3 mb-5  rounded" id=" <?php echo $product['Id_Produit'] ?>" style="width: 18rem;">
                    <h5 class="card-title d-flex justify-content-center mt-2"><?php echo $product['Nom_court'] ?></h5>
                    <div class="image d-flex justify-content-center">
                        <img src="<?php echo $product['Photo'] ?>" class="card-img-top" alt="...">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <p class="card-text text-center "> <?php echo $product['Nom_Long'] ?> </p>
                        <p class="card-text d-flex justify-content-center fw-semibold"> <?php echo $product['Prix_Achat'] ?> €</p>

                        <form method="POST">
                            <div class="d-grid">
                                <button type="input" name="btn_ajoutPanier" class="btn btn-secondary btn-block">Ajouter au panier</button>
                                <?php if ((isset($_SESSION['login']) == true) && ($_SESSION['Id_UserType'] == 2)) { ?>
                                    <button type="input" name="btn_supprimer" value="<?php echo $product['Id_Produit'] ?>" class="btn btn-dark btn-block mt-2">Supprimer</button>
                                    <button type="button" name="<?php echo $product['Nom_court'] ?>" value="<?php echo $product['Id_Produit'] ?>" class="btn btn-secondary btn-block mt-2 " data-bs-toggle="modal" data-bs-target="#exampleModal_<?= $product['Id_Produit']; ?>">Modifier</button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal_<?= $product['Id_Produit']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modification du produit:</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">

                                                    <section class="container mt-5 ">
                                                        <!-- A compléter car ne fonctionne pas -->
                                                        <h4 class="text-center mb-4">Modification de <?php echo $product['Nom_court'] ?> </h4>


                                                        <form method="POST">
                                                            <div class="row mb-3 d-flex justify-content-center">

                                                                <div class="col-12 mb-3">
                                                                    <input type="text" name="tauxTVA-modif" class="form-control" placeholder="Taux de TVA" value="<?php echo $product['Taux_TVA'] ?>" />
                                                                </div>


                                                                <div class="col-12 mb-3">
                                                                    <input type="text" name="nomLong-modif" class="form-control" placeholder="Nom long"  value="<?php echo $product['Nom_Long'] ?>"/>
                                                                </div>


                                                                <div class="col-12 mb-3">
                                                                    <input type="text" name="nomCourt-modif" class="form-control" placeholder="Nom court" value="<?php echo $product['Nom_court'] ?>" />
                                                                </div>


                                                                <div class="col-12 mb-3">
                                                                    <input type="text" name="refFournisseur-modif" class="form-control" placeholder="Ref Fournisseur" value="<?php echo $product['Ref_fournisseur'] ?>" />
                                                                </div>


                                                                <div class="col-12 mb-3">
                                                                    <input type="text" name="photo-modif" class="form-control" placeholder="photo (lien)"  value="<?php echo $product['Photo'] ?>"/>
                                                                </div>


                                                                <div class="col-12 mb-3">
                                                                    <input type="text" name="prixAchat-modif" class="form-control" placeholder="Prix d'achat euros" value="<?php echo $product['Prix_Achat'] ?>" />
                                                                </div>

                                                                <div class="col-12 mb-3">
                                                                    <select name="idFournisseur-modif" class="form-select" >
                                                                    <option value="<?php echo $product['Id_Fournisseur'] ?>" selected>Fournisseur</option>
                                                                        <?php foreach ($dao->getFournisseur() as $fournisseur) { ?>
                                                                            <option value="<?php echo $fournisseur['Id_Fournisseur']; ?>"><?php echo $fournisseur['Nom_Fournisseur']; ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>

                                                                <div class="col-12 mb-3">
                                                                    <select name="idCategorie-modif" class="form-select" >
                                                                    <option value="<?php echo $product['Id_Categorie'] ?>" selected> Catégorie produit</option>
                                                                        <?php foreach ($dao->getCategorie() as $categorie) { ?>
                                                                           
                                                                            <?php if ($categorie['Id_Categorie_Parent'] != null) { ?>
                                                                                <option value="<?php echo $categorie['Id_Categorie']; ?>"><?php echo $categorie['Libelle']; ?></option>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </form>




                                                </div>
                                                <div class="modal-footer">
                                                    <button type="input" name="modif_<?= $product['Id_Produit']; ?>"  class="btn btn-secondary" data-bs-dismiss="modal">Valider</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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