<?php 
session_start();

try{
    $bdd = new PDO('mysql:host=localhost;dbname=Evenementia;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    //echo 'BDD : OK.<br><br>';
}
catch (Exception $e){
    die('Erreur : ' . $e->getMessage());
}

function phpAlert($msg) {
    echo '<script type="text/javascript">
        alert("' . $msg . '");
        window.location.href=\'index.php\';
    </script>';
}

$newTheme = $_POST['creerTheme'];

if($_SESSION['Groupe'] == 'ADMINISTRATEUR'){

    
    try{
        $req = $bdd->prepare('INSERT INTO THEME (Theme, PseudoA) VALUES (:theme, :pseudoA)');
        $req->execute(array(
            'theme' => $newTheme,
            'pseudoA' => $_SESSION['Pseudo']
        ));

        phpAlert('Félicitation, vous venez de le thème : ' . $newTheme . ' !');
    }catch(Exception $e){
        phpAlert('Erreur : Le thème que vous essayez de créer existe déjà !');
    }




}
else{
    phpAlert('Seul les administrateurs peuvent créer des thèmes !');
}