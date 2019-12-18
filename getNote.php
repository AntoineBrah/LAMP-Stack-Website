<?php

try{
    $bdd = new PDO('mysql:host=localhost;dbname=Evenementia;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    //echo 'BDD : OK.<br><br>';
}
catch (Exception $e){
    die('Erreur : ' . $e->getMessage());
}

function phpAlertInscription($msg) {
    echo '<script type="text/javascript">
        alert("' . $msg . '");
        window.location.href=\'index.php\';
    </script>';
}

$pseudoV = $_POST['PseudoV'];

try{
    $req = $bdd->prepare('SELECT NomE FROM NOTER WHERE PseudoV = :pseudo');
    $req->execute(array(
        'pseudo' => $pseudoV,
    ));


    $myArray = [];

    while($resultat = $req->Fetch()){

        array_push($myArray, $resultat['NomE']);
    }

    $req->closeCursor();
    $myString = join(',',$myArray);

    echo $myString;
}catch(Exception $e){
    phpAlertInscription('Erreur lors de l\'attribution de la note, veuillez réessayer ultérieurement');
}
?>