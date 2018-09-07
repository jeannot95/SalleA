<?php 
require_once('../inc/init.inc.php');

//-----------------TRAITEMENT-----------------
// 1- On vérifie que le membre est admin :
if (!internauteEstConnecteEtEstAdmin()){
	header('location:../connexion.php');
	exit();
}

// 7- Suppression du produit :
if (isset($_GET['action']) && $_GET['action']=='suppression' && isset($_GET['id_commande'])){ // si les indices "action" et "id_produit", c'est que l'url est complète
		
		executeRequete("DELETE FROM commande WHERE id_commande = :id_commande", array(':id_commande'=> $_GET['id_commande']));
		$contenu .= '<div class="bg-success">commande supprimée !</div>';
		
	} //$_GET['action']='affichage'; // afficher automatiquement le tableau des produits après suppression



// 6- Affichage des produits sous forme de table HTML :
// si on demande l'affichage en GET :

	$resultat = executeRequete("SELECT c.id_commande,c.id_membre,c.id_produit,p.prix,c.date_enregistrement,m.email,s.titre,DATE_FORMAT(p.date_arrivee,'%d/%m/%Y') as dateArrivee,DATE_FORMAT(p.date_depart,'%d/%m/%Y') AS dateDepart
								FROM commande c
								LEFT JOIN membre m ON c.id_membre=m.id_membre
								LEFT JOIN produit p ON c.id_produit=p.id_produit
								LEFT JOIN salle s ON s.id_salle = p.id_salle;
								"); // on obtient un objet PDOStatement non exploitable directement : il faudra donc faire un fetch dessus

	$contenu .= '<h3> Affichage des commandes</h3>';
	$contenu .= 'Nombre de commandes dans la boutique : ' . $resultat->rowCount();
	$contenu .=  '<div id="target" style="overflow: scroll; height: 70vh;">';
	$contenu .= '<table class="table text-center" >';
		// Affichage des entêtes :
		$contenu .='<tr>';
			for($i=0;$i<$resultat->columnCount();$i++){
				// debug($resultat->getColumnMeta($i)); // pour voir ce que retourne cette méthode : un array avec notamment un indice "name" qui contient le nom du champ
				$colonne =$resultat->getColumnMeta($i); // array
				if(($colonne['name'] != 'titre') && ($colonne['name'] !='email') && ($colonne['name'] !='dateDepart')&& ($colonne['name'] !='dateArrivee')){
					$contenu .='<th class="text-center">'.$colonne['name']. '</th>'; 
				}
			}
			$contenu .= '<th class="text-center">Actions</th>';
		$contenu .='</tr>';	
		
	$contenu .='<hr>';
	// Affichage des autres lignes :
	while ($ligne = $resultat->fetch(PDO::FETCH_ASSOC)) {
		 //debug($ligne);
		$contenu .= '<tr>';
			foreach($ligne as $indice=>$info){
				if ($indice != 'email' && $indice != 'titre' && $indice != 'dateArrivee' && $indice != 'dateDepart'){
					if ($indice == 'prix'){
						$contenu .= '<td class="text-center">' .$info . ' €</td>';
					} else if($indice == 'id_membre'){
						$contenu .= '<td>' .$info . ' - <em>'. $ligne['email'] .'</em> </td>';
					} else if($indice == 'id_produit'){
						$contenu .= '<td>' .$info . ' - <em>'. $ligne['titre'] .'</em><br><em>'. $ligne['dateArrivee'] .' au '. $ligne['dateDepart'] .'</em> </td>';
					} else {
						$contenu .= '<td>'.$info . '</td>';
					}
				}	
			}
			$contenu .= '<td>
							<a href="?action=suppression&id_commande='.$ligne['id_commande'].'" onclick="return(confirm(\'Etes-vous certains de vouloir supprimer cette commande ? \'))" >Supprimer </a>
						</td>';
						
		$contenu .= '</tr>';
	}		
		
	$contenu .= '</table>';
	$contenu .= '</div>';

require_once('../inc/haut.inc.php');

// é- création des onglets "affichage" et "ajout" des produits :

echo $contenu;

// 3- Formulaire HTML : on affiche le formulaire uniquement en action "ajout" ou "modification" de produit :
 // syntaxe en if() :...endif; utile quand on mélange beaucoup de HTML/PHP dans la condition



require_once('../inc/haut.inc.php');






require_once('../inc/bas.inc.php');