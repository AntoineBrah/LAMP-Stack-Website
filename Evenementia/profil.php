<?php 
try{
    $bdd = new PDO('mysql:host=localhost;dbname=Evenementia;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    //echo 'BDD : OK.<br><br>';
}
catch (Exception $e){
    die('Erreur : ' . $e->getMessage());
}

// Permet de supprimer les accents d'une chaine de caractère
// On va l'utiliser pour enlever les accents et ainsi effectuer des comparaisons
function without_accents( $str, $charset='utf-8' ) {
    $str = htmlentities( $str, ENT_NOQUOTES, $charset );
    $str = preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str );
    $str = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $str );
    $str = preg_replace( '#&[^;]+;#', '', $str );
    return $str;
}

$reqTheme = $bdd->query('SELECT Theme FROM THEME');

?>


<div id='profil-block'>
    <div class='profil-block-header'>
        <h2>Mon Profil</h2>
    </div>
    <div class='profil-block-separator'></div>
    <div class='profil-information'>
        <p>Pseudonyme : <strong class='Spseudo'><?php echo $_SESSION['Pseudo'] ?></strong></p>
        <p>Nom : <strong class='Snom'><?php echo $_SESSION['Nom'] ?></strong></p>
        <p>Prénom : <strong class='Sprenom'><?php echo $_SESSION['Prenom'] ?></strong></p>
        <p>Type d'utilisateur : <strong class='Sgroupe'><?php echo $_SESSION['Groupe'][0] . strtolower(substr($_SESSION['Groupe'], 1))  ?></strong></p>
        <p>Genre : <strong class='Sgenre'><?php echo $_SESSION['Genre'] ?></strong></p>
        <p>Adresse e-mail : <strong class='Semail'><?php echo $_SESSION['Email'] ?></strong></p>
    </div>
    <div class='profil-block-admin-tool'>
        <div class='profil-block-admin-tool-header'>
            <h3>Outil administrateur</h3>
        </div>
        <div class='profil-block-admin-tool-create'>
            <div class='profil-block-admin-tool-createContrib'>
                <div class='profil-block-admin-tool-createContrib-header'>
                    <h2>Création contributeur</h4>
                </div>
                <form id='createContributeur' method='POST' action='createContributor.php'>
                    <input type='text' name='pseudoContrib' class='pseudoContrib' placeholder='Pseudo' pattern='^[A-Za-z0-9]{3,20}$'>
                    <input type='text' name='nomContrib' class='nomContrib' placeholder='Nom' pattern='^[A-Za-z]{2,20}$'>
                    <input type='text' name='prenomContrib' class='prenomContrib' placeholder='Prenom' pattern='^[A-Za-z]{2,20}$'>
                    <select name="genreContrib" class='genreContrib'>
                        <option value="Homme">Homme</option>
                        <option value="Femme">Femme</option>
                        <option value="Autre">Autre</option>
                    </select>
                    <input type='email' name='emailContrib' class='emailContrib' placeholder='Email'>
                    <input type='password' name='mdpContrib' class='mdpContrib' placeholder='Mot de passe' pattern=".{5,}">
                    <input type='password' name='mdpcContrib' class='mdpcContrib' placeholder='Confirmer mot de passe' pattern=".{5,}">
                    <input type='submit' value='envoyer'>
                </form>
            </div>
            <div class='profil-block-admin-tool-createTheme'>
                <div class='profil-block-admin-tool-createTheme-first'>
                    <div class='profil-block-admin-tool-createTheme-header'>
                        <h2>Supprimer un thème</h3>
                    </div>
                    <form id='deleteTheme'>
                        <select>
                            <?php 
                            while($theme = $reqTheme->Fetch()){
                                echo '<option value=' . without_accents(strtolower($theme['Theme'])) . '>' . $theme['Theme'] . '</option>';
                            }
                            ?>
                        </select>
                        <input type='submit' value='Supprimer'>
                    </form>
                </div>
                <div class='profil-block-admin-tool-createTheme-second'>      
                    <div class='profil-block-admin-tool-createTheme-header2'>
                        <h2>Créer un thème</h3>
                    </div>
                    <form id='createTheme' method='POST' action='createTheme.php'>
                        <input type='text' name='creerTheme' placeholder='Thème' pattern='^[A-Za-z]{2,20}$'>
                        <input type='submit' value='Créer'>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
