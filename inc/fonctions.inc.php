<?php

function debug($var) {
	echo '<div style="border: 1px solid orange">';
		echo '<pre>';print_r($var);echo '</pre>';
	echo '</div>';	
}


//-----------------------------------------------------
// Fonctions liées aux membres :
// Fonction pour déterminer si un membre est connecté :

function internauteEstConnecte(){
	if(isset($_SESSION['membre'])){ // Si membre existe dans $_SESSION c'est que l'internaute est passé par le formulaire de connexion avec le bon mot de passe(cf connexion.php)
		return true;
	} else {
		return false;
	}
	// return (isset($_SESSION['membre'])); // On peut mettre ça à la place du if...else...
}

// Fonction pour déterminer si un membre est connecté et qu'il est administrateur :
function internauteEstConnecteEtEstAdmin () {
	if (internauteEstConnecte() && $_SESSION['membre']['statut']== 1){
		return true;
	} else {
		return false;
	}
	// return (internauteEstConnecte() && $_SESSION['membre']['statut']== 1); // Idem que précédemment
	
}

//-----------------------------------------------------
// Fonctions pour exécuter des requêtes:
function executeRequete($req, $param = array()) {
	if (!empty($param)) { // si j'ai reçu des valeurs associées aux marqueurs, je fais un htmlspecialchars pour les échapper = convertir les caractères spéciaux en entité HTML :
		foreach($param as $indice => $valeur){
			$param[$indice] = htmlspecialchars($valeur, ENT_QUOTES); // on prend la valeur de $param que l'on traite par htmlspecialchars et que l'on remet à son indice(çàd exactement à la même place). Permet d'éviter les injections XSS et CSS
		}
	}
	
	global $pdo; // permet d'avoir accès à la variable $pdo définie dans l'espace global, à l'intérieur de l'espace local de la fonction executeRequete
	
	$r = $pdo->prepare($req); // on prépare la requête reçu en argument
	$r->execute($param); // on exécute la requête fourni en passant l'array $param qui associe les marqueurs aux variables
	
	return $r; // on retourne l'objet PDOStatement à l'endroit où la fonction executeRequete est appelée(utile aux SELECT)
}

//-----------------------------------------------------
// Fonctions pour panier:
function creationDuPanier(){
	if (!isset($_SESSION['panier'])){ // si le panier n'existe pas, on le crée(vide):
		$_SESSION['panier'] = array();
		$_SESSION['panier']['id_produit'] = array();
		$_SESSION['panier']['titre'] = array();
		$_SESSION['panier']['reference'] = array();
		$_SESSION['panier']['quantite'] = array();
		$_SESSION['panier']['prix'] = array();
	}
}

function ajouterProduitDansPanier($id_produit, $titre, $reference, $quantite, $prix) { // ces paramètres reçoivent des valeurs qui leur sont communiquées lors de l'appel de la fonction

	creationDuPanier(); // on crée d'abord le panier vide
	
	// nous devons savoir si l'id_produit que l'on souhaite ajouter est déjà présent dans le panier pour ne pas avoir de doublons de produits :
	$position_produit = array_search($id_produit, $_SESSION['panier']['id_produit']); // array_search retourne l'indice du produit si il existe sinon false. Remarque : le premier indice est à la position 0. Ce pourquoi on test avec "=== false" pour ne considérer dans la condition que le booléen false et exclure l'indice 0 (integer) dont la valeur implicite est aussi false. Ainsi je n'entre dans la condition que si j'obtien un booléen false.
	
	if ($position_produit === false) { // ici le produit n'est pas encore dans le panier : on l'ajout donc :
		$_SESSION['panier']['id_produit'][] = $id_produit; // les crochets vides pour remplir l'array par la fin, les indices étant numériques
		$_SESSION['panier']['titre'][] = $titre;
		$_SESSION['panier']['reference'][] = $reference;
		$_SESSION['panier']['quantite'][] = $quantite;
		$_SESSION['panier']['prix'][] = $prix;
	} else { // le produit étant déjà dans le panier, on augmente sa quantité :
		$_SESSION['panier']['quantite'][$position_produit] += $quantite;
	}
	
}


//-----------------------
function montantTotal(){
	$total = 0; // variable pour stocker le total du panier
	
	for($i = 0; $i < count($_SESSION['panier']['id_produit']);$i++){
		$total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i]; // on multiplie quantité x prix et on l'ajoute à la variable $total
	} 
	
	return round($total, 2); // on arrondit le total à 2 décimales puis le retourne à l'endroit où la fonction est appelée(dans panier.php)
}

//--------------------------------------
function retirerProduitDuPanier($id_produit){
	// on détermine l'indice du produit à supprimer dans le panier :
	$position_produit = array_search($id_produit, $_SESSION['panier']['id_produit']); // renvoie false si on ne trouve pas l'id_produit, sinon retourne son indice dans le panier
	if ($position_produit !== false){ // si on trouve le produit dans le panier, nous n'obtenons pas false mais bien un indice : on coupe alors le produit :
		array_splice($_SESSION['panier']['id_produit'], $position_produit, 1); // efface et remplace une portion de tableau à partir de l'ince $position_produit et sur 1 indice
		array_splice($_SESSION['panier']['reference'], $position_produit, 1);
		array_splice($_SESSION['panier']['quantite'], $position_produit, 1);
		array_splice($_SESSION['panier']['prix'], $position_produit, 1);
	}	
}


//--------------------------------------
// Exercice : créer une fonction qui retourne le nombre de produits différents dans le panier, et afficher le résultat à côté du lien "panier" dans le menu de navigation. Exemple :panier(4). En l'absence de produit, on affiche :panier(0)


function nombreProduitPanier(){
	if (isset($_SESSION['panier'])){
	// return count($_SESSION['panier']['id_produit']);
	return array_sum($_SESSION['panier']['quantite']); // additionne les valeurs integer positionnées à l'indice "quantité"
	} else { return 0;}
}

function nombreProduitPanier2(){
	$total = 0;
	if (isset($_SESSION['panier'])){
		for($i = 0; $i < count($_SESSION['panier']['quantite']);$i++){
		$total += $_SESSION['panier']['quantite'][$i] ; // on ajoute quantité à la variable $total
	} return round($total);} else { return 0;}	
}

























































