/* Affichage Bloc connexion/inscription : Début */


var body = document.querySelector('body');

if(document.querySelector('.signin-button')){
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
    });

    signup_button.addEventListener('click', function(){
        disableAll();
        enableFilter();
        filter.style.display = 'block';
        signup_block.style.display = 'block';
        window.scroll(0,0);
    });



    filter.addEventListener('click', function(){
        disableFilter();
        signin_block.style.display = 'none';
        signup_block.style.display = 'none';
    });
    
}

/* Affichage Bloc connexion/inscription : Fin */


/* Création des ancres : DÉBUT */

var logo = document.querySelector('.logo');

logo.addEventListener('click', function(){
    window.location.href='index.php';
});

/* Création des ancres : FIN */

/* Affichage du profil : DÉBUT */

var profil = document.querySelector('#profil-block');

if(profil){
    
    var body = document.querySelector('body');
    var monProfil = document.querySelector('#monProfil');
    var filter = document.querySelector('#filter');

    function disableAll(){
        filter.style.display = 'none';
        profil.style.display = 'none';
    }

    function enableFilter(){
        filter.style.display = 'block';
        body.style.overflow = 'hidden';
    }

    function disableFilter(){
        filter.style.display = 'none';
        body.style.overflow = 'visible';
    }

    monProfil.addEventListener('click', function(){
        disableAll();
        enableFilter();
        profil.style.display = 'block';
        window.scroll(0,0);
    });

    filter.addEventListener('click', function(){
        disableFilter();
        profil.style.display = 'none';
    });
}
/* Affichage du profil : FIN */