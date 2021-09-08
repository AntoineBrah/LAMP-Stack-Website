<?php
session_start();

try{
    $bdd = new PDO('mysql:host=localhost;dbname=Evenementia;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    //echo 'BDD : OK.<br><br>';
}
catch (Exception $e){
    die('Erreur : ' . $e->getMessage());
}

// Si l'utilisateur essaye de faire une recherche sans utiliser le formulaire il est immédiatement redirigé sur la page d'accueil
if(!isset($_POST['saisieDepartementRecherche']))
    echo '<script>window.location.href=\'index.php\'</script>';

// Permet de supprimer les accents d'une chaine de caractère
function skip_accents( $str, $charset='utf-8' ) {
    $str = htmlentities( $str, ENT_NOQUOTES, $charset );
    $str = preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str );
    $str = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $str );
    $str = preg_replace( '#&[^;]+;#', '', $str );
    return $str;
}

$departementEvenement = $_POST['saisieDepartementRecherche'];
$dateEvenement = $_POST['dateEvenement'];
$effectifMin = $_POST['effectifMin'];
$effectifMax = $_POST['effectifMax'];
$categorieEvenement = $_POST['categorie'];

/* ON EFFECTUERA ICI UNE VERIFICATION DES SAISIES UTILISATEUR (expressions régulières...) 


*/


//echo '-> ' . $departementEvenement . ',' . $dateEvenement . ',' . $effectifMin . ',' . $effectifMax . ',' . $categorieEvenement . '<br><br>';


/***************************************************************************************************************************************************************/
/* LE CODE QUI SUIT PERMET DE CONSTRUIRE AU FUR ET A MESURE LA REQUETE SQL EN RESPECTANT LE FAIT QUE L'UTILISATEUR N'EST PAS OBLIGÉ DE REMPLIR TOUS LES INPUTS */
/* AUQUEL CAS ON AFFICHE LE PLUS D'INFORMATION POSSIBLE, PAR EXEMPLE POUR AFFICHER TOUTES LES EVENEMETS, IL SUFFIT DE RIEN REMPLIR ET DE CLIQUER SUR RECHERCHER*/
/***************************************************************************************************************************************************************/

$arrayReq = array('SELECT', '*', 'FROM', 'EVENEMENT', 'WHERE', 'Departement', '=', '\'', $departementEvenement, '\'', 'AND', 'Date_Evenement',  '=', '\'', $dateEvenement, '\'', 'AND', 'EffectifMin', '=', $effectifMin, 'AND', 'EffectifMax', '=', $effectifMax, 'AND', 'Theme', '=', '\'', $categorieEvenement, '\'');

$nbAND = 0; //Variable servant à enlever les AND en trop car on peut finir avec des requetes du type : SELECT * FROM ... WHERE ... = ... AND ... = ... AND
// Voir feuille pour plus d'explication


if($departementEvenement == '' && $dateEvenement == '' && $effectifMin == '' && $effectifMax == '' && $categorieEvenement == ''){
    for($i=0; $i<26; $i++){
        array_pop($arrayReq);
    }

    //print_r($arrayReq);

}
else{
    if($departementEvenement == ''){
        $arrayReq[5] = '';
        $arrayReq[6] = '';
        $arrayReq[7] = ''; 
        $arrayReq[8] = ''; // est déjà vide
        $arrayReq[9] = '';
        $arrayReq[10] = '';
    }
    else
        $nbAND++;

    if($dateEvenement == ''){
        $arrayReq[11] = ''; // est déjà vide
        $arrayReq[12] = ''; 
        $arrayReq[13] = '';
        $arrayReq[14] = '';
        $arrayReq[15] = '';
        $arrayReq[16] = '';
    }
    else
        $nbAND++;

    if($effectifMin == ''){
        $arrayReq[17] = ''; // est déjà vide
        $arrayReq[18] = ''; 
        $arrayReq[19] = ''; 
        $arrayReq[20] = '';
    }
    else
        $nbAND++;

    if($effectifMax == ''){
        $arrayReq[21] = ''; // est déjà vide
        $arrayReq[22] = ''; 
        $arrayReq[23] = ''; 
        $arrayReq[24] = '';
    }
    else
        $nbAND++;

    if($categorieEvenement == ''){
        $arrayReq[25] = ''; // est déjà vide
        $arrayReq[26] = ''; 
        $arrayReq[27] = ''; 
        $arrayReq[28] = ''; 
        $arrayReq[29] = '';
    }
    else
        $nbAND++;
}


