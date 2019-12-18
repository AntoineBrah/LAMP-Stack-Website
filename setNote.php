<?php

try{
    $bdd = new PDO('mysql:host=localhost;dbname=Evenementia;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    //echo 'BDD : OK.<br><br>';
}
catch (Exception $e){
    die('Erreur : ' . $e->getMessage());
}

function phpAlertSetNote($msg) {
    echo '<script type="text/javascript">
        alert("' . $msg . '");
        window.location.href=\'index.php\';
    </script>';
}

$pseudoV = $_POST['PseudoV'];
$nomE = $_POST['nomE'];
$note = $_POST['note'];

try{
    $req = $bdd->prepare('INSERT INTO NOTER (PseudoV, NomE, Note) VALUES (:pseudo, :nomEvenement, :note)');
    $req->execute(array(
        'pseudo' => $pseudoV,
        'nomEvenement' => $nomE,
        'note' => $note
    ));

    $req2 = $bdd->prepare('SELECT AVG(Note) AS Moyenne FROM NOTER WHERE NomE = :nomEvenement');

    $req2->execute(array(
        'nomEvenement' => $nomE
    ));

    $response = $req2->Fetch();

    $req2->closeCursor();

    echo $response['Moyenne'];
}catch(Exception $e){
    phpAlertSetNote('Erreur : la note n\'a pas pu être attribué');
}
?>