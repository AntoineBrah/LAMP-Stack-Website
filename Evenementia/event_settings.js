/* AFFICHAGE de la MAP : DÉBUT */
var mapid = document.querySelectorAll('#mapid');

var titreEvenement = document.querySelectorAll('.evenement-header-titre');

var localisation = document.querySelectorAll('#Localisation');

var i = 0;

localisation.forEach(x => {

    var coord = x.getAttribute('class');

    coord = coord.split(',');
    lat = coord[0];
    long = coord[1];


    var mymap = L.map(mapid[i]).setView([lat, long], 13);

    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoiYW50b2JyYWgiLCJhIjoiY2s0NzZwaHRmMHBnMTNmbGZ0ZDkzd3pxYyJ9.W9mgsMjA9U1CBLOrz26SGQ', {
        attribution: lat + ',' + long,
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        accessToken: 'your.mapbox.access.token'
    }).addTo(mymap);

    var marker = L.marker([lat, long]).addTo(mymap);
    marker.bindPopup("<b>" + titreEvenement[i].textContent + "</b><br>Tu participes ?").openPopup();


    i++;
});
/* AFFICHAGE de la MAP : FIN */


/* DISPLAY NONE/BLOCK D'EVENEMENT-FOOTER : DÉBUT */

var evenement = document.querySelectorAll('.evenement');

evenement.forEach(x => {

    x.children[2].style.display = 'none';
    x.children[2].setAttribute('class', 'hide');

    x.children[0].addEventListener('click', function(){

        if(x.children[2].getAttribute('class') === 'hide'){
            
            x.children[2].style.display = 'flex';
            x.children[2].setAttribute('class', 'visible');
        }
        else{
            x.children[2].style.display = 'none';
            x.children[2].setAttribute('class', 'hide');
        }
    });
});

/* DISPLAY NONE/BLOCK D'EVENEMENT-FOOTER : FIN */

/* EVENT ADDITIONNAL-CONTENT Inscription événement : DÉBUT */

//Dans un premier temps, on va gérer le fait qu'un visiteur
//puisse s'inscrire à un événement si la date n'est pas passé
//et s'il est inscrit et que la date est passé alors il peut noter l'événement

function disableAllInputRate(msg){
    var inscriptionEvenement = document.querySelectorAll('#inscrire');
    var noteEvenement = document.querySelectorAll('#note');
    var envoyerNote = document.querySelectorAll('#send-note');


    for(var i=0; i<inscriptionEvenement.length; i++){
        inscriptionEvenement[i].disabled = true;
        inscriptionEvenement[i].setAttribute('title', msg);
        noteEvenement[i].disabled = true;
        noteEvenement[i].setAttribute('title', 'Il faut être inscrit à l\'événement pour pouvoir le noter');
        envoyerNote[i].disabled = true;
        envoyerNote[i].setAttribute('title', 'Il faut être inscrit à l\'événement pour pouvoir le noter');
    }
}

