<?php
session_start();

try{
    $bdd = new PDO('mysql:host=localhost;dbname=Evenementia;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    //echo 'BDD : OK.<br><br>';
}
catch (Exception $e){
    die('Erreur : ' . $e->getMessage());
}

// On regarde si l'utilisateur est connecté ou pas
if(!isset($_SESSION['Pseudo'])){
    // On définit une Alertbox en PHP qui redirige sur la page d'accueil
    function phpAlert($msg) {
        echo '<script type="text/javascript">
            alert("' . $msg . '");
            window.location.href=\'index.php\';
        </script>';
    }

    phpAlert('Vous devez être connecté pour pouvoir réaliser cette action');
}
else{
    // Si l'utilisateur est bien connecté alors on regarde qu'il ne soit pas de type Visiteur (seul un Contributeur ou un Admin peut créer un événement)
    if(strtolower($_SESSION['Groupe']) == 'visiteur'){
        // On définit une Alertbox en PHP qui redirige sur la page signin_server.php
        function phpAlert($msg) {
            echo '<script type="text/javascript">
                alert("' . $msg . '");
                window.location.href=\'signin_server.php\';
            </script>';
        }

        phpAlert('Vous n\'avez pas les droits pour pouvoir réaliser cette action');
    }
}

$nomEvenement = $_POST['saisieNomEvenement'];
$categorieEvenement = $_POST['categorieEvenement'];
$dateEvenement = $_POST['dateEvenementCreation'];
$effectifMinEvenement = $_POST['effectifMinCreation'];
$effectifMaxEvenement = $_POST['effectifMaxCreation'];
$latitudeEvenement = $_POST['latitudeEvenement'];
$longitudeEvenement = $_POST['longitudeEvenement'];
$descriptionEvenement = $_POST['descriptionEvenement'];

$req = $bdd->prepare('INSERT INTO EVENEMENT (Nom, Date_Evenement, Them, Descriptif, EffectifMin, EffectifMax, PseudoC, Latitude, Longitude)')
 







?>