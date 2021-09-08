/*
Fichier : Test_GroupeC4.sql
Auteurs : 
Ingo Diab 21709489
Antoine Brahimi 21612658
Nom du groupe : C4
*/

USE Evenementia;

/*Les tests des fonctions se font a l'intérieur des triggers*/


/**********************************************/
/**************** TEST : ADMIN ****************/
/**********************************************/

/* Insertion d'un admin avec des attributs valides*/
INSERT INTO ADMINISTRATEUR VALUES ('admin', 'azerty', 'Ad', 'Min', 'Homme', 'admin.mail@live.fr');

/* On essaye d'update le mail de l'admin en supprimant le ".fr" ce qui provoque une erreur relevé par le trigger ADMIN_INPUT_UPDATE :*/
/*UPDATE ADMINISTRATEUR SET Email = 'admin.mail@live' WHERE ADMINISTRATEUR.Pseudo = 'admin';*/
/* /!\ La requête ci-dessus est mise en commentaire pour ne pas bloquer l'exécution des requêtes suivantes car elle renvoit une erreur /!\ */

/*On vérifie que l'erreur est sauvegardé dans la table LOGERROR*/
SELECT * FROM LOGERROR;




/*****************************************************/
/**************** TEST : CONTRIBUTEUR ****************/
/*****************************************************/

/* Insertion d'un contributeur avec des attributs valides CREE PAR L'ADMINISTRATEUR "ADMIN"*/
INSERT INTO CONTRIBUTEUR VALUES ('Contrib1', 'azerty', 'Cont', 'RibUn', 'Femme', 'contrib1@live.com', 'admin');

/* Insertion d'un contributeur avec des attributs valides NON CREE PAR UN ADMINISTRATEUR (créé via un formulaire sur le site)*/
INSERT INTO CONTRIBUTEUR VALUES ('Contrib2', 'azerty', 'Cont', 'RibDeux', 'Autre', 'contrib2@live.com', NULL);

/* On essaye d'update le prénom du Contrib1 en mettant un chiffre dans son prénom ce qui provoque une erreur relevé par le trigger CONTRIBUTEUR_INPUT_UPDATE*/
/*UPDATE CONTRIBUTEUR SET Prenom = 'Rib1' WHERE ADMINISTRATEUR.Pseudo = 'Contrib1';*/
/* /!\ La requête ci-dessus est mise en commentaire pour ne pas bloquer l'exécution des requêtes suivantes car elle renvoit une erreur /!\ */

/* On essaye d'update la provenance de Contrib1 en mettant admin dans l'attribut de PseudoA ce qui provoque une erreur relevé par le trigger CONTRIBUTEUR_INPUT_UPDATE*/
/*UPDATE CONTRIBUTEUR SET PseudoA = 'admin' WHERE ADMINISTRATEUR.Pseudo = 'Contrib2';*/
/* /!\ La requête ci-dessus est mise en commentaire pour ne pas bloquer l'exécution des requêtes suivantes car elle renvoit une erreur /!\ */

/*On vérifie que l'erreur est sauvegardé dans la table LOGERROR*/
SELECT * FROM LOGERROR;





/*************************************************/
/**************** TEST : VISITEUR ****************/
/*************************************************/

/* Insertion d'un visiteur avec des attributs valides*/
INSERT INTO VISITEUR VALUES ('Visiteur', 'azerty', 'Visit', 'Eur', 'Homme', 'visiteur@live.com');

/* On essaye d'update le genre du visiteur ce qui provoque une erreur car Animal n'est pas une valeur possible dans Genre*/
/*UPDATE VISITEUR SET Genre = 'Animal' WHERE VISITEUR.Pseudo = 'Visiteur';*/
/* /!\ La requête ci-dessus est mise en commentaire pour ne pas bloquer l'exécution des requêtes suivantes car elle renvoit une erreur /!\ */

/*On vérifie que l'erreur est sauvegardé dans la table LOGERROR*/
SELECT * FROM LOGERROR;

/*************************************************/
/******************* TEST : THEME ****************/
/*************************************************/

/* Insertion d'un thème créé par un administrateur */
INSERT INTO THEME VALUES ('Ceremonie', 'admin');

/* On essaye de changer le créateur du thème alors que c'est impossible */
/* UPDATE THEME SET PseudoA = 'admin2' WHERE THEME.Theme = 'Ceremonie'; */
/* /!\ La requête ci-dessus est mise en commentaire pour ne pas bloquer l'exécution des requêtes suivantes car elle renvoit une erreur /!\ */

/*On vérifie que l'erreur est sauvegardé dans la table LOGERROR*/
SELECT * FROM LOGERROR;

/*****************************************************/
/******************* TEST : EVENEMENT ****************/
/*****************************************************/

/* Insertion d'un évènement */
INSERT INTO EVENEMENT VALUES ('Nouvel An', '2019-12-31 00:00:00', 'Ceremonie', 'Nouvel an en plein air', '1', '200000', 'Contrib2', 'Montpellier promenade du peyrou'); 

/* On essaye de mettre une date expirée à un événement */
/* UPDATE EVENEMENT SET Date_Evenement = '2018-12-31 00:00:00' WHERE EVENEMENT.Nom = 'Nouvel An'; */
/* /!\ La requête ci-dessus est mise en commentaire pour ne pas bloquer l'exécution des requêtes suivantes car elle renvoit une erreur /!\ */

/*On vérifie que l'erreur est sauvegardé dans la table LOGERROR*/
SELECT * FROM LOGERROR;

/*****************************************************/
/******************* TEST : INSCRIRE *****************/
/*****************************************************/

/* Inscription d'un visiteur à un événement */
INSERT INTO INSCRIRE VALUES ('Visiteur', 'Nouvel An');

/**************************************************/
/******************* TEST : NOTER *****************/
/**************************************************/

/* Note (sur 5) du visiteur par rapport à un événement */
INSERT INTO NOTER VALUES ('Visiteur', 'Nouvel An', 3);

/* On essaye de mettre une note supérieur strictement à 5 */
/*UPDATE NOTER SET Note = 9 WHERE NOTER.PseudoV = 'Visiteur';*/
/* /!\ La requête ci-dessus est mise en commentaire pour ne pas bloquer l'exécution des requêtes suivantes car elle renvoit une erreur /!\ */


