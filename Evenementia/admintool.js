/******** AFFICHAGE MOD TOOL  *******/
var modtool = document.querySelector('.profil-block-admin-tool');
var userType = document.querySelector('.Sgroupe');

if(!document.querySelector('#monProfil') || (userType.textContent.toLowerCase() !== 'administrateur')){
    modtool.style.display = 'none';
}



/****** AJOUT CONTRIBUTEUR *****/

var creerContrib = document.querySelector('#createContributeur');

creerContrib.onsubmit = function(e){

    var pseudo = document.querySelector('.pseudoContrib');
    var nom = document.querySelector('.nomContrib');
    var prenom = document.querySelector('.prenomContrib');
    var email = document.querySelector('.emailContrib');
    var mdp = document.querySelector('.mdpContrib');
    var mdpc = document.querySelector('.mdpcContrib');

    var saisies = [pseudo, nom, prenom, email, mdp, mdpc];

    saisies.forEach(x => {
        if(x.value === ''){
            e.preventDefault();
            x.style.borderColor = 'red';
        }
    });

    if(mdp.value !== mdpc.value){
        e.preventDefault();
        mdp.style.borderColor = 'red';
        mdpc.style.borderColor = 'red';
    }

    
}