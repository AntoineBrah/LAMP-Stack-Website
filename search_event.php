<div id='main-search-event'>
    <div id='main-search-event-header'>
        <h2>Choisissez l'événement qui vous convient</h2>
    </div>
    <div class='event-separator'></div>
    <div id='main-search-event-search'>
        <form id='search-event' autocomplete="off" method='POST' action='event_searched.php'>
            <div id='event-criteria'>
                <div class='autocomplete'>
                    <input type='text' name='saisieDepartementRecherche' id='saisieDepartementRecherche' placeholder="Saisissez un département..." title="Lieu de l'événement"> 
                </div>
                <input type='date' name='dateEvenement' id='dateEvenement' title="Date de l'événement">
                <input type='number' name='effectifMin' id='effectifMin' placeholder="Effectif minimum" title='Effectif minimum' min=0 max=1000000>
                <input type='number' name='effectifMax' id='effectifMax' placeholder="Effectif maximum" title='Effectif maximum' min=0 max=1000000>
                <input type='submit' id='submitSearchEvent' placeholder="Rechercher" value='Rechercher'>
            </div>
            <div id='search-event-category'>
                <input type='button' name='ceremonie' value='Cérémonie'>
                <input type='button' name='sport' value='Sport'>
                <input type='button' name='festival' value='Festival'>
                <input type='button' name='congres' value='Congrès'>
                <input type='button' name='repas' value='Repas'>
                <input type='button' name='exposition' value='Exposition'>
                <input type='hidden' id='choixCategorie' name='categorie' value=''>
            </div>
        </form>
    </div>
    <div class='event-separator'></div>
    <div id='main-search-event-content'>
        <!-- Ici on affiche tous les événements en fonction des critères de recherche -->
        




    </div>
</div>