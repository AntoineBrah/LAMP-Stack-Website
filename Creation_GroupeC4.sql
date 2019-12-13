/*
Fichier : Creation_GroupeC4.sql
Auteurs : 
Ingo Diab 21709489
Antoine Brahimi 21612658
Nom du groupe : C4
*/

/*
A METTRE OBLIGATOIREMENT AU DEBUT 
- SUPPRESSION DE LA BASE DE DONNEES
- CREATION DE LA BASE DE DONNEES
- SUPPRESSIONS DE TOUTES LES BASES CREEES POUR LE PROJET
*/





/*************************************************************************************************************************************************************************************************************************/
/*************************************************************************************************************************************************************************************************************************/
/* PRÉAMBULE : CETTE BASE DE DONNÉE EST DESTINÉE AU PROJET WEB DE L'UE : HLIN510, ELLE DIFFÈRE QUELQUE PEU DE LA BDD DE L'UE HLIN511 */
/*************************************************************************************************************************************************************************************************************************/
/* AJOUT DE LA TABLE 'MEMBRE' POUR VERIFIER L'UNICITÉ DES PSEUDOS */
/* AJOUT DE L'ATTRIBUT 'GROUPE' POUR SAVOIR POUR UN MEMBRE DONNÉ, SON GROUPE */
/*************************************************************************************************************************************************************************************************************************/
/* /!\ IL NE FAUT PAS AJOUTER OU MODIFIER DE MEMBRE DEPUIS LA BDD SINON RISQUE D'AVOIR UN MEME PSEUDO DANS PLUSIEURS TABLES => PROBLEMES DE CONNEXION /!\ */
/*************************************************************************************************************************************************************************************************************************/
/* POUR PALLIER ÇA IL FAUT AJOUTER DES 'TRIGGERS' DANS LES TABLES CONTRIB/VISITEUR/ADMIN AFIN DE S'ASSURER QUE 'INSERT' OU 'UPDATE' DANS UNE DE CES TABLES VÉRIFIE QUE LE PSEUDO NE FAIT PAS PARTI DE LA LISTE DES PSEUDOS 
DE LA TABLE MEMBRE */
/*************************************************************************************************************************************************************************************************************************/
/*************************************************************************************************************************************************************************************************************************/






/***************************************************/
/********************** BASE ***********************/
/***************************************************/

DROP DATABASE IF EXISTS Evenementia;
CREATE DATABASE Evenementia;
USE Evenementia;


/***************************************************/
/********************* TABLES **********************/
/***************************************************/

DROP TABLE  IF EXISTS ADMINISTRATEUR; 
DROP TABLE  IF EXISTS CONTRIBUTEUR; 
DROP TABLE  IF EXISTS VISITEUR;
DROP TABLE  IF EXISTS MEMBRE;
DROP TABLE IF EXISTS THEME;
DROP TABLE IF EXISTS EVENEMENT;
DROP TABLE IF EXISTS INSCRIRE;
DROP TABLE IF EXISTS NOTER;
DROP TABLE IF EXISTS LOGERROR;

CREATE TABLE ADMINISTRATEUR(
    Pseudo VARCHAR(20),
    Mdp VARCHAR(128),
    Nom VARCHAR(20),
    Prenom VARCHAR(20),
    Genre CHAR(5),
    Email VARCHAR(50),
    CONSTRAINT PK_ADMINISTRATEUR PRIMARY KEY (Pseudo),
    CONSTRAINT MDP_NOTNULL CHECK (Mdp IS NOT NULL),
    CONSTRAINT NOM_NOTNULL CHECK (Nom IS NOT NULL),
    CONSTRAINT PRENOM_NOTNULL CHECK (Prenom IS NOT NULL),
    CONSTRAINT GENRE_NOTNULL CHECK (Genre IS NOT NULL),
    CONSTRAINT EMAIL_NOTNULL CHECK (Email IS NOT NULL),
    CONSTRAINT CK_ADMINISTRATEUR_GENRE CHECK (Genre IN ('Homme', 'Femme', 'Autre'))
);

