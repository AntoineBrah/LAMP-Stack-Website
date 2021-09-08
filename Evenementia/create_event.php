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
function no_accents( $str, $charset='utf-8' ) {
    $str = htmlentities( $str, ENT_NOQUOTES, $charset );
    $str = preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str );
    $str = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $str );
    $str = preg_replace( '#&[^;]+;#', '', $str );
    return $str;
}

$req = $bdd->query('SELECT Theme FROM THEME');

?>

<div id='main-create-event'>
    <div id='main-create-event-header'>
        <h2>Créez votre propre événement</h2>
    </div>
    <div class='event-separator'></div>
    <div id='main-create-event-content'>
        <form id='create-event' autocomplete="off" method='POST' action='event_created.php'>
            <div id='event-criteria'>
                <input type='text' name='saisieNomEvenement' id='saisieNomEvenement' placeholder="Saisissez un nom d'événement..." title="Veuillez saisir un nom d'événement : lettres/chiffres autorisés et 1 caractère 'espace' ou '-' autorisé" pattern='^[A-Za-z0-9]+[\\-\\ ]{0,1}[A-Za-z0-9]+$' required>
                <select name='categorieEvenement' id='create-event-category' placeholder='Événement' required>
                    <?php 
                    while($theme = $req->Fetch()){
                        echo '<option value=' . no_accents(strtolower($theme['Theme'])) . '>' . $theme['Theme'] . '</option>';
                    }

                    /*
                    <option value='ceremonie'>Cérémonie</option>
                    <option value='sport'>Sport</option>
                    <option value='festival'>Festival</option>
                    <option value='congres'>Congrès</option>
                    <option value='repas'>Repas</option>
                    <option value='exposition'>Exposition</option>
                    */
                    $req->closeCursor();
                    ?>
                </select>
                <input type='date' name='dateEvenementCreation' id='dateEvenementCreation' title="Date de l'événement" required>
                <input type='number' name='effectifMinCreation' id='effectifMinCreation' placeholder="Effectif minimum" title='Effectif minimum' min=0 max=1000000 required>
                <input type='number' name='effectifMaxCreation' id='effectifMaxCreation' placeholder="Effectif maximum" title='Effectif maximum' min=0 max=1000000 required>
                <filedset id='coordonneesGPS'>
                    <input type='text' name='latitudeEvenement' id='latitudeEvenement' placeholder="Latitude de l'événement" title='Latitude' required pattern='^-{0,1}[0-9]+\.[0-9]+$'>
                    <input type='text' name='longitudeEvenement' id='longitudeEvenement' placeholder="Longitude de l'événement" title='Longitude' required pattern='^-{0,1}[0-9]+\.[0-9]+$'>
                </fieldset>
            </div>
            <div id='create-event-additional-information'>
                <input type='file' name="fileToUpload" id="fileToUpload">
                <input type='button' id='alternativeUpload'>
                <textarea name='descriptionEvenement' id='event-description' placeholder="Veuillez donner une description de votre événement..." required></textarea>
            </div>
            <input type='submit' id='submitCreateEvent' placeholder="Rechercher" value='Créer'>
        </form>
    </div>
    <div class='event-separator'></div>
    <div id='main-create-event-result'>
        <!-- Ici on met si l'événement c'est bien créé ou non -->
    </div>
</div>