//Suppression du AND en trop
if($nbAND == 1 && ($departementEvenement != '' || $dateEvenement != '' || $effectifMin != '' || $effectifMax != '')){
    
    $cle = array_search('AND', $arrayReq); //On cherche la clé du AND
    unset($arrayReq[$cle]); //On supprime le AND du tableau en passant par sa clé
}




$reqPresqueFinale = join(" ",array_filter($arrayReq)); //On obtient la requete presque finale que l'on va communiquer à Mysql
//Le probleme est que quand une chaine de caractère est présente dans le where, il y a un espace en l'apostrophe ouvrant, la chaine et l'apostrophe fermant
//Comme ceci : ' Hérault ' et donc la requête est fausse

//On doit le faire pour toute valeur de type String présent dans le WHERE
if($departementEvenement != ''){
    $fpos = strpos($reqPresqueFinale, $departementEvenement);
    $lpos = $fpos + strlen($departementEvenement)-1;

    $reqPresqueFinale = str_split($reqPresqueFinale);

    unset($reqPresqueFinale[$fpos-1]);
    unset($reqPresqueFinale[$lpos+1]);

    $reqPresqueFinale = implode('', $reqPresqueFinale);
}
if($dateEvenement != ''){
    $fpos = strpos($reqPresqueFinale, $dateEvenement);
    $lpos = $fpos + strlen($dateEvenement)-1;

    $reqPresqueFinale = str_split($reqPresqueFinale);

    unset($reqPresqueFinale[$fpos-1]);
    unset($reqPresqueFinale[$lpos+1]);

    $reqPresqueFinale = implode('', $reqPresqueFinale);
}
if($categorieEvenement != ''){
    $fpos = strpos($reqPresqueFinale, $categorieEvenement);
    $lpos = $fpos + strlen($categorieEvenement)-1;

    $reqPresqueFinale = str_split($reqPresqueFinale);

    unset($reqPresqueFinale[$fpos-1]);
    unset($reqPresqueFinale[$lpos+1]);

    $reqPresqueFinale = implode('', $reqPresqueFinale);
}

$reqFinale = $reqPresqueFinale; // On obtient enfin la requête syntaxiquement correcte que l'on va pouvoir transmettre à mysql
/***************************************************************************************************************************************************************/
/***************************************************************************************************************************************************************/
/***************************************************************************************************************************************************************/



//print_r($reqFinale);

$req = $bdd->query($reqFinale);


// On déclare cette variable afin de savoir si la table que l'on reçoit est vide ou non
// si c'est le cas alors on affiche rien (on part de la supposition que oui)
$estVideRequete = true; 

if($req->rowCount() == 0){

    $req->closeCursor();

    function phpAlert($msg) {
        echo '<script type="text/javascript">
            alert("' . $msg . '");
            window.location.href=\'index.php\';
        </script>';
    }

    phpAlert('Erreur : Aucun résultat correspondant à votre recherche.');

}
else
    $estVideRequete = false;

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
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
        <!-- On ajoute le CSS Leaflet pour correctement styliser la carte -->
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

        <!-- Ajout de la barre de navigation et du block profil si l'utilisateur est connecté -->
        <?php 
        if(isset($_SESSION['Pseudo'])){
            include("navbar2.php");
            include("profil.php");
        }
        else
            include("navbar.php");
        ?>

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
        <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
        <!-- Ajout de la librairie Leaflet JS -->
        <script src='event_settings.js'></script>
        <script src='admintool.js'></script>
    </body>
</html>