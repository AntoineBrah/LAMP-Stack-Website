<?php
session_start();

try{
    $bdd = new PDO('mysql:host=localhost;dbname=Evenementia;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    //echo 'BDD : OK.<br><br>';
}
catch (Exception $e){
    die('Erreur : ' . $e->getMessage());
}

// Si l'utilisateur essaye de créer un événement sans cliquer sur le bouton du formulaire il est redirigé sur la page d'accueil
if(!isset($_POST['saisieNomEvenement'])){
    echo '<script>window.location.href=\'index.php\'</script>';
}
// On regarde si l'utilisateur est connecté ou pas
else if(!isset($_SESSION['Pseudo'])){
    function phpAlert($msg) {
        echo '<script type="text/javascript">
            alert("' . $msg . '");
            window.location.href=\'index.php\';
        </script>';
    }

    phpAlert('Vous devez être connecté pour pouvoir réaliser cette action');
}
else{

    function phpAlert($msg) {
        echo '<script type="text/javascript">
            alert("' . $msg . '");
            window.location.href=\'index.php\';
        </script>';
    }

    // Si l'utilisateur est bien connecté alors on regarde qu'il ne soit pas de type Visiteur ou Administrateur (seul un Contributeur peut créer un événement)
    if(strtolower($_SESSION['Groupe']) != 'contributeur'){
        // On définit une Alertbox en PHP qui redirige sur la page signin_server.php
        phpAlert('Vous n\'avez pas les droits pour pouvoir réaliser cette action');
    }
}

$nomEvenement = $_POST['saisieNomEvenement'];
$categorieEvenement = $_POST['categorieEvenement'];
$dateEvenement = $_POST['dateEvenementCreation'];
$effectifMinEvenement = $_POST['effectifMinCreation'];
$effectifMaxEvenement = $_POST['effectifMaxCreation'];
$latitudeEvenement = $_POST['latitudeEvenement'];
$longitudeEvenement = $_POST['longitudeEvenement'];
$descriptionEvenement = $_POST['descriptionEvenement'];



/**********************************************************************************************/
/********** OBTENTION DE RÉGION/DÉPARTEMENT/VILLE A PARTIR DE LA LATITUDE/LONGITUDE ***********/
/**********************************************************************************************/

// Permet de supprimer les accents d'une chaine de caractère
// On va l'utiliser pour enlever les accents et ainsi effectuer des comparaisons
function skip_accents( $str, $charset='utf-8' ) {
    $str = htmlentities( $str, ENT_NOQUOTES, $charset );
    $str = preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str );
    $str = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $str );
    $str = preg_replace( '#&[^;]+;#', '', $str );
    return $str;
}

//Les @ permettent de cacher les erreurs (cas ou l'utilisateur entrerait des valeurs incohérentes pour la latitude/longitude)
@$url = 'http://open.mapquestapi.com/nominatim/v1/reverse.php?key=Ajv621GZGf6AsnuaCpjM5qOH9NfLPqCS&format=json&lat=' . $latitudeEvenement . '&lon=' . $longitudeEvenement;
@$json = file_get_contents($url);

@$data = json_decode($json, TRUE);

@$areaInformation = strtolower(skip_accents($data['display_name']));

// Ici on récupère le nom de la région ainsi que de la commune (ville ou village)
// Pour le département c'est un peu plus compliqué...
$nomRegion = 'undefined';
$nomCommune = 'undefined';

if(!isset($data['address']['village'])){
    $nomRegion = $data['address']['state'];
    $nomCommune = $data['address']['county'];
}
else{
    $nomRegion = $data['address']['state'];
    $nomCommune = $data['address']['village'];
}


//echo $areaInformation;

/********************/


$localUrl = 'departement.json';
$localJson = file_get_contents($localUrl);
$localData = json_decode($localJson, TRUE);

//print_r($localData[0]['departmentName']);

/*******************/

$listeDepartement = array(); // On déclare un tableau vide

for($i=0; $i<count($localData); $i++){
    array_push($listeDepartement, $localData[$i]['departmentName']);
}

/* Le tableau 'listeDepartement contient désormais tous les départements de France */

/* Maintenant on va essayer d'extraire à partir du 'display_name' obtenu par les données longitude et latitude, le département précis */
/* Les données sont de la forme (ex) 'Préfecture Côtes d'Armor (22), Place du Général de Gaulle, Saint-Brieuc, Côtes-d'Armor, Bretagne, France métropolitaine, 22000, France' */
/* Ce qui nous intéresse est uniquement le département (ici c'est donc 'Côtes-d'Armor) */

$nomDepartement = 'undefined';

// On regarde si une chaine de caractère (département) de $listeDepartement est une sous-chaîne de la chaine $data
for($i=0; $i<count($localData); $i++){

    /* J'ai eu des problèmes pour obtenir la sous-chaine, en effet le département Ain (par exemple) est reconnu dans certains mot comme : 'sAINt-brieuc'... */
    /* Donc obligation de s'assurer que le caractère présent juste avant la première lettre du mot que l'on cherche dans la chaine est un espace */
    if(strpos($areaInformation, strtolower(skip_accents($localData[$i]['departmentName'])))){
        if(($areaInformation[strpos($areaInformation, strtolower(skip_accents($localData[$i]['departmentName'])))-1]) == ' '){
            $nomDepartement = $localData[$i]['departmentName']; //On obtient le nom du département
            break; //On a le nom du département plus besoin de continuer à itérer
        }
    }
}

/**********************************************************************************************/
/**********************************************************************************************/


/* Ici on effectuera un contrôle des données saisies (expressions régulières...)


*/


// On déclare une variable estEnvoye afin de savoir si on peut afficher un message indiquant que l'événement
// a bien été créé
$estEnvoye = false; 

// Si on ne peut pas déterminer la région à partir des coordonnées GPS alors cela signifie que la longitude et la latitude
// fournies sont fausses
if($nomRegion == 'undefined'){
    function phpAlert($msg) {
        echo '<script type="text/javascript">
            alert("' . $msg . '");
            window.location.href=\'index.php\';
        </script>';
    }

    phpAlert('Erreur : la latitude et longitude que vous avez saisi ne correspondent a aucune coordonnée géographique situé en France.');
}
else{

    if(isset($_SESSION['Pseudo']) && (strtolower($_SESSION['Groupe']) == 'contributeur')){
        try{      
            $req = $bdd->prepare('INSERT INTO EVENEMENT (Nom, Date_Evenement, Theme, Descriptif, EffectifMin, EffectifMax, PseudoC, Latitude, Longitude, Region, Departement, Commune) VALUES (:nom, :date_evenement, :theme, :descriptif, :effectifmin, :effectifmax, :pseudo_c, :latitude, :longitude, :region, :departement, :commune)');
            $req->execute(array(
                'nom' => $nomEvenement,
                'date_evenement' => $dateEvenement,
                'theme' => $categorieEvenement,
                'descriptif' => $descriptionEvenement,
                'effectifmin' => $effectifMinEvenement,
                'effectifmax' => $effectifMaxEvenement,
                'pseudo_c' => $_SESSION['Pseudo'],
                'latitude' => $latitudeEvenement,
                'longitude' => $longitudeEvenement,
                'region' => $nomRegion,
                'departement' => $nomDepartement,
                'commune' => $nomCommune
            ));

            $estEnvoye = true; // L'événement a bien été créé
            phpAlert('L\'évenement a bien été créé !');
        }
        catch(PDOException $e){    
            phpAlert('Erreur : ce nom d\'événement est déjà utilisé');
        }
    }
}



?>