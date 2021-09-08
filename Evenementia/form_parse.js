/* Ici on vérifie que les données entrées dans tous les types de formulaire présents sur le site sont correctes sinon on affiche un message d'erreur dans les formulaires respectifs */

/* Analyse des données du formulaire d'inscription : DÉBUT */

function analyseInscriptionFormulaire(){

    var form = document.querySelector('#inscription-formulaire-contenu');

    form.onsubmit = function(e){
        var pseudonyme = document.querySelector('#inscription-formulaire-contenu-pseudonyme');
        var nom = document.querySelector('#inscription-formulaire-contenu-nom');
        var prenom = document.querySelector('#inscription-formulaire-contenu-prenom');
        var typeUtilisateur = document.querySelector('#inscription-formulaire-contenu-typeUtilisateur');
        var genre = document.querySelector('#inscription-formulaire-contenu-nom-genre');
        var email = document.querySelector('#inscription-formulaire-contenu-email');
        var mdp = document.querySelector('#inscription-formulaire-contenu-motdepasse');
        var mdpConfirm = document.querySelector('#inscription-formulaire-contenu-cmotdepasse'); // Confirmation du mot de passe

        var saisies = [pseudonyme, nom, prenom, typeUtilisateur, genre, email, mdp, mdpConfirm];

        var erreur = document.querySelector('.inscription-formulaire-erreur');

        saisies.forEach(x => {
            if(x.value === ''){
                e.preventDefault();
                x.style.borderColor = 'red';
                erreur.textContent = 'Erreur : veuillez vérifier les saisies au sein du ou des encadrés rouges.';
                erreur.style.color = 'red';
                erreur.style.fontWeight = 'bold';
            }
        });

        if(mdp.value !== mdpConfirm.value){
            e.preventDefault();
            erreur.textContent = 'Erreur : veuillez vérifier les saisies au sein du ou des encadrés rouges. Les mots de passes ne correspondent pas.';
            mdp.style.borderColor = 'red';
            mdpConfirm.style.borderColor = 'red';
            erreur.style.color = 'red';
            erreur.style.fontWeight = 'bold';
        }
    }
}


analyseInscriptionFormulaire();


/* Analyse des données du formulaire d'inscription : FIN */

/* ----------------------------------------------------- */

/* Analyse des données du formulaire de connexion : DÉBUT */

function analyseConnexionFormulaire(){

    var form = document.querySelector('#form-connection');

    form.onsubmit = function(e){
        var pseudonyme = document.querySelector('#connexion-formulaire-identifiant');
        var mdp = document.querySelector('#connexion-formulaire-motdepasse');

        var erreur = document.querySelector('.connexion-formulaire-erreur');

        /*
        e.preventDefault();
        erreur.textContent = 'Erreur';
        */
    }
}

analyseConnexionFormulaire();

/* Analyse des données du formulaire de connexion : FIN */

/* ----------------------------------------------------- */

/* Analyse des données du formulaire de recherche d'événement : DÉBUT */

function analyseRechercheEvenement(){

    var form = document.querySelector('#search-event');

    form.onsubmit = function(e){
        var departement = document.querySelector('#saisieDepartementRecherche');
        var date = document.querySelector('#dateEvenement');
        var effectifMin = document.querySelector('#effectifMin');
        var effectifMax = document.querySelector('#effectifMax');

        var evenementCategorie = document.querySelectorAll('#search-event-category input');

        /*
        e.preventDefault();

        departement.style.borderColor = 'red';
        */
    }
}

analyseRechercheEvenement();


/* Analyse des données du formulaire de recherche d'événement : FIN */

/* ----------------------------------------------------- */

/* Analyse des données du formulaire de création d'événement : DÉBUT */

function analyseCreationEvenement(){

    var form = document.querySelector('#create-event');

    form.onsubmit = function(e){

        var nomEvenement = document.querySelector('#saisieNomEvenement');
        var categorie =  document.querySelector('#create-event-category');
        var date = document.querySelector('#dateEvenementCreation');
        var effectifMin = document.querySelector('#effectifMinCreation');
        var effectifMax = document.querySelector('#effectifMaxCreation');
        var photoEvenement = document.querySelector('#fileToUpload');
        var evenementDescription = document.querySelector('#event-description');
        var latitudeEvenement = document.querySelector('#latitudeEvenement');
        var latitudeEvenement = document.querySelector('#longitudeEvenement');

        var erreur = document.querySelector('#main-create-event-result');

        //Ajout du support d'heure par zone géographique
        Date.prototype.toDateInputValue = (function() {
            var local = new Date(this);
            local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
            return local.toJSON().slice(0,10);
        });

        if(date.value < new Date().toDateInputValue()){
            e.preventDefault();
            date.style.borderColor = 'red';
            erreur.textContent = 'La date saisie est déjà passée';
            erreur.style.color = 'red';
            erreur.style.fontWeight = 'bold';
        }
        else if(Number(effectifMin.value) >= Number(effectifMax.value)){
            e.preventDefault();
            effectifMin.style.borderColor = 'red';
            effectifMax.style.borderColor = 'red';
            erreur.textContent = "L'effectif minimum doit être strictement inférieur à l'effectif maximum";
            erreur.style.color = 'red';
            erreur.style.fontWeight = 'bold';
        }
        else
            erreur.textContent = 'Événement créé !';
            erreur.style.color = 'green';
        
        
    }


}

analyseCreationEvenement();

/* Analyse des données du formulaire de création d'événement : FIN */