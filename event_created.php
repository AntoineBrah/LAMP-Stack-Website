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

/* Ici on effectuera un contrôle des données saisies (expressions régulières...)


*/

// On déclare une variable estEnvoye afin de savoir si on peut afficher un message indiquant que l'événement
// a bien été créé
$estEnvoye = false; 

try{
    $req = $bdd->prepare('INSERT INTO EVENEMENT (Nom, Date_Evenement, Theme, Descriptif, EffectifMin, EffectifMax, PseudoC, Latitude, Longitude) VALUES (:nom, :date_evenement, :theme, :descriptif, :effectifmin, :effectifmax, :pseudo_c, :latitude, :longitude)');
    $req->execute(array(
        'nom' => $nomEvenement,
        'date_evenement' => $dateEvenement,
        'theme' => $categorieEvenement,
        'descriptif' => $descriptionEvenement,
        'effectifmin' => $effectifMinEvenement,
        'effectifmax' => $effectifMaxEvenement,
        'pseudo_c' => $_SESSION['Pseudo'],
        'latitude' => $latitudeEvenement,
        'longitude' => $longitudeEvenement
    ));

    $estEnvoye = true; // L'événement a bien été créé
}
catch(PDOException $e){
    function phpAlert($msg) {
        echo '<script type="text/javascript">
            alert("' . $msg . '");
            window.location.href=\'sigin_server.php\';
        </script>';
    }

    phpAlert('Erreur lors de l\'envoi de la requête : ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>Evenementia</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/css?family=Bree+Serif|Vast+Shadow&amp;display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet"> 
        <link href="https://fonts.googleapis.com/css?family=Signika+Negative|Spectral&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
        <link rel="icon" type="image/png" href="logofavico.png">
    </head>
    <body>
        <!-- Ajout de la barre de navigation modifiée -->
        <?php include("navbar2.php"); ?>

        <!-- Ajout du header -->
        <?php include("header.php") ?>

        <!-- Contenu principal de la page-->
        <main>
            <!-- Préambule -->
            <?php include('preamble.php'); ?>

            <!-- Formulaire de recherche d'événement -->
            <?php include('search_event.php'); ?>

            <!-- Formulaire de création d'événement -->
            <?php include("create_event.php"); ?>
        </main>

        <!-- Ajout du footer -->
        <?php include("footer.php") ?>

        <script src='https://kit.fontawesome.com/a076d05399.js'></script>
        <script src='nav_settings.js'></script>
        <script src='header_slideshow.js'></script>
        <script src='search_event_settings.js'></script>
        <script src='form_parse.js'></script>
    </body>
</html>