<?php

try{
    $bdd = new PDO('mysql:host=localhost;dbname=Evenementia;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    //echo 'BDD : OK.<br><br>';
}
catch (Exception $e){
    die('Erreur : ' . $e->getMessage());
}

$pseudoV = $_POST['PseudoV'];
$nomE = $_POST['nomE'];
$commentaire = $_POST['commentaire'];

function phpAlertCommentaire($msg) {
    echo '<script type="text/javascript">
        alert("' . $msg . '");
        window.location.href=\'index.php\';
    </script>';
}

try{
    $req = $bdd->prepare('INSERT INTO COMMENTER (PseudoV, NomE, Commentaire) VALUES (:pseudo, :nomEvenement, :commentaire)');
    $req->execute(array(
        'pseudo' => $pseudoV,
        'nomEvenement' => $nomE,
        'commentaire' => $commentaire
    ));

    $req2 = $bdd->query('SELECT Commentaire, PseudoV FROM COMMENTER');

    $myArray = [];

    while($com = $req2->Fetch()){

        $chaine = $com['PseudoV'] . ' : ' . $com['Commentaire'];
        array_push($myArray, $chaine);
    }

    $req2->closeCursor();

    $myString = join(',',$myArray);

    echo $myString;
}catch(Exception $e){
    phpAlertCommentaire('Erreur : le message n\'a pas pu être posté');
}


?>