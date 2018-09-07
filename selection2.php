<?php


require_once('inc/init.inc.php');
$contenu = ''; // contiendra la réponse html
$categorie =true; // pour que la requête sql fonctionne par défaut en sélectionnant tous les produits
$ville =true;
$capacite =true;
$date_arrivee =true;
$date_depart =true;
$contenu_droite = '';
$contenu_gauche='';

if(!empty($_POST)){ // si $_POST n'est pas vide c'est qu'on a reçu une requête HTTP en POST de la part du client
	//debug($_POST);
	// On construit alors notre requête "variable" :
	if(isset($_POST['categorie'])){
		$categorie = "categorie IN ('". implode("','", $_POST['categorie']) ."')";
	}
	if(isset($_POST['ville'])){
		$ville = "ville IN ('". implode("','", $_POST['ville']) ."')";
	}
	if(isset($_POST['capacite'])){
		$capacite = "capacite >= " . $_POST['capacite'];
	}
	if(isset($_POST['prix'])){
		$prix = "prix <= " . $_POST['prix'];
	}
	/*if(isset($_POST['date_arrivee'])){
		$date_arrivee = "date_arrivee" ;
	}	else {
		"date_arrivee =  NOW()";
		}
	debug($date_arrivee);	/*
	if(isset($_POST['date_depart'])){
		$date_depart = $_POST['date_depart'];
	}	*/
}

$donnees2 = executeRequete("
	SELECT p.id_produit, s.photo , s.titre, p.prix, s.description, DATE_FORMAT(p.date_arrivee,'%d/%m/%Y') as dateArrivee, DATE_FORMAT(p.date_depart,'%d/%m/%Y') AS dateDepart,s.id_salle, s.ville, s.categorie, s.capacite, ROUND(AVG(a.note),1)AS note 
	FROM produit p 
	JOIN salle s ON s.id_salle=p.id_salle 
	LEFT JOIN avis a ON s.id_salle=a.id_salle 
	WHERE p.etat= 'libre' 
	AND $categorie 
	AND $ville 
	AND $capacite 
	AND $prix 
	AND date_arrivee >= :date_arrivee 
	AND date_depart <= :date_depart
	GROUP BY id_produit", array(
		':date_arrivee'=> $_POST['date_arrivee'],
		':date_depart'=> $_POST['date_depart']
	));

//debug($donnees2);
if($donnees2->rowCount() > 1){
	$contenu_gauche .=  $donnees2->rowCount() .' résultats';
	} else if ($donnees2->rowCount() == 1){
		$contenu_gauche .=  $donnees2->rowCount() .' résultat';
	} else {
		$contenu_gauche .=  'Aucun résultat';
	}

// Envoi des données au client :
echo $contenu_gauche;



?>



















