
/* Autocomplétion saisie département : DÉBUT */
var saisieDepartementRecherche = document.querySelector('#saisieDepartementRecherche');
//var saisieDepartementCreation = document.querySelector('#saisieDepartementCreation');
var departements = [];

var requeteChemin = 'departement.json';
var requete = new XMLHttpRequest();
requete.open('GET', requeteChemin);
requete.responseType = 'json';
requete.send();

requete.onload = function(){
    if(requete.readyState === 4 && requete.status === 200){ //readyState = 4 signifie que la requete a fini et la réponse est prête; status = 200 signifie "ok" et donc pas d'erreur 403, 404...
        var reponseRequete = requete.response;

        reponseRequete.forEach(x => { //On met tous les départements dans notre tableau départements[]
            departements.push(x.departmentName);
        })

        autocomplete(saisieDepartementRecherche, departements);
        //autocomplete(saisieDepartementCreation, departements);

        /* Oblige l'utilisateur à saisir un département donné : DÉBUT */

        // Fonction servant à vérifier si la saisie de l'utilisateur correspond bien à un département de notre tableau
        function isEqualToOneElement(saisie){
          var isEqual = false;
          departements.forEach(x => {
            if(x === saisie.value){
              isEqual = true;
            }
          });
          return isEqual;
        }

        // Ici on vérifie la saisie de l'utilisateur
        saisieDepartementRecherche.addEventListener('input', function(){
          if(!isEqualToOneElement(saisieDepartementRecherche))
            saisieDepartementRecherche.style.borderColor = '#fd2727';
          else
            saisieDepartementRecherche.style.borderColor = '#00FF00';
        })

        /*

        saisieDepartementCreation.addEventListener('input', function(){
          if(!isEqualToOneElement(saisieDepartementCreation))
            saisieDepartementCreation.style.borderColor = '#fd2727';
          else
            saisieDepartementCreation.style.borderColor = '#00FF00';
        })

        */
        /* Oblige l'utilisateur à saisir un département donné : FIN */
        
        
    }
    else{
        console.log("Erreur lors de la réception de la requête.");
    }
}

function autocomplete(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
        var a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) { return false;}
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
            /*check if the item starts with the same letters as the text field value:*/
            if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
            /*create a DIV element for each matching element:*/
            b = document.createElement("DIV");
            /*make the matching letters bold:*/
            b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
            b.innerHTML += arr[i].substr(val.length);
            /*insert a input field that will hold the current array item's value:*/
            b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
            /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function(e) {
                /*insert the value for the autocomplete text field:*/
                inp.value = this.getElementsByTagName("input")[0].value;  // Lorsque l'utilisateur clique sur un nom de département il est automatiquement inséré dans la zone de saisie

                saisieDepartementRecherche.style.borderColor = '#00FF00'; // Lorsque l'utilisateur clique sur un département la saisie est alors correcte on met donc un contour vert pour lui montrer
                saisieDepartementCreation.style.borderColor = '#00FF00';

                /*close the list of autocompleted values,
                (or any other open lists of autocompleted values:*/
                closeAllLists();
                });
                a.appendChild(b);
            }
        }
    });

    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
          /*If the arrow DOWN key is pressed,
          increase the currentFocus variable:*/
          currentFocus++;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 38) { //up
          /*If the arrow UP key is pressed,
          decrease the currentFocus variable:*/
          currentFocus--;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 13) {
          /*If the ENTER key is pressed, prevent the form from being submitted,*/
          e.preventDefault();
          if (currentFocus > -1) {
            /*and simulate a click on the "active" item:*/
            if (x) x[currentFocus].click();
          }
        }
    });

    function addActive(x) {
      /*a function to classify an item as "active":*/
      if (!x) return false;
      /*start by removing the "active" class on all items:*/
      removeActive(x);
      if (currentFocus >= x.length) currentFocus = 0;
      if (currentFocus < 0) currentFocus = (x.length - 1);
      /*add class "autocomplete-active":*/
      x[currentFocus].classList.add("autocomplete-active");
    }

    function removeActive(x) {
      /*a function to remove the "active" class from all autocomplete items:*/
      for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("autocomplete-active");
      }
    }

    function closeAllLists(elmnt) {
      /*close all autocomplete lists in the document,
      except the one passed as an argument:*/
      var x = document.getElementsByClassName("autocomplete-items");
      for (var i = 0; i < x.length; i++) {
        if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }

  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
} 
/* Auto-complétion saisie département : FIN */

