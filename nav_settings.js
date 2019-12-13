/* Affichage Bloc connexion/inscription : Début */

var body = document.querySelector('body');
var signin_button = document.querySelector('.signin-button');
var signup_button = document.querySelector('.signup-button');

var signin_block = document.querySelector('#signin-block');
var signup_block = document.querySelector('#signup-block');

var filter = document.querySelector('#filter');

disableAll();

function disableAll(){
    filter.style.display = 'none';
    signin_block.style.display = 'none';
    signup_block.style.display = 'none';
}


function enableFilter(){
    filter.style.display = 'block';
    body.style.overflow = 'hidden';
}

function disableFilter(){
    filter.style.display = 'none';
    body.style.overflow = 'visible';
}



signin_button.addEventListener('click', function(){
    disableAll();
    enableFilter();
    signin_block.style.display = 'block';
    window.scroll(0,0);
})

signup_button.addEventListener('click', function(){
    disableAll();
    enableFilter();
    filter.style.display = 'block';
    signup_block.style.display = 'block';
    window.scroll(0,0);
})



filter.addEventListener('click', function(){
    disableFilter();
    signin_block.style.display = 'none';
    signup_block.style.display = 'none';
})

/* Affichage Bloc connexion/inscription : Fin */


/* Création des ancres : DÉBUT */

var logo = document.querySelector('.logo');

logo.addEventListener('click', function(){
    document.location.reload(true);
});

/* Création des ancres : FIN */