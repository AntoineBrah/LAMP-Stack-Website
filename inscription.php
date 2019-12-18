<?php
try{
    $bdd = new PDO('mysql:host=localhost;dbname=Evenementia;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    //echo 'BDD : OK.<br><br>';
}
catch (Exception $e){
    die('Erreur : ' . $e->getMessage());
}

$pseudoV = $_POST['PseudoV'];
$nomE = $_POST['NomE'];

// On ajoute le visiteur et l'événement dans la table INSCRIRE
$req = $bdd->prepare('INSERT INTO INSCRIRE (PseudoV, NomE) VALUES (:pseudo, :evenement)');
$req->execute(array(
    'pseudo' => $pseudoV,
    'evenement' => $nomE
));

// On récupère la date de l'événement (pour savoir si l'utilisateur peut noter l'événement ou pas)
$reqDate = $bdd->prepare('SELECT Date_Evenement FROM EVENEMENT WHERE Nom = :evenement');
$reqDate->execute(array(
    'evenement' => $nomE
));

$resultDate = $reqDate->Fetch();

echo $resultDate['Date_Evenement'];

$reqDate->closeCursor();

?>