<?php

try{
    $bdd = new PDO('mysql:host=localhost;dbname=Evenementia;charset=utf8', 'root', '');
    //echo '<br><br> BDD : OK.';
}
catch (Exception $e){
    die('Erreur : ' . $e->getMessage());
}

// On définit une Alertbox en PHP
function phpAlert($msg) {
    echo '<script type="text/javascript">
        alert("' . $msg . '");
        window.location.href=\'index.php\';
    </script>';
}

/* On effectuera des vérifications du type :

    - Expressions régulières sur toutes les variables d'inscription
    - Concordance des mdps fournies
*/

$pseudonyme = htmlspecialchars($_POST['pseudonyme']);
$nom = htmlspecialchars($_POST['nom']);
$prenom = htmlspecialchars($_POST['prenom']);
$typeUtilisateur = $_POST['type-utilisateur'];
$genre = $_POST['genre'];
$adresseEmail = htmlspecialchars($_POST['adresseEmail']);
$mdp = password_hash(htmlspecialchars($_POST['mdp']), PASSWORD_DEFAULT); // On hash le mot de passe

// Ajout d'un contributeur dans la BDD
if($typeUtilisateur == 'contributeur'){

    $getPseudo = $bdd->query('SELECT Pseudo FROM MEMBRE');
    $existePseudo = false;

    while($tupleMembre = $getPseudo->fetch()){
        if(strtolower($tupleMembre['Pseudo']) == strtolower($pseudonyme)){
            $existePseudo = true;
        }
    }

    $getPseudo->closeCursor(); //On ferme les résultats de recherche après avoir traité chaque requête

    if(!$existePseudo){

        $req = $bdd->prepare('INSERT INTO MEMBRE (Pseudo, Groupe) VALUES (:pseudo, :groupe)');
        $req->execute(array(
            'pseudo' => $pseudonyme,
            'groupe' => $typeUtilisateur
        ));

        $req = $bdd->prepare('INSERT INTO CONTRIBUTEUR (Pseudo, Mdp, Nom, Prenom, Genre, Email, PseudoA) VALUES (:pseudo, :mdp, :nom, :prenom, :genre, :email, NULL)');
        $req->execute(array(
            'pseudo' => $pseudonyme,
            'mdp' => $mdp,
            'nom' => $nom,
            'prenom' => $prenom,
            'genre' => $genre,
            'email' => $adresseEmail
        ));
    

        phpAlert('L\'inscription a bien été prise en compte. Veuillez vous connecter.');
    }
    else{
        phpAlert('Le pseudonyme est déjà pris, veuillez en choisir un autre.');
    }
}

// Ajout d'un visiteur dans la BDD
if($typeUtilisateur == 'visiteur'){

    $getPseudo = $bdd->query('SELECT Pseudo FROM MEMBRE');
    $existePseudo = false;

    while($tupleMembre = $getPseudo->fetch()){
        if(strtolower($tupleMembre['Pseudo']) == strtolower($pseudonyme)){
            $existePseudo = true;
        }
    }

    $getPseudo->closeCursor(); //On ferme les résultats de recherche après avoir traité chaque requête

    if(!$existePseudo){

        $req = $bdd->prepare('INSERT INTO MEMBRE (Pseudo, Groupe) VALUES (:pseudo, :groupe)');
        $req->execute(array(
            'pseudo' => $pseudonyme,
            'groupe' => $typeUtilisateur
        ));


        $req = $bdd->prepare('INSERT INTO VISITEUR (Pseudo, Mdp, Nom, Prenom, Genre, Email) VALUES (:pseudo, :mdp, :nom, :prenom, :genre, :email)');
        $req->execute(array(
            'pseudo' => $pseudonyme,
            'mdp' => $mdp,
            'nom' => $nom,
            'prenom' => $prenom,
            'genre' => $genre,
            'email' => $adresseEmail
        ));

        phpAlert('L\'inscription a bien été prise en compte. Veuillez vous connecter.');
    }
    else{
        phpAlert('Le pseudonyme est déjà pris, veuillez en choisir un autre.');
    }
}
?>