/* --------------------------------------------------------------- */

/* Événement de clique sur les catégories : DÉBUT */
var categorie = document.querySelectorAll('#search-event-category input');

var hiddenInput = document.querySelector('#choixCategorie');

//Permet d'enlever les accents d'une chaine de caractere
String.prototype.sansAccent = function(){
  var accent = [
      /[\300-\306]/g, /[\340-\346]/g, // A, a
      /[\310-\313]/g, /[\350-\353]/g, // E, e
      /[\314-\317]/g, /[\354-\357]/g, // I, i
      /[\322-\330]/g, /[\362-\370]/g, // O, o
      /[\331-\334]/g, /[\371-\374]/g, // U, u
      /[\321]/g, /[\361]/g, // N, n
      /[\307]/g, /[\347]/g, // C, c
  ];
  var noaccent = ['A','a','E','e','I','i','O','o','U','u','N','n','C','c'];
   
  var str = this;
  for(var i = 0; i < accent.length; i++){
      str = str.replace(accent[i], noaccent[i]);
  }
   
  return str;
}

function removeAllOpacity(){
  categorie.forEach(x => {
    x.style.opacity = 1;
    x.setAttribute('name', '');
    hiddenInput.setAttribute('value', '');
    hiddenInput.setAttribute('name', 'categorie');
  });
}

categorie.forEach(x => {
  x.addEventListener('click', function(){
    if(x.getAttribute('name') === ''){
      removeAllOpacity();
      x.style.opacity = 0.5;
      x.setAttribute('name', (x.getAttribute('value').sansAccent()).toLowerCase());
      hiddenInput.setAttribute('value', x.getAttribute('name'));
    }
    else{
      x.style.opacity = 1;
      x.setAttribute('name', '');
      hiddenInput.setAttribute('value', '');
    }
  });

  x.addEventListener('mouseover', function(){
    if(x.getAttribute('name') === '')
      x.style.opacity = 0.7;
  })

  x.addEventListener('mouseout', function(){
    if(x.getAttribute('name') === '')
      x.style.opacity = 1;
  })
});
/* Événement de clique sur les catégories : FIN */

/* --------------------------------------------------------------- */

/* Passer l'input date à la date courante automatiquement : DEBUT */

var date = document.querySelector('#dateEvenement');

//Ajout du support d'heure par zone géographique
Date.prototype.toDateInputValue = (function() {
  var local = new Date(this);
  local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
  return local.toJSON().slice(0,10);
});

//date.value = new Date().toDateInputValue();

/* Passer l'input date à la date courante automatiquement : FIN */

/* --------------------------------------------------------------- */

/* Réinitialisez les paramètres de tous les inputs de notre section catégorie : DÉBUT */

var effectifMin = document.querySelector('#effectifMin');
var effectifMax = document.querySelector('#effectifMax');

var formulaireRecherche = document.querySelector('#search-event');

function reinitialiseAll(){
  saisieDepartement.value = '';
  saisieDepartement.style.borderColor = '#cccccc';
  date.value = '';
  effectifMin.value = '';
  effectifMax.value = '';
  removeAllOpacity();
}

//formulaireRecherche.addEventListener('submit', reinitialiseAll);

/* Réinitialisez les paramètres de tous les inputs de notre section catégorie : FIN */

/* --------------------------------------------------------------- */

/* Bouton d'insertion d'image : DÉBUT */

var upload = document.querySelector('#fileToUpload');
var alternative = document.querySelector('#alternativeUpload');

alternative.addEventListener('click', function(){
  upload.click();
});

/* Bouton d'insertion d'image : FIN */

/* --------------------------------------------------------------- */