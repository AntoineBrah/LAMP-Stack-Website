<!-- Conteneur connexion -->
<div id='signin-block'>
    <div class='signin-block-header'>
        <h2>Connexion</h2>
    </div>
    <div class='signin-block-separator'></div>
    <form id='form-connection' method='POST' action='signin_server.php'>
        <label for='connexion-formulaire-identifiant'>Pseudonyme :</label>
        <input type='text' name='identifiant' id='connexion-formulaire-identifiant' title='Entre 3 et 20 caractères autorisés, lettres et chiffres uniquement' pattern='^[A-Za-z0-9]{3,20}$' required>
        <label for='connexion-formulaire-motdepasse'>Mot de passe :</label>
        <input type='password' name='motdepasse' id='connexion-formulaire-motdepasse' title='Veuillez saisir au moins 5 caractères' pattern=".{5,}" required>
        <a class='motdepasseoublie' href='#'>J'ai oublié mon mot de passe.</a>
        <div class='connexion-formulaire-infosComplementaires'>
            <p class='connexion-formulaire-erreur'>
            <input type='checkbox' name='resterConnecter' id='connexion-formulaire-infosComplementaires-resterConnecte' name='resterConnecte'><label for='connexion-formulaire-infosComplementaires-resterConnecte'>Rester connecté</label>
        </div>
        <input type='submit' name='soumettre' value='Connexion' id='connexion-formulaire-soumettre'>
        <p class='connexion-formulaire-pasDeCompte'>Vous n'avez pas de compte ? <a href='#'>S'incrire maintenant.</a></p>
    </form>
</div>