//Si ça n'existe pas alors cela signifie que l'utilisateur est un Guest
if(!document.querySelector('#monProfil') ){
    disableAllInputRate('Vous devez être connecté pour vous inscrire à cet événement');
}
else{
    //Si on rentre dans le else alors ça signifie que l'utilisateur est connecté

    //On suppose que ce n'est pas un visiteur
    if(document.querySelector('.Sgroupe').textContent.toLowerCase() != 'visiteur'){
        disableAllInputRate('Vous n\'avez pas les droit pour rejoindre cet événement');
    }
    else{
        //Ici l'utilisateur est forcément connecté et est dans le groupe Visiteur

        //On fait en sorte que si l'événement est passé alors on ne puisse pas cliquer
        var statut = document.querySelectorAll('#statutEvenement');
        var boutonInscrire = document.querySelectorAll('#inscrire');
        var saisieNote = document.querySelectorAll('#note');
        var sendNote = document.querySelectorAll('#send-note');

                    
        var requeteGetxInscription = new XMLHttpRequest();
        requeteGetxInscription.open('POST', 'getInscription.php', true);
        requeteGetxInscription.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        var pseudonyme = document.querySelector('.Spseudo').textContent;

        requeteGetxInscription.send("PseudoV=" + pseudonyme);

        requeteGetxInscription.onload = function(){
            if(requeteGetxInscription.readyState === 4 && requeteGetxInscription.status === 200){ //readyState = 4 signifie que la requete a fini et la réponse est prête; status = 200 signifie "ok" et donc pas d'erreur 403, 404...
                var reponseRequete = requeteGetxInscription.response;;
                var myArray = reponseRequete.split(',');
                if(reponseRequete){

                    saisieNote.forEach(x => {

                        j = Array.from(saisieNote).indexOf(x);
                        var nomEvenement = x.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.children[0].children[0].textContent; // On récupére le nom de l'événement

                        if(myArray.includes(nomEvenement)){
                            boutonInscrire[j].disabled = true;
                            x.disabled = false;
                            sendNote[j].disabled = false;
                        }
                        else{
                            boutonInscrire[j].disabled = false;
                            x.disabled = true;
                            sendNote[j].disabled = true;

                        }
                    });
                    
                }
                else{
                    
                }
            }
            else{
                console.log("Erreur : la requête a échoué");
            }
        }





        for(var i=0; i<statut.length; i++){
            //Cas ou l'événement est terminé
            if(statut[i].textContent === 'Terminé'){
                boutonInscrire[i].disabled = true;
                boutonInscrire[i].setAttribute('title', 'L\'événement est terminé');
                saisieNote[i].disabled = true;
                saisieNote[i].setAttribute('title', 'Il faut être inscrit à l\'événement pour pouvoir le noter');
                sendNote[i].disabled = true;
                sendNote[i].setAttribute('title', 'Il faut être inscrit à l\'événement pour pouvoir le noter');
            }
            else{
                
                saisieNote[i].disabled = true;
                saisieNote[i].setAttribute('title', 'Il faut être inscrit à l\'événement pour pouvoir le noter');
                sendNote[i].disabled = true;
                sendNote[i].setAttribute('title', 'Il faut être inscrit à l\'événement pour pouvoir le noter');
                

                boutonInscrire.forEach(x => {

                    x.addEventListener('click', function(e){
                        e.stopImmediatePropagation(); //Résout les problèmes de bouillonnement/capture
                        e.target.disabled = true;
                        e.target.setAttribute('title', 'Vous êtes inscrit');
                        e.target.children[0].style.display = 'block';

                        j = Array.from(boutonInscrire).indexOf(x);

                        saisieNote[j].disabled = false;
                        saisieNote[j].setAttribute('title', '');
                        sendNote[j].disabled = false;
                        sendNote[j].setAttribute('title', '');

                        // Début AJAX
                        var requeteInscription = new XMLHttpRequest();
                        requeteInscription.open('POST', 'inscription.php', true);
                        requeteInscription.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        
                        var pseudonyme = document.querySelector('.Spseudo').textContent;
                        var nomEvenement = x.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.children[0].children[0].textContent; // On récupére le nom de l'événement

                        // Insert to du Visiteur et de l'événement auquel il s'inscrit
                        requeteInscription.send("PseudoV=" + pseudonyme + "&NomE=" + nomEvenement);

                    });
                });
 
            }
                
        }     
    }
}

/* EVENT ADDITIONNAL-CONTENT Inscription événement : FIN */

/*****************************************************************/

/* EVENT ADDITIONNAL-CONTENT Inscription événement (maj auto): DÉBUT */

//En suivant la logique du modèle AJAX on désactiver tous les boutons s'inscrire pour
//les visiteurs qui sont déjà inscrit à un événement

