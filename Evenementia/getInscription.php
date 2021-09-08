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
    $req = $bdd->prepare('SELECT NomE FROM INSCRIRE WHERE PseudoV = :pseudo');
    $req->execute(array(
        'pseudo' => $pseudoV,
    ));


    $myArray = [];

    while($resultat = $req->Fetch()){

        array_push($myArray, $resultat['NomE']);
    }


    $myString = join(',',$myArray);

    echo $myString;
}catch(Exception $e){
    phpAlert('Erreur lors de l\'inscription à l\'événement, veuillez réessayer ultérieurement');
}
?>