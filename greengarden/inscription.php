<?php
session_start();
require_once("dao.php");
$dao = new DAO();
$dao->connexion();

$succes = ""; //variable pour afficher le message de succès
// $messageErrorMDP = ""; //variable pour afficher le message d'erreur
// $messageErrorLogin = ""; //variable pour afficher le message d'erreur
$Id_UserType = 1; //variable pour définir le type d'utilisateur (ici, 1 = utilisateur qui est un client)
$Id_Type_Client = 1; //variable pour définir le type de client (ici, 1 = client particulier)
$Id_Commercial= 3; //variable pour définir le commercial (ici, 3 = pas de commercial pour le moment)

// $messageInvalidPrenom = ""; //variable pour afficher le message d'erreur
// $messageInvalidMail = ""; //variable pour afficher le message d'erreur
// $messageErrorMail = ""; //variable pour afficher le message d'erreur
$messageError = ""; //variable pour afficher le message d'erreur

if (isset($_POST['register'])) {

    $mail = $dao->checkMail($dao->valideDonnees($_POST['email']));                       //on vérifie si l'email existe déjà dans la BDD
    $LogCheckExist = $dao->checkLogin($dao->valideDonnees($_POST['login']));             //on vérifie si le login existe déjà dans la BDD

    //on sécurise les données entrées par l'utilisateur pour éviter les injections SQL:
    $login = $dao->valideDonnees($_POST['login']);
    $pass = $dao->valideDonnees($_POST['pass']);
    $pass2 = $dao->valideDonnees($_POST['pass2']);
    $passHash = $dao->valideDonnees(password_hash($pass, PASSWORD_DEFAULT));

    $prenom = $dao->valideDonnees($_POST['prenom']);
    $nom = $dao->valideDonnees($_POST['nom']);
    $email = $dao->valideDonnees($_POST['email']);
    $phone = $dao->valideDonnees($_POST['phone']);
    $societe = $dao->valideDonnees($_POST['societe']);

    switch (true) {
        case $LogCheckExist && $LogCheckExist['Login'] == $login:
            $messageError = "Ce login est déjà utilisé.";
            break;
        case $mail && $mail['Mail_Client'] == $email:
            $messageError = "Cet email est déjà utilisé.";
            break;
        case ($_POST['pass'] != $_POST['pass2']):
            $messageError = "Les mots de passe ne correspondent pas.";
            break;
        default:
            $dao->AddUsers($Id_UserType, $login, $passHash);
            $dao->AddClient($societe, $nom, $prenom, $email, $phone, $Id_Commercial, $Id_Type_Client);
            $succes = "✔ Votre inscription a bien été validée";
            break;
    }
}