if(document.querySelector('#monProfil') && (document.querySelector('.Sgroupe').textContent.toLowerCase() == 'visiteur')){


    var requeteGetInscription = new XMLHttpRequest();
    requeteGetInscription.open('POST', 'getInscription.php', true);
    requeteGetInscription.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    var pseudonyme = document.querySelector('.Spseudo').textContent;

    requeteGetInscription.send("PseudoV=" + pseudonyme);

    requeteGetInscription.onload = function(){
        if(requeteGetInscription.readyState === 4 && requeteGetInscription.status === 200){ //readyState = 4 signifie que la requete a fini et la réponse est prête; status = 200 signifie "ok" et donc pas d'erreur 403, 404...
            var reponseRequete = requeteGetInscription.response;
            if(reponseRequete){
                disableSuscribeEvent(reponseRequete);
            }
            else{
                console.log('chaine vide');
            }
        }
        else{
            console.log("Erreur : la requête a échoué");
        }
    }

    function disableSuscribeEvent(myString){

        var boutonInscrire = document.querySelectorAll('#inscrire');
        var myArray = myString.split(',');

        boutonInscrire.forEach(x => {

            var nomEvenement = x.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.children[0].children[0].textContent; // On récupére le nom de l'événement
            if(myArray.includes(nomEvenement)){
                x.disabled = true;
                x.children[0].style.display = 'block';
            }

        });
    }
}

/* EVENT ADDITIONNAL-CONTENT Inscription événement (maj auto): FIN */

/*******************************************************************/

/* EVENT ADDITIONNAL-CONTENT Inscription événement (NOTE): DÉBUT */

if(document.querySelector('#monProfil') && (document.querySelector('.Sgroupe').textContent.toLowerCase() == 'visiteur')){


    var requeteGetNote = new XMLHttpRequest();
    requeteGetNote.open('POST', 'getNote.php', true);
    requeteGetNote.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    var pseudonyme = document.querySelector('.Spseudo').textContent;

    requeteGetNote.send("PseudoV=" + pseudonyme);

    requeteGetNote.onload = function(){
        if(requeteGetNote.readyState === 4 && requeteGetNote.status === 200){ //readyState = 4 signifie que la requete a fini et la réponse est prête; status = 200 signifie "ok" et donc pas d'erreur 403, 404...
            var reponseRequete = requeteGetNote.response;
            if(reponseRequete){
                disableNoteEvent(reponseRequete);
            }
            else{
                console.log('chaine vide');
            }
        }
        else{
            console.log("Erreur : la requête a échoué");
        }
    }

    function disableNoteEvent(myString){

        var myNote = document.querySelectorAll('#note');
        var mySend = document.querySelectorAll('#send-note');

        var myArray = myString.split(',');

        myNote.forEach(x => {

            var nomEvenement = x.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.children[0].children[0].textContent; // On récupére le nom de l'événement

            j = Array.from(myNote).indexOf(x);

            if(myArray.includes(nomEvenement)){
                x.disabled = true;
                x.setAttribute('class', 'Vous avez déjà saisi une note');
                mySend[j].disabled = true;
                mySend[j].setAttribute('class', 'Vous avez déjà saisi une note');
            }

        });
    }
}

/* EVENT ADDITIONNAL-CONTENT Inscription événement (NOTE): FIN */

/* EVENT ADDITIONNAL-CONTENT Inscription événement (ADD EVENT CLIC NOTE): DEBUT */

