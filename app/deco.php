<?php
ob_start();
session_start();                                         //on démarre la session pour pouvoir utiliser les variables de session
unset($_SESSION['Login']);                               //on détruit la variable de session du login
unset($_SESSION['Id_UserType']);                         //on détruit la variable de session de l'Id_UserType


if (ini_get("session.use_cookies")) {                    //on vérifie si les cookies sont activés
    setcookie(session_name(), '', time() - 3600);        //on détruit le cookie de session
}

session_destroy();                                       //on détruit la session
header('location: index.php');                       

ob_end_flush();
?>