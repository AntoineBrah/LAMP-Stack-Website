<!--Conteneur inscription-->
<div id='signup-block'>
    <div class='signup-content'>
        <section class='inscription-infos'>
            <div class='inscription-infos-header'>
                <h2>Pourquoi créer un compte ?</h2>
            </div>
            <div class='inscription-infos-separateur'></div>
            <div class='inscription-infos-contenu'>
                <div class='inscription-infos-contenu-texte'>
                    <div class='inscription-infos-contenu-texte-logo'>
                        <i class="material-icons" style="font-size:50px">visibility</i>
                    </div>
                    <p>Consultez un large choix d'événements disponibles dans toute la France.</p>
                </div>
                <div class='inscription-infos-contenu-texte'>
                    <div class='inscription-infos-contenu-texte-logo'>
                        <i class="material-icons" style="font-size:50px">create</i>
                    </div>
                    <p>Créez vos propres événements en fournissant : un lieu, un thème, une date, un descriptif et l'effectif disponible.</p>
                </div>
                <div class='inscription-infos-contenu-texte'>
                        <div class='inscription-infos-contenu-texte-logo'>
                            <i class="material-icons" style="font-size:50px">trending_up</i>
                        </div>
                        <p>Visualisez les statistiques liées à vos annonces (nombre de fois où l'annonce a été vu).</p>
                </div>
                <div class='inscription-infos-contenu-texte'>
                        <div class='inscription-infos-contenu-texte-logo'>
                            <i class="material-icons" style="font-size:50px">chat_bubble</i>
                        </div>
                        <p>Notez et donnez votre avis sur un ou plusieurs événements auquel vous avez assisté.</p>
                </div>
                <div class='inscription-infos-contenu-texte'>
                        <div class='inscription-infos-contenu-texte-logo'>
                            <i class="material-icons" style="font-size:50px">notifications_active</i>
                        </div>
                        <p>Tenez-vous informé des derniers événements publiés.</p>
                </div>
            </div>
        </section>
        <div class='inscription-formulaire'>
            <div class='inscription-formulaire-header'>
                <h2>Créer un compte</h2>
            </div>
            <div class='inscription-formulaire-separateur'></div>

            <form id='inscription-formulaire-contenu' method='POST' action='signup_server.php'>
                <label for='inscription-formulaire-contenu-pseudonyme'>Pseudonyme *</label><input type='text' name='pseudonyme' id='inscription-formulaire-contenu-pseudonyme' title='Entre 3 et 20 caractères autorisés, lettres et chiffres uniquement' pattern='^[A-Za-z0-9]{3,20}$'>
                <label for='inscription-formulaire-contenu-nom'>Nom *</label><input type='text' name='nom' id='inscription-formulaire-contenu-nom' pattern='^[A-Za-z]{2,20}$'>
                <label for='inscription-formulaire-contenu-prenom'>Prénom *</label><input type='text' name='prenom' id='inscription-formulaire-contenu-prenom' pattern='^[A-Za-z]{2,20}$'>
                <label for='type-utilisateur'>Type d'utilisateur *</label>
                <select name='type-utilisateur' id='inscription-formulaire-contenu-typeUtilisateur'>
                    <option value='contributeur'>Contributeur</option>
                    <option value='visiteur'>Visiteur</option>
                </select>
                <label for="genre">Genre *</label>
                <select name="genre" id="inscription-formulaire-contenu-nom-genre">
                    <option value="Homme">Homme</option>
                    <option value="Femme">Femme</option>
                    <option value="Autre">Autre</option>
                </select>
                <label for='inscription-formulaire-contenu-email'>Adresse email *</label><input type='email' name='adresseEmail' id='inscription-formulaire-contenu-email'>
                <label for='inscription-formulaire-contenu-motdepasse'>Mot de passe *</label><input type='password' name='mdp' id='inscription-formulaire-contenu-motdepasse' title='Veuillez saisir au moins 5 caractères' pattern=".{5,}">
                <label for='inscription-formulaire-contenu-cmotdepasse'>Confirmation mot de passe *</label><input type='password' name='mdpc' id='inscription-formulaire-contenu-cmotdepasse' title='Veuillez saisir au moins 5 caractères' pattern=".{5,}">

                <div id='inscription-formulaire-contenu-complementaire'>
                    <p class='inscription-formulaire-erreur'></p>
                    <p>Les champs marqués d'un * sont obligatoires.</p>
                </div>

                <input type='submit' value='Créer mon compte' id='inscription-formulaire-contenu-soumettre'>
            </form>

        </div>
    </div>
</div>