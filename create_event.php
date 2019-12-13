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
                    <option value='ceremonie'>Cérémonie</option>
                    <option value='sport'>Sport</option>
                    <option value='festival'>Festival</option>
                    <option value='congres'>Congrès</option>
                    <option value='repas'>Repas</option>
                    <option value='exposition'>Exposition</option>
                </select>
                <input type='date' name='dateEvenementCreation' id='dateEvenementCreation' title="Date de l'événement" required>
                <input type='number' name='effectifMinCreation' id='effectifMinCreation' placeholder="Effectif minimum" title='Effectif minimum' min=0 max=1000000 required>
                <input type='number' name='effectifMaxCreation' id='effectifMaxCreation' placeholder="Effectif maximum" title='Effectif maximum' min=0 max=1000000 required>
                <filedset id='coordonneesGPS'>
                    <input type='text' name='latitudeEvenement' id='latitudeEvenement' placeholder="Latitude de l'événement" title='Latitude' required>
                    <input type='text' name='longitudeEvenement' id='longitudeEvenement' placeholder="Longitude de l'événement" title='Longitude' required>
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