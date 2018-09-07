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
	GROUP BY id_produit
	ORDER BY note DESC
	", array(
		':date_arrivee'=> $_POST['date_arrivee'],
		':date_depart'=> $_POST['date_depart']
	));

//debug($donnees2);

if ($donnees2->rowCount() > 0){// si il ya a des produits :
	while($produit = $donnees2->fetch(PDO::FETCH_ASSOC)){
		//if ($produit['dateDepart'] >= date("Y-m-d" )){
			//debug($produit);
			$contenu_droite .= '<div class="col-sm-4">';
				$contenu_droite .= '<div class="thumbnail">';
					// image cliquable :
					$contenu_droite .= '<a href="fiche_produit.php?id_produit='. $produit['id_produit'] .'"><img src="'.$produit['photo'].'"  ></a>';
				//debug($produit);
					// les infos du produit :
					$contenu_droite .= '<div class="caption">';
						$contenu_droite .='<h4 class="pull-right">'.$produit['prix'].' €</h4>';
						$contenu_droite .='<h4>'.$produit['titre'].'</h4>';
						$contenu_droite .='<p>'. substr($produit['description'], 0, 30).'... <br>';
						$contenu_droite .= $produit['dateArrivee'].' au '. $produit['dateDepart'].'</p>';
						
						if($produit['note']>0){	//$contenu_droite .= '<p>'.$produit['note']*20 .'</p>';
						$contenu_droite .= '<a class="etoile" href="?action=avis&id_salle=' .$produit['id_salle']. '"><div class="star-ratings-css" title="'.$produit['note'].'/5">
							<div class="star-ratings-css-top" style="width: '.$produit['note']*20 .'%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
							<div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
						</div></a>';}
						
						//$contenu_droite .='<p>'.$produit['note'].' </p>';
				
					$contenu_droite .= '</div>';
				$contenu_droite .= '</div>'; // .thumbnail
			$contenu_droite .= '</div>'; 
		//}// .col-sm-4
	}
}	else {
			$contenu_droite .= '<h4>Aucun résultat ne correspond à votre recherche.</h4>';
		}


	  


	  
// Envoi des données au client :
echo $contenu_droite;
//echo $contenu_gauche;





?>









































