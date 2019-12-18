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

if($_SESSION['Groupe'] == 'ADMINISTRATEUR'){

    $pseudo = $_POST['pseudoContrib'];
    $nom = $_POST['nomContrib'];
    $prenom = $_POST['prenomContrib'];
    $genre = $_POST['genreContrib'];
    $email = $_POST['emailContrib'];
    $mdp = $_POST['mdpContrib'];



    try{

        $req = $bdd->prepare('INSERT INTO CONTRIBUTEUR (Pseudo, Mdp, Nom, Prenom, Genre, Email, PseudoA) VALUES (:pseudo, :mdp, :nom, :prenom, :genre, :email, :admin)');
        $req->execute(array(
            'pseudo' => $pseudo,
            'mdp' => $mdp,
            'nom' => $nom,
            'prenom' => $prenom,
            'genre' => $genre,
            'email' => $email,
            'admin' => $_SESSION['Pseudo']
        ));

        phpAlert('Félicitation, vous venez de créer le contributeur : ' . $pseudo . ' !');
    }catch(Exception $e){
        phpAlert('Erreur : le pseudonyme est déjà utilisé');
    }




}
else{
    phpAlert('Seul les administrateurs peuvent créer des contributeurs !');
}