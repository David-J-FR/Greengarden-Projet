<?php
ob_start();
session_start();
require_once("dao.php");
$dao = new DAO();
$dao->connexion();

$IdentifiantsInexistant = ""; //variable pour afficher le message d'erreur 
$IdentifiantsErr = ""; //variable pour afficher le message d'erreur


if (isset($_POST['register'])) {

    $login = $dao->valideDonnees($_POST['login']);
    $pass = $dao->valideDonnees($_POST['pass']);

    $result = $dao->checkLogin($dao->validedonnees($_POST['login']));          //on stocke le résultat de la fonction checkLogin dans une variable


    if ($result > 0) {                                                                          //si le résultat est supérieur à 0, c'est que le login existe dans la BDD

        switch (true) {
           
            case $login != $result['Login']:                                                    //si le login entré par l'utilisateur est différent du login de la BDD, on affiche un message d'erreur 
                $IdentifiantsErr = "Votre login est incorrect.";              
                break;
            case !password_verify($pass, $result['Password']):                                  //si le mot de passe entré par l'utilisateur est différent du mot de passe de la BDD, on affiche un message d'erreur
                $IdentifiantsErr = "Votre mot de passe est incorrect.";             
                break;

            default:
                $_SESSION['login'] = $login;                                                    //sinon, on démarre la session et on stocke le login dans une variable de session              
                $_SESSION['Id_UserType'] = $result['Id_UserType'];                              //on stocke l'Id_UserType dans une variable de session
                header('location: index.php');                                                  //on redirige l'utilisateur vers la page d'accueil
                break;
        }
    } else {
        $IdentifiantsErr = "Les champs entrés sont incorrects.";
       
    }
}




?>
<script>
    //fonction pour montrer les mots de passe:
    function filtreMdp() {

        let pass1 = document.getElementById("pass");

        if (pass1.type === "password") { //si le type du mot de passe est "password" (donc caché), 
            pass1.type = "text"; //on le change en "text" (donc visible)                 
        } else {
            pass1.type = "password";
        } //sinon, on le change en "password" (donc caché)

    }
</script>
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
    <title>Connexion</title>
    <style>
        .boutonInsc {
            background: #DDD6C4;
        }

        .boutonInsc:hover {
            background: #BF9C72;
        }
        body {
            background-color: #F5F5F5;
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body>
    <!-- partie navbar -->
    <nav class="navbar navbar-expand-lg bg-dark mb-5">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="index.php">GreenGarden</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active text-white" aria-current="page" href="index.php">Accueil</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="#"></a>
                    </li>
                </ul>

                <?php if (isset($_SESSION['login']) == false) { ?>
                    <a style="color:white;" href="inscription.php">Inscription</a>
                <?php } else { ?>
                    <a style="color:red;" class="d-flex justify-content-center " title="Cliquez ici pour vous déconnecter" href='deco.php'>Déconnexion</a>

                <?php } ?>

            </div>
        </div>
    </nav>
    <!-- Fin de la partie navbar  -->

    <div class="mask d-flex align-items-center h-100 gradient-custom-3">

        <div class="container">

            <div class="row d-flex justify-content-center align-items-center h-100">

                <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                    <div class="card shadow-lg p-3 mb-5 bg-body rounded" style="border-radius: 15px;">

                        <div class="card-body p-5">

                            <h2 style="font-family: 'Poppins', sans-serif; " class="text-uppercase text-center mb-5 fw-bolder">Connexion</h2>



                            <form method="POST">



                                <div class="col-auto mb-5">
                                    <div class="input-group">
                                        <div style="border: none;" class="input-group-text"><span class="material-symbols-outlined">person</span></div>
                                        <input style="border: none;" type="text" class="form-control" id="login" name="login" title="Veuillez indiquer votre login" placeholder="Login" required>
                                    </div>

                                </div>



                                <div class="col-auto mb-5">
                                    <div class="input-group">
                                        <div style="border: none;" class="input-group-text"><span class="material-symbols-outlined">lock</span></div>
                                        <input style="border: none;" type="password" class="form-control" id="pass" name="pass" title="Veuillez indiquer votre mot de passe" placeholder="Mot de passe" required>
                                    </div>
                                    <input type="checkbox" class="mt-5 ms-3" onclick="filtreMdp()"> Afficher le mot de passe

                                </div>

                                <div style="color:red;" class="d-flex justify-content-center" id="messIdentError">
                                    <?php
                                    if ($IdentifiantsErr) {
                                        print $IdentifiantsErr;
                                    }
                                    ?>
                                </div>

                                <?php if (isset($_SESSION['login']) == true) { ?> <!-- si l'utilisateur est connecté, on affiche le bouton de déconnexion -->
                                    <p style="color:green;" class="d-flex justify-content-center ">✔ Connexion établie, bienvenue <?php echo $_SESSION['login']  ?> !</p>

                                <?php } ?>

                                <!-- bouton pour s'inscrire: -->
                                <?php if (isset($_SESSION['login']) == false) { ?>
                                    <div class=" d-flex justify-content-center mt-4">
                                        <button type="submit" name="register" class="boutonInsc btn btn btn-lg gradient-custom-4 text-body  ">Se connecter</button>
                                    </div>
                                <?php } ?>

                                <?php if (isset($_SESSION['login']) == false) { ?>
                                    <p class="text-center text-muted mt-4 mb-0">Vous n'avez pas de compte ? <a href="inscription.php" class="fw-bold text-body"><u>S'enregistrer</u></a></p>
                                <?php } ?>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>


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