CREATE TABLE CONTRIBUTEUR(
    Pseudo VARCHAR(20),
    Mdp VARCHAR(128),
    Nom VARCHAR(20),
    Prenom VARCHAR(20),
    Genre CHAR(5),
    Email VARCHAR(50),
    PseudoA VARCHAR(20),
    CONSTRAINT PK_CONTRIBUTEUR PRIMARY KEY (Pseudo),
    CONSTRAINT MDP_NOTNULL CHECK (Mdp IS NOT NULL),
    CONSTRAINT NOM_NOTNULL CHECK (Nom IS NOT NULL),
    CONSTRAINT PRENOM_NOTNULL CHECK (Prenom IS NOT NULL),
    CONSTRAINT GENRE_NOTNULL CHECK (Genre IS NOT NULL),
    CONSTRAINT EMAIL_NOTNULL CHECK (Email IS NOT NULL),
    CONSTRAINT CK_CONTRIBUTEUR_GENRE CHECK (Genre IN ('Homme', 'Femme', 'Autre')),
    CONSTRAINT FK_CONTRIBUTEUR_ADMINISTRATEUR FOREIGN KEY (PseudoA) REFERENCES ADMINISTRATEUR(Pseudo)
);

CREATE TABLE VISITEUR(
    Pseudo VARCHAR(20),
    Mdp VARCHAR(128),
    Nom VARCHAR(20),
    Prenom VARCHAR(20),
    Genre CHAR(5),
    Email VARCHAR(50),
    CONSTRAINT PK_VISITEUR PRIMARY KEY (Pseudo),
    CONSTRAINT MDP_NOTNULL CHECK (Mdp IS NOT NULL),
    CONSTRAINT NOM_NOTNULL CHECK (Nom IS NOT NULL),
    CONSTRAINT PRENOM_NOTNULL CHECK (Prenom IS NOT NULL),
    CONSTRAINT GENRE_NOTNULL CHECK (Genre IS NOT NULL),
    CONSTRAINT EMAIL_NOTNULL CHECK (Email IS NOT NULL),
    CONSTRAINT CK_VISITEUR_GENRE CHECK (Genre IN ('Homme', 'Femme', 'Autre'))
);

CREATE TABLE MEMBRE(
    Pseudo VARCHAR(20),
    Groupe VARCHAR(14),
    CONSTRAINT PK_PSEUDO PRIMARY KEY (Pseudo),
    CONSTRAINT CK_GROUPE_DEFINED CHECK (Groupe IS NOT NULL),
    CONSTRAINT CK_GROUPE_TYPE CHECK (Groupe IN('administrateur', 'contributeur', 'visiteur'))
);


CREATE TABLE THEME(
    Theme VARCHAR(20),
    PseudoA VARCHAR(20),
    CONSTRAINT PK_THEME PRIMARY KEY (Theme),
    CONSTRAINT FK_THEME FOREIGN KEY (PseudoA) REFERENCES ADMINISTRATEUR (Pseudo)
);

CREATE TABLE EVENEMENT(
    Nom VARCHAR(20),
    Date_Evenement DATETIME,
    Theme VARCHAR(20),
    Descriptif VARCHAR(500),
    EffectifMin INTEGER CHECK (EffectifMin>0),
    EffectifMax INTEGER CHECK (EffectifMax<1000000),
    PseudoC VARCHAR(20),
    Adresse VARCHAR(255) CHECK (Adresse IS NOT NULL),
    CONSTRAINT PK_EVENEMENT PRIMARY KEY (Nom),  
    CONSTRAINT FK_EVENEMENT_THEME FOREIGN KEY (Theme) REFERENCES THEME (Theme),
    CONSTRAINT FK_EVENEMENT_CONTRIBUTEUR FOREIGN KEY (PseudoC) REFERENCES CONTRIBUTEUR (Pseudo)
);

CREATE TABLE INSCRIRE(
    PseudoV VARCHAR(20),
    NomE VARCHAR(20),
    CONSTRAINT PK_INSCRIRE PRIMARY KEY (PseudoV,NomE),
    CONSTRAINT FK_VISITEUR_INSCRIRE FOREIGN KEY (PseudoV) REFERENCES VISITEUR (Pseudo),
    CONSTRAINT FK_EVENEMENT_INSCRIRE FOREIGN KEY (NomE) REFERENCES EVENEMENT (Nom)
);

