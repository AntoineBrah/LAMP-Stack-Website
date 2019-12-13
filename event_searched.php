<?php
session_start();

try{
    $bdd = new PDO('mysql:host=localhost;dbname=Evenementia;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    //echo 'BDD : OK.<br><br>';
}
catch (Exception $e){
    die('Erreur : ' . $e->getMessage());
}


// Permet de supprimer les accents d'une chaine de caractère
function skip_accents( $str, $charset='utf-8' ) {
    $str = htmlentities( $str, ENT_NOQUOTES, $charset );
    $str = preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str );
    $str = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $str );
    $str = preg_replace( '#&[^;]+;#', '', $str );
    return $str;
}

$departementEvenement = skip_accents($_POST['saisieDepartementRecherche']);
$dateEvenement = $_POST['dateEvenement'];
$effectifMin = $_POST['effectifMin'];
$effectifMax = $_POST['effectifMax'];
$categorieEvenement = $_POST['categorie'];

echo $departementEvenement . ',' . $dateEvenement . ',' . $effectifMin . ',' . $effectifMax . ',' . $categorieEvenement; 
 



?>