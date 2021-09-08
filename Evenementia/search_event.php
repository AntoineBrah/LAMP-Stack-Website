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
                <input type='button' name='' value='Autre'>
                <input type='hidden' id='choixCategorie' name='categorie' value=''>
            </div>
        </form>
    </div>
    <div id='main-search-event-content'>
        <!-- Ici on affiche tous les événements en fonction des critères de recherche -->
        <?php 
        // Si la requete existe et n'est pas vide alors on affiche les événements sinon on ne fait rien
        if(isset($estVideRequete) && !$estVideRequete){


            while($donnee = $req->fetch()){

                /* OBTENTION DES COMMENTAIRES D'UN ÉVÉNEMENT DONNÉ */
                $reqComEvenement = $bdd->prepare('SELECT Commentaire, PseudoV FROM COMMENTER WHERE NomE = :nomEvenement');
                $reqComEvenement->execute(array(
                    'nomEvenement' => $donnee['Nom']
                ));

                /* OBTENTION DE LA MOYENNE DES NOTES D'UN ÉVÉNEMENT DONNÉ */
                $reqNoteMoyenneEvenement = $bdd->prepare('SELECT AVG(Note) AS Moyenne FROM NOTER WHERE NomE = :nomEvenement');
                $reqNoteMoyenneEvenement->execute(array(
                    'nomEvenement' => $donnee['Nom']
                ));

                $tupleReq = $reqNoteMoyenneEvenement->fetch();
                $noteEvenement = 'undefined';

                if($tupleReq['Moyenne']){
                    $noteEvenement = $tupleReq['Moyenne'];
                    $noteEvenement = round(floatval($noteEvenement), 1); // On converti en chaine de caractère puis on arrondi 1 au dixieme
                }
                else
                    $noteEvenement = 'Pas de note ☹️';
            
                $reqNoteMoyenneEvenement->closeCursor();
                /*********************************************************/

                /* SAVOIR SI L'ÉVÉNEMENT EST EN COURS OU NON */
                $dateActuelle = date('Y m d');
                $dateActuelle = str_replace(' ', '-', $dateActuelle);

                $statut = 'undefined';

               if($dateActuelle > $donnee['Date_Evenement']){
                   $statut = 'Terminé';
               }
               else{
                   $statut = 'En cours';
               }

               /**********************************************************/

                echo '<div class=\'evenement\'>
                    <div class=\'evenement-header\'>
                        <h3 class=\'evenement-header-titre\' title=\'Cliquez ici pour afficher la carte correspondante\'>' . $donnee['Nom'] . '</h3>
                    </div>
                    <div class=\'evenement-main\'>
                        <div class=\'evenement-main-image\'>
                        
                        </div>
                        <div class=\'evenement-main-information\'>
                            <div id=\'PseudoC\'>' . $donnee['PseudoC'] . '</div>
                            <div id=\'Localisation\' class=\'' . $donnee['Latitude'] . ',' . $donnee['Longitude'] . '\'>' . $donnee['Region'] . ', ' . $donnee['Departement'] . ', ' . $donnee['Commune'] . '</div>
                            <div id=\'Theme\'>' . $donnee['Theme'] . '</div>
                            <div id=\'Date\'>' . $donnee['Date_Evenement'] . '</div>
                            <div id=\'Effectif\'>
                                <div class=\'EffectifMin\'>' . $donnee['EffectifMin'] . '</div>
                                -
                                <div class=\'EffectifMax\'>' . $donnee['EffectifMax'] . '</div>
                            </div>
                            <div id=\'Description\'><p>' . $donnee['Descriptif'] . '</p></div>
                        </div>
                    </div>
                    <div id=\'evenement-footer\' class=\'visible\'>
                        <div id=\'mapid\'>

                        </div>
                        <div class=\'evenement-footer-additionnalContent\'>
                            <div class=\'evenement-footer-additionnalContent-inscrire\'>
                                <div class=\'evenement-footer-additionnalContent-inscrire-header\'>
                                    <h4>Participer à l\'événement ?</h4>
                                </div>
                                <div class=\'evenement-footer-additionnalContent-inscrire-header-evaluation\'>
                                    <div class=\'event-subscribe\'>
                                        <button id=\'inscrire\'>Rejoindre<i class="fa fa-check" style="font-size:18px"></i></button>
                                    </div>
                                    <div class=\'event-rate\'>
                                        <div class=\'event-rate-note\'>
                                            <input id=\'note\' type=\'text\' name=\'note\'> /10
                                        </div>
                                        <button id=\'send-note\'>Envoyer</button>
                                    </div>            
                                </div>
                            </div>
                            <div class=\'evenement-footer-additionnalContent-subscribe\'>
                                <div class=\'evenement-footer-additionnalContent-subscribe-header\'>
                                    <div class=\'evenement-footer-additionnalContent-subscribe-header-first\'>
                                        <p>Statut : <strong id=\'statutEvenement\'>' . $statut .'</strong></p>
                                    </div>
                                    <div class=\'evenement-footer-additionnalContent-subscribe-header-second\'>
                                        <p>Note globale :  <strong id=\'noteGlobale\'>' . $noteEvenement .'</strong></p>
                                    </div>
                                </div>
                                <div class=\'evenement-footer-additionnalContent-subscribe-main-content\'>
                                    <div class=\'commenterEvent\'>
                                        <textarea id=\'commentaireVisiteur\'placeholder=\'Écrivez votre commentaire ici...\'></textarea>
                                        <button id=\'sendCommentaireVisiteur\'>Send</button>
                                    </div>
                                    <div class=\'commentaires\'>';
                                        while($com = $reqComEvenement->Fetch()){
                                            echo '<p>' . $com['PseudoV'] . ' : ' . $com['Commentaire'] . '</p>';
                                        }
                                        $reqComEvenement->closeCursor();
                                        echo '
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            }
            $req->closeCursor();
        }
        ?>
    </div>
</div>