?>
<script>
    //fonction pour montrer les mots de passe:
    function filtreMdp() {

        let pass1 = document.getElementById("pass"); //on crée une variable pour le premier mot de passe
        let pass2 = document.getElementById("pass2"); //on crée une variable pour le deuxième mot de passe

        if (pass1.type === "password" && pass2.type === "password") { //si les mots de passe sont cachés, on les affiche:
            pass1.type = "text"; //on change le type de l'input pour afficher le mot de passe
            pass2.type = "text";
        } else {
            pass1.type = "password"; //sinon, on les cache:                       
            pass2.type = "password";
        }
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
    <title>Inscription</title>

    <style>
        .boutonInsc {
            background: #DDD6C4;
        }

        .boutonInsc:hover {
            background: #BF9C72;
        }
        main {
            height: 100vh !important;
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

                <a style="color:white;" href="connexion.php">se connecter</a>

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
                            <h2 style="font-family: 'Poppins', sans-serif;" class="text-uppercase text-center mb-5 fw-bolder">Inscription</h2>
                            <!-- formulaire d'inscription -->
                            <form method="POST">
                                <div class="row">
                                    <!-- Les quatre premiers champs à gauche -->
                                    <div class="col-md-6">
                                        <div class="col-auto mb-3">
                                            <div class="input-group">
                                                <div style="border: none;" class="input-group-text"><span class="material-symbols-outlined">person</span></div>
                                                <input style="border: none;" type="text" class="form-control" id="login" name="login" placeholder="Login*" title="Veuillez indiquer votre login" required>
                                            </div>
            
                                        </div>
                                        <div class="col-auto mb-3">
                                            <div class="input-group">
                                                <div style="border: none;" class="input-group-text"><span class="material-symbols-outlined">badge</span></div>
                                                <input style="border: none;" type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom*" title="Veuillez indiquer votre prénom" required>
                                            </div>
                                        </div>
                                        <div class="col-auto mb-3">
                                            <div class="input-group">
                                                <div style="border: none;" class="input-group-text"><span class="material-symbols-outlined">person</span></div>
                                                <input style="border: none;" type="text" class="form-control" id="nom" name="nom" placeholder="Nom*" title="Veuillez indiquer votre nom" required>
                                            </div>
                                        </div>
                                        <div class="col-auto mb-3">
                                            <div class="input-group">
                                                <div style="border: none;" class="input-group-text"><span class="material-symbols-outlined">mail</span></div>
                                                <input style="border: none;" type="email" class="form-control" id="email" name="email" title="Veuillez indiquer votre adresse mail" placeholder="Email*" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Les quatre derniers champs à droite -->
                                    <div class="col-md-6">
                                        <div class="col-auto mb-3">
                                            <div class="input-group">
                                                <div style="border: none;" class="input-group-text"><span class="material-symbols-outlined">call</span></div>
                                                <input style="border: none;" type="tel" class="form-control" id="phone" name="phone" title="Veuillez indiquer votre numéro de téléphone" placeholder="Tel">
                                            </div>
                                        </div>
                                        <div class="col-auto mb-3">
                                            <div class="input-group">
                                                <div style="border: none;" class="input-group-text"><span class="material-symbols-outlined">apartment</span></div>
                                                <input style="border: none;" type="text" class="form-control" id="societe" name="societe" title="Veuillez indiquer votre société" placeholder="Votre société ">
                                            </div>
                                        </div>
                                        <div class="col-auto mb-3">
                                            <div class="input-group">
                                                <div style="border: none;" class="input-group-text"><span class="material-symbols-outlined">key</span></div>
                                                <input style="border: none;" type="password" class="form-control" id="pass" name="pass" title="Indiquez votre mot de passe" placeholder="Mot de passe*" required>
                                            </div>
                                          
                                        </div>
                                        <div class="col-auto mb-3">
                                            <div class="input-group">
                                                <div style="border: none;" class="input-group-text"><span class="material-symbols-outlined">lock</span></div>
                                                <input style="border: none;" type="password" class="form-control" id="pass2" name="pass2" title="Veuillez répéter votre mot de passe" placeholder="Confirmez mdp*" required>
                                            </div>
                                             <br>
                                        </div>

                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                <span style="color:red;">
                                <?php if ($messageError) { print $messageError;}?>
                                </span>  
                                </div>
                                <div class="d-flex justify-content-center gap-1 mb-3 ">
                                    <input type="checkbox" onclick="filtreMdp()"> Afficher les mots de passe
                                </div>


                                <div class="d-flex justify-content-center" style="color:green;" id="messValidInscrip">
                                    <?php
                                    if ($succes) {
                                        print $succes;
                                    }
                                    ?>
                                </div>

                                <!-- bouton pour s'inscrire -->
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="submit" name="register" class="boutonInsc btn btn btn-lg gradient-custom-4 text-body">S'inscrire</button>
                                </div>

                                <!-- lien pour se connecter si on a déjà un compte -->
                                <p class="text-center text-muted mt-4 mb-0">Vous avez déjà un compte ? <a href="connexion.php" class="fw-bold text-body"><u>Se connecter</u></a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
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