CREATE TABLE NOTER(
    PseudoV VARCHAR(20),
    NomE VARCHAR(20),
    Note INTEGER(5) CHECK (Note >=0 && Note<=5),
    CONSTRAINT PK_NOTER PRIMARY KEY (PseudoV,NomE),
    CONSTRAINT FK_VISITEUR_NOTER FOREIGN KEY (PseudoV) REFERENCES VISITEUR (Pseudo),
    CONSTRAINT FK_EVENEMENT_NOTER FOREIGN KEY (NomE) REFERENCES EVENEMENT (Nom)
);

CREATE TABLE LOGERROR(
  ID INT(11) AUTO_INCREMENT,
  MESSAGE VARCHAR(255) DEFAULT NULL,
  THETIME TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT PK_LOGERROR PRIMARY KEY (ID)
);

/***************************************************/
/******************** FONCTIONS ********************/
/***************************************************/

/* FONCTION : CheckAlphaNumeric : vérifie que les Pseudos sont composés uniquement de chiffres ou de lettres et autorisation d'1 seul espace OU 1 seul tiret entre deux expressions*/
DROP FUNCTION IF EXISTS CheckAlphaNumeric;  

DELIMITER $$
CREATE FUNCTION CheckAlphaNumeric(Id VARCHAR(20)) RETURNS int(1)
    NO SQL
BEGIN
	RETURN (SELECT CASE WHEN Id REGEXP '^[A-Za-z0-9]+[\\-\\ ]{0,1}[A-Za-z0-9]+$' THEN 1 ELSE 0 END);
END$$

/* FONCTION : CheckAlphabetic : vérifie que les Noms et Prénoms sont composés uniquement de chiffres ou de lettres et autorisation d'1 seul espace OU 1 seul tiret entre deux mots (pour les noms composés) */
DROP FUNCTION IF EXISTS CheckAlphabetic;  

DELIMITER $$
CREATE FUNCTION CheckAlphabetic(Id VARCHAR(20)) RETURNS int(1)
    NO SQL
BEGIN
	RETURN (SELECT CASE WHEN Id REGEXP '^[A-Za-z]+[\\-\\ ]{0,1}[A-Za-z]+$' THEN 1 ELSE 0 END);
END$$

/* FONCTION : CheckEmailAdress : vérifie que la syntaxe d'une adresse mail est respectée */
DROP FUNCTION IF EXISTS CheckEmailAdress;  

DELIMITER $$
CREATE FUNCTION CheckEmailAdress(Email VARCHAR(50)) RETURNS int(1)
    NO SQL
BEGIN
	RETURN (SELECT CASE WHEN Email REGEXP '^[A-Z0-9._%-]+@[A-Z0-9.-]+\\.[A-Z]{2,4}$' THEN 1 ELSE 0 END);
END$$

/* FONCTION : CheckExpiredDate : vérifie qu'une date n'est pas passé */
DROP FUNCTION IF EXISTS CheckExpiredDate;  

DELIMITER $$
CREATE FUNCTION CheckExpiredDate(d DATETIME) RETURNS int(1)
    NO SQL
BEGIN
	RETURN (SELECT CASE WHEN (d < CURRENT_TIMESTAMP) THEN 1 ELSE 0 END);
END$$


/**************************************************/
/**************** TRIGGERS : ADMIN ****************/
/**************************************************/

DROP TRIGGER IF EXISTS ADMIN_INPUT_INSERT;

DELIMITER //
CREATE TRIGGER ADMIN_INPUT_INSERT BEFORE INSERT ON ADMINISTRATEUR
 FOR EACH ROW BEGIN
	DECLARE msg VARCHAR(255);
    IF CheckAlphaNumeric(NEW.Pseudo) = 0
        THEN
            SET msg = "ERREUR : Caractères alphanumériques seulement.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;

    IF CheckAlphabetic(NEW.Nom) = 0 OR CheckAlphabetic(NEW.Prenom) = 0
        THEN
            SET msg = "ERREUR : Nom et/ou prénom incorrectement saisi.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;

    IF CheckEmailAdress(NEW.Email) = 0
        THEN
            SET msg = "ERREUR : Adresse Email incorrectement saisie.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;
END //

/********************************************************/

DROP TRIGGER IF EXISTS ADMIN_INPUT_UPDATE;

