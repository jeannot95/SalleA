<?php 
require_once('inc/init.inc.php');

//--------------TRAITEMENT---------------------
// 1- on redirige l'internaute vers la page de connexion s'il n'est pas connecté : 
if (!internauteEstConnecte()){ // si pas connecté :
	header('location:connexion.php'); // nous l'invitons à se connecter
	exit();
} 

// -2 Préparation  du profil à afficher :
// debug($_SESSION);

$contenu.= '<h2>Bonjour '. $_SESSION['membre']['pseudo'] .'</h2>';

if (internauteEstConnecteEtEstAdmin()){ // si membre administrateur :
	$contenu .= '<p>Vous êtes administrateur</p>';
}

$contenu.='<div><h3>Vos informations de profil : </h3>';
	$contenu .= '<p>Votre email : '. $_SESSION['membre']['email'] .'</p>';
	$contenu .= '<p>Votre date d\'inscription : '. $_SESSION['membre']['date_enregistrement'] .'</p>';
$contenu.='</div>';


//----------
// Exercice : Afficher le suivi des commandes de l'internaute connecté dans une liste <ul><li> avec les infos suivantes : id_commande, date et état. S'il n'y a pas de commande à afficher on met "aucune commende en cours".
$resultat = executeRequete("SELECT *, DATE_FORMAT(date_enregistrement, '%d/%m/%Y à %H:%i:%s')AS date_fr FROM commande WHERE id_membre= :id_membre", array(':id_membre' => $_SESSION['membre']['id_membre']));


if ($resultat->rowcount() > 0){
$contenu .= '<h3>Vous avez '.$resultat->rowcount().' commandes en cours : </h3>';
$contenu .= '<ul>';	
while($ligne = $resultat->fetch(PDO::FETCH_ASSOC)){
// debug($ligne);
	
		$contenu .= '<li class="list-group-item">numero commande : '.$ligne['id_commande'];
		$contenu .= ' / date de commande : '.$ligne['date_fr'].'';
		$contenu .= ' / statut : Réservée</li>';	
	
} $contenu.='</ul>';
} else { $contenu.='<p>Aucune commande en cours</p>';}



//----------------AFFICHAGE---------------------
require_once('inc/haut.inc.php');
echo $contenu;
require_once('inc/bas.inc.php');

























