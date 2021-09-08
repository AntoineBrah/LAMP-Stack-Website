<?PHP
session_start();

// On met cette condition pour rediriger l'utiliser sur la page sigin_server.php quand celui-ci est connecté
if(isset($_SESSION['Pseudo']))
    header('Location: signin_server.php');

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

        <!-- Ajout du contenu de connexion -->
        <?php include("signin.php"); ?>

        <!-- Ajout du contenu d'inscription -->
        <?php include("signup.php"); ?>

        <!-- Conteneur filter -->
        <div id='filter'></div>

        <!-- Ajout de la barre de navigation -->
        <?php include("navbar.php"); ?>

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