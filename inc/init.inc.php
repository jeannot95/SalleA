<?php
 // Ce fichier sera inclus dans tous les scripts du site(hors les fichiers inc eux mêmes. Ainsi, les paramètres qui y sont définis serant disponibles partout.
 
 // Connexion à la BDD :
$pdo = new PDO (
		'mysql:host=localhost;dbname=sallea', // driver mysql + serveur hôte + nom bdd
		'root', // login du sgbd
		'', // mot de passe du sgbd
		array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
		) // option 1 : pour générer l'affichage des erreurs, option 2 : définit le jeu de caractères dess échanges avec la bdd
);


// Session : 
session_start();


// Chemin du site :
define('RACINE_SITE', '/projet_sallea/'); // chemin absolu du site à partir de localhost. Utile pour faire des liens dynamiques selon que le fichier source qui les contient sont dans le dossier/admin/ (back-office) ou à la racine du site(front-office)


// Variables d'affichage :
$contenu='';
$contenu_gauche='';
$contenu_droite='';

// Inclusion du fichier de fonctions :
require_once('fonctions.inc.php');




