DELIMITER //
CREATE TRIGGER ADMIN_INPUT_UPDATE BEFORE UPDATE ON ADMINISTRATEUR
 FOR EACH ROW BEGIN
	DECLARE msg VARCHAR(255);
    IF CheckAlphaNumeric(NEW.Pseudo) = 0
        THEN
            SET msg = "ERREUR : Caractères alphanumériques seulement.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;

    IF CheckAlphabetic(NEW.Nom) = 0 OR CheckAlphabetic(NEW.Prenom) = 0
        THEN
            SET msg = "ERREUR : Nom et/ou prénom incorrectement saisi.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;

    IF CheckEmailAdress(NEW.Email) = 0
        THEN
            SET msg = "ERREUR : Adresse Email incorrectement saisie.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;
END //

/*****************************************************/
/**************** TRIGGERS : VISITEUR ****************/
/*****************************************************/

DROP TRIGGER IF EXISTS VISITOR_INPUT_INSERT;

DELIMITER //
CREATE TRIGGER VISITOR_INPUT_INSERT BEFORE INSERT ON VISITEUR
 FOR EACH ROW BEGIN
	DECLARE msg VARCHAR(255);
    IF CheckAlphaNumeric(NEW.Pseudo) = 0
        THEN
            SET msg = "ERREUR : Caractères alphanumériques seulement.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;

    IF CheckAlphabetic(NEW.Nom) = 0 OR CheckAlphabetic(NEW.Prenom) = 0
        THEN
            SET msg = "ERREUR : Nom et/ou prénom incorrectement saisi.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;

    IF CheckEmailAdress(NEW.Email) = 0
        THEN
            SET msg = "ERREUR : Adresse Email incorrectement saisie.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;
END //

/********************************************************/

DROP TRIGGER IF EXISTS VISITOR_INPUT_UPDATE;

DELIMITER //
CREATE TRIGGER VISITOR_INPUT_UPDATE BEFORE UPDATE ON VISITEUR
 FOR EACH ROW BEGIN
	DECLARE msg VARCHAR(255);
    IF CheckAlphaNumeric(NEW.Pseudo) = 0
        THEN
            SET msg = "ERREUR : Caractères alphanumériques seulement.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;

    IF CheckAlphabetic(NEW.Nom) = 0 OR CheckAlphabetic(NEW.Prenom) = 0
        THEN
            SET msg = "ERREUR : Nom et/ou prénom incorrectement saisi.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;

    IF CheckEmailAdress(NEW.Email) = 0
        THEN
            SET msg = "ERREUR : Adresse Email incorrectement saisie.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;
END //

/*********************************************************/
/**************** TRIGGERS : CONTRIBUTEUR ****************/
/*********************************************************/

DROP TRIGGER IF EXISTS CONTRIB_INPUT_INSERT;

DELIMITER //
CREATE TRIGGER CONTRIB_INPUT_INSERT BEFORE INSERT ON CONTRIBUTEUR
 FOR EACH ROW BEGIN
	DECLARE msg VARCHAR(255);
    IF CheckAlphaNumeric(NEW.Pseudo) = 0
        THEN
            SET msg = "ERREUR : Caractères alphanumériques seulement.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;

    IF CheckAlphabetic(NEW.Nom) = 0 OR CheckAlphabetic(NEW.Prenom) = 0
        THEN
            SET msg = "ERREUR : Nom et/ou prénom incorrectement saisi.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;

    IF CheckEmailAdress(NEW.Email) = 0
        THEN
            SET msg = "ERREUR : Adresse Email incorrectement saisie.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;
END //

/********************************************************/

DROP TRIGGER IF EXISTS CONTRIB_INPUT_UPDATE;

DELIMITER //
CREATE TRIGGER CONTRIB_INPUT_UPDATE BEFORE UPDATE ON CONTRIBUTEUR
 FOR EACH ROW BEGIN
	DECLARE msg VARCHAR(255);
    IF CheckAlphaNumeric(NEW.Pseudo) = 0
        THEN
            SET msg = "ERREUR : Caractères alphanumériques seulement.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;

    IF CheckAlphabetic(NEW.Nom) = 0 OR CheckAlphabetic(NEW.Prenom) = 0
        THEN
            SET msg = "ERREUR : Nom et/ou prénom incorrectement saisi.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;

    IF NEW.PseudoA <> OLD.PseudoA
        THEN
            SET msg = "ERREUR : Impossible de changer la provenance d'un compte Contributeur.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;
END//

/***************************************************/
/**************** TRIGGERS : MEMBRE ****************/
/***************************************************/

DROP TRIGGER IF EXISTS MEMBRE_INPUT_INSERT;

DELIMITER //
CREATE TRIGGER MEMBRE_INPUT_INSERT BEFORE INSERT ON MEMBRE
 FOR EACH ROW BEGIN
	DECLARE msg VARCHAR(255);
    IF CheckAlphaNumeric(NEW.Pseudo) = 0
        THEN
            SET msg = "ERREUR : Caractères alphanumériques seulement.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;
END //

/********************************************************/

DROP TRIGGER IF EXISTS MEMBRE_INPUT_UPDATE;

DELIMITER //
CREATE TRIGGER MEMBRE_INPUT_UPDATE BEFORE UPDATE ON MEMBRE
 FOR EACH ROW BEGIN
	DECLARE msg VARCHAR(255);
    IF CheckAlphaNumeric(NEW.Pseudo) = 0
        THEN
            SET msg = "ERREUR : Caractères alphanumériques seulement.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;
END//

/********************************************************/
/***************** TRIGGERS : EVENEMENT *****************/
/********************************************************/

DROP TRIGGER IF EXISTS EVENT_INPUT_INSERT;

DELIMITER //
CREATE TRIGGER EVENT_INPUT_INSERT BEFORE INSERT ON EVENEMENT
 FOR EACH ROW BEGIN
	DECLARE msg VARCHAR(255);
    IF CheckAlphaNumeric(NEW.Nom) = 0
        THEN
            SET msg = "ERREUR : Caractères alphanumériques seulement.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;

    IF CheckExpiredDate(NEW.Date_Evenement) = 1
        THEN
            SET msg = "ERREUR : Date expirée.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;

    IF NEW.EffectifMax < NEW.EffectifMin
        THEN
            SET msg = "ERREUR : L'effectif minimum doit être inférieur à l'effectif maximum.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;

    IF NEW.Adresse REGEXP '^[[A-Za-z0-9]+[\\-\\ ]{0,1}]+$'
        THEN
            SET msg = "ERREUR : L'adresse est incorrecte.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;
END //

/********************************************************/

DROP TRIGGER IF EXISTS EVENT_INPUT_UPDATE;

DELIMITER //
CREATE TRIGGER EVENT_INPUT_UPDATE BEFORE UPDATE ON EVENEMENT
 FOR EACH ROW BEGIN
	DECLARE msg VARCHAR(255);
    IF CheckAlphaNumeric(NEW.Nom) = 0
        THEN
            SET msg = "ERREUR : Caractères alphanumériques seulement.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;

    IF CheckExpiredDate(NEW.Date_Evenement) = 1
        THEN
            SET msg = "ERREUR : Date expirée.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;

    IF NEW.EffectifMax < NEW.EffectifMin
        THEN
            SET msg = "ERREUR : L'effectif minimum doit être inférieur à l'effectif maximum.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;

    IF NEW.Adresse REGEXP '^[[A-Za-z0-9]+[\\-\\ ]{0,1}]+$'
        THEN
            SET msg = "ERREUR : L'adresse est incorrecte.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;
END //

/****************************************************/
/***************** TRIGGERS : THEME *****************/
/****************************************************/

DROP TRIGGER IF EXISTS THEME_INPUT_INSERT;

DELIMITER //
CREATE TRIGGER THEME_INPUT_INSERT BEFORE INSERT ON THEME
 FOR EACH ROW BEGIN
	DECLARE msg VARCHAR(255);
    IF CheckAlphabetic(NEW.Theme) = 0
        THEN
            SET msg = "ERREUR : Intitulé incorrectement saisi.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;
END //

/********************************************************/

DROP TRIGGER IF EXISTS THEME_INPUT_UPDATE;

DELIMITER //
CREATE TRIGGER THEME_INPUT_UPDATE BEFORE UPDATE ON THEME
 FOR EACH ROW BEGIN
	DECLARE msg VARCHAR(255);
    IF CheckAlphabetic(NEW.Theme) = 0
        THEN
            SET msg = "ERREUR : Intitulé incorrectement saisi.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;

    IF NEW.PseudoA <> OLD.PseudoA
        THEN
            SET msg = "ERREUR : Impossible de changer le créateur du thème.";
            INSERT INTO LOGERROR(MESSAGE) VALUES (msg);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;
END //
