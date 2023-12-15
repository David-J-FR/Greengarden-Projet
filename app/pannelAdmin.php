<?php
session_start();
require_once("dao.php");
$dao = new DAO();
$dao->connexion();

if (!isset($_SESSION['login']) && ($_SESSION['Id_UserType'] != 2)) {
    header('location: index.php');
}

$message = ""; // message de validation de l'ajout d'un produit

if (isset($_POST['btn_ajouter'])) {
    $tauxTVA = $dao->valideDonnees($_POST['tauxTVA']);
    $nomLong = $dao->valideDonnees($_POST['nomLong']);
    $nomCourt = $dao->valideDonnees($_POST['nomCourt']);
    $refFournisseur = $dao->valideDonnees($_POST['refFournisseur']);
    $photo = $dao->valideDonnees($_POST['photo']);
    $prixAchat = $dao->valideDonnees($_POST['prixAchat']);
    $idFournisseur = $dao->valideDonnees($_POST['idFournisseur']);
    $idCategorie = $dao->valideDonnees($_POST['idCategorie']);

    $dao->AddProduct($tauxTVA, $nomLong, $nomCourt, $refFournisseur, $photo, $prixAchat, $idFournisseur, $idCategorie);
    $message = "Produit ajouté avec succès";
   
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
    <title>Pannel administrateur</title>
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
                        <a class="nav-link active text-secondary" aria-current="page" href="index.php">Accueil</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Pannel admin</a>
                    </li>


                </ul>

                <a style="color:red;" class="d-flex justify-content-center " title="Cliquez ici pour vous déconnecter" href='deco.php'>Déconnexion</a>


            </div>
        </div>
    </nav>

    <div class="d-flex justify-content-center">
        <h1> Pannel administrateur </h1>
    </div>
    <!-- Fin de la partie navbar  -->
    <section class="container mt-5">
        <h4 class="text-center mb-4">Ajout de produits :</h4>
        <form method="POST">
            <div class="row mb-3 d-flex justify-content-center">

                <div class="col-md-3 mb-3">
                    <input type="text" name="tauxTVA" class="form-control" placeholder="Taux de TVA" required />
                </div>


                <div class="col-md-3 mb-3">
                    <input type="text" name="nomLong" class="form-control" placeholder="Nom long" required />
                </div>


                <div class="col-md-3 mb-3">
                    <input type="text" name="nomCourt" class="form-control" placeholder="Nom court" required />
                </div>


                <div class="col-md-3 mb-3">
                    <input type="text" name="refFournisseur" class="form-control" placeholder="Ref Fournisseur" required />
                </div>


                <div class="col-md-3 mb-3">
                    <input type="text" name="photo" class="form-control" placeholder="photo (lien)" required />
                </div>


                <div class="col-md-3 mb-3">
                    <input type="text" name="prixAchat" class="form-control" placeholder="Prix d'achat euros" required />
                </div>

                <div class="col-md-3 mb-3">
                    <select name="idFournisseur" class="form-select" required>
                        <option value="" disabled selected>Fournisseur</option>
                        <?php foreach ($dao->getFournisseur() as $fournisseur) { ?>
                            <option value="<?php echo $fournisseur['Id_Fournisseur']; ?>"><?php echo $fournisseur['Nom_Fournisseur']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <select name="idCategorie" class="form-select" required>
                        <option value="" disabled selected>Catégorie</option>
                        <?php foreach ($dao->getCategorie() as $categorie) { ?>
                            <option value="<?php echo $categorie['Id_Categorie']; ?>"><?php echo $categorie['Libelle']; ?></option>
                        <?php } ?>
                    </select>
                </div>


                <div class="row mt-3 d-flex justify-content-center">
                    <div class="col-md-12 text-center mt-2">
                        <button class="btn btn-dark " name="btn_ajouter" type="submit">Ajouter</button>
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-2" style="color:green;" id="messValidAjoutProduit">
                                    <?php
                                    if ($message) {
                                        print $message;
                                    }
                                    ?>
                </div>

            </div>
        </form>
    </section>



    <!-- fin partie card produits -->

    <!-- Footer -->
    <!-- <footer class="navbar navbar-expand-lg bg-dark text-white mt-5 ">
        <div class="container-fluid d-flex justify-content-center ">
            <span class="navbar-brand text-white fs-6 text"> GreenGarden - 2023 </span>
        </div>
    </footer>  -->


    <?php
    $dao->disconnect();
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>