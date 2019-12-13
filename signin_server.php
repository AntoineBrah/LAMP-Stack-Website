<?php
session_start();

try{
    $bdd = new PDO('mysql:host=localhost;dbname=Evenementia;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    //echo 'BDD : OK.<br><br>';
}
catch (Exception $e){
    die('Erreur : ' . $e->getMessage());
}


// On met cette condition pour vérifier que l'utilisateur n'est pas déjà connecté (auquel cas pas besoin de récupérer des infos dans la BDD...)
if(!isset($_SESSION['Pseudo'])){

    // On définit une Alertbox en PHP
    function phpAlert($msg) {
        echo '<script type="text/javascript">
            alert("' . $msg . '");
            window.location.href=\'index.php\';
        </script>';
    }

    $getMembre = $bdd->query('SELECT Pseudo FROM MEMBRE');
    $estMembre = false; // On regarde si l'utilisateur qui essai de se connecter est un membre ou pas

    while($membre = $getMembre->fetch()){
        if(strtolower($membre['Pseudo']) == strtolower($_POST['identifiant'])){
            $estMembre = true;
            break;
        }
    }

    $getMembre->closeCursor();

    if($estMembre){
        $getGroupe = $bdd->prepare('SELECT Groupe FROM MEMBRE WHERE Pseudo = :identifiant');
        $getGroupe->execute(array(
            'identifiant' => $_POST['identifiant']
        ));

        $groupe = $getGroupe->fetch(); // On obtient un tableau de 1 colonne, 1 tuple contenant le groupe de l'utilisateur essayant de se connecter
        $getGroupe->closeCursor(); // On ferme les résultats de recherche après avoir traité chaque requête

        $groupe = strtoupper($groupe['Groupe']); //On change le tableau en une chaine de caractère contenant le groupe de l'utilisateur essayant de se connecter
        // On le met en majuscule car dans le SGBD les attributs ne sont pas sensibles à la casse alors que le nom des tables si

        // IMPOSSIBLE d'utiliser les marqueurs nominatifs sur le nom d'une table (WTF?)
        $listeMembre = $bdd->prepare('SELECT Pseudo, Mdp FROM '. $groupe . ' WHERE Pseudo = :identifiant');
        $listeMembre->execute(array(
            'identifiant' => $_POST['identifiant']
        ));


        $utilisateur = $listeMembre->fetch(); // La variable est un tableau à 1 ligne et 2 colonnes (Pseudo, Mdp)
        $listeMembre->closeCursor(); // On ferme les résultats de recherche après avoir traité chaque requête


        $mdpCorrect = password_verify($_POST['motdepasse'], $utilisateur['Mdp']);

        if($mdpCorrect){
            //echo 'Félicitation : l\'utilisateur est bien connecté !';
            $_SESSION['Pseudo'] = $utilisateur['Pseudo'];
            $_SESSION['Groupe'] = $groupe;
            //echo '<br>L utilisateur est : ' . $_SESSION['Pseudo'] . "<br>Son groupe est : " . $_SESSION['Groupe'];
            //echo '<script>alert(\'Authentification réussie.\');</script>';
        }
        else{
            //echo 'Authentification échouée ! Vérifier le mdp saisi';
            //echo '<script>alert(\'Authentification échouée, veuillez vérifier votre mot de passe.\');</script>';
            phpAlert('Le mot de passe saisi est incorrect.');
        }
    }
    else{
        //echo 'L\'utilisateur n\'existe pas'; 
        //echo '<script>alert(\'L utilisateur n existe pas.\');</script>';
        phpAlert('L\'utilisateur n\'existe pas.');
    }
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