if(document.querySelector('#monProfil') && (document.querySelector('.Sgroupe').textContent.toLowerCase() == 'visiteur')){

    var myNote = document.querySelectorAll('#note');
    var mySend = document.querySelectorAll('#send-note');

    mySend.forEach(x => {

        x.addEventListener('click', function(){

            j = Array.from(mySend).indexOf(x);

            strToFloat = parseFloat(myNote[j].value);

            if(isNaN(strToFloat) || strToFloat < 0 || strToFloat > 10){
                myNote[j].style.borderColor = 'red';
            }
            else{
                myNote[j].style.borderColor = '#7CFC00';
                myNote[j].disabled = true;
                x.disabled = true;

                var moyenneNote = document.querySelectorAll('#noteGlobale');

                var requeteSetNote = new XMLHttpRequest();
                requeteSetNote.open('POST', 'setNote.php', true);
                requeteSetNote.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                var pseudonyme = document.querySelector('.Spseudo').textContent;
                var nomEvenement = x.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.children[0].children[0].textContent; // On récupére le nom de l'événement
                var noteEvenement = strToFloat;

                requeteSetNote.send("PseudoV=" + pseudonyme + "&nomE=" + nomEvenement + "&note=" + noteEvenement);

                requeteSetNote.onload = function(){
                    if(requeteSetNote.readyState === 4 && requeteSetNote.status === 200){ //readyState = 4 signifie que la requete a fini et la réponse est prête; status = 200 signifie "ok" et donc pas d'erreur 403, 404...
                        var reponseRequete = requeteSetNote.response;
                        if(reponseRequete){
                            var myfloat = parseFloat(reponseRequete).toFixed(1);
                            moyenneNote[j].textContent = myfloat;
                        }
                        else{
                            console.log('chaine vide');
                        }
                    }
                    else{
                        console.log("Erreur : la requête a échoué");
                    }
                }
            }
        });
    });
}

/* EVENT ADDITIONNAL-CONTENT Inscription événement (ADD EVENT CLIC NOTE): FIN */

/* SYSTEME DE COMMENTAIRE : DEBUT */

var sendCommentaire = document.querySelectorAll('#sendCommentaireVisiteur');
var commentaireVisiteur = document.querySelectorAll('#commentaireVisiteur');
var commentaires = document.querySelectorAll('.commentaires');

sendCommentaire.forEach(x => {

    j = Array.from(sendCommentaire).indexOf(x);

        x.addEventListener('click', function(){

            console.log(commentaireVisiteur[j].value);

            if(commentaireVisiteur[j].value !== ''){

                //alert('Vous venez de poster un commentaire !');

                var requeteCommentaire = new XMLHttpRequest();
                requeteCommentaire.open('POST', 'setCommentaire.php', true);
                requeteCommentaire.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                var pseudonyme = document.querySelector('.Spseudo').textContent;
                var nomEvenement = x.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.children[0].children[0].textContent; // On récupére le nom de l'événement
                var commentaire = commentaireVisiteur[j].value;

                commentaireVisiteur[j].value = '';

                requeteCommentaire.send("PseudoV=" + pseudonyme + "&nomE=" + nomEvenement + "&commentaire=" + commentaire);

                var para = document.createElement('p');
                para.textContent = pseudonyme + ' : ' + commentaire;

                commentaires[j].prepend(para);
            }
            else{
                console.log('Requete non envoyé, le commentaire est vide');
            }
        });
});

/* SYSTEME DE COMMENTAIRE : FIN */

/* SYSTEME DE COMMENTAIRE DESACTIVER SI NON CONNECTE OU NON VISITEUR : DEBUT */

if(!document.querySelector('#monProfil') || (document.querySelector('.Sgroupe').textContent.toLowerCase() != 'visiteur')){

    var sendCommentaire = document.querySelectorAll('#sendCommentaireVisiteur');
    var commentaireVisiteur = document.querySelectorAll('#commentaireVisiteur');

    sendCommentaire.forEach(x => {
        j = Array.from(sendCommentaire).indexOf(x);
        x.disabled = true;
        commentaireVisiteur[j].disabled = true;
        commentaireVisiteur[j].placeholder = 'Seul les Visiteurs peuvent profiter du chat instantané...';
    });
}


/* SYSTEME DE COMMENTAIRE DESACTIVER SI NON CONNECTE OU NON VISITEUR : FIN */










  