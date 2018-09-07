<?php 
require_once('../inc/init.inc.php');

//-----------------TRAITEMENT-----------------
// 1- On vérifie que le membre est admin :
if (!internauteEstConnecteEtEstAdmin()){
	header('location:../connexion.php');
	exit();
}

// 7- Suppression du produit :
if (isset($_GET['action']) && $_GET['action']=='suppression' && isset($_GET['id_avis'])){ // si les indices "action" et "id_produit", c'est que l'url est complète
		
		executeRequete("DELETE FROM avis WHERE id_avis = :id_avis", array(':id_avis'=> $_GET['id_avis']));
		$contenu .= '<div class="bg-success">Avis supprimé !</div>';
		
	} //$_GET['action']='affichage'; // afficher automatiquement le tableau des produits après suppression



// 6- Affichage des produits sous forme de table HTML :
// si on demande l'affichage en GET :

	$resultat = executeRequete("SELECT a.id_avis, a.id_membre,a.id_salle,a.commentaire, a.note, a.date_enregistrement,s.titre,m.email FROM avis a LEFT JOIN salle s ON s.id_salle = a.id_salle LEFT JOIN membre m ON m.id_membre = a.id_membre"); // on obtient un objet PDOStatement non exploitable directement : il faudra donc faire un fetch dessus

	$contenu .= '<h3> Affichage des avis</h3>';
	$contenu .= 'Nombre d\'avis dans la boutique : ' . $resultat->rowCount();
	$contenu .=  '<div id="target" style="overflow: scroll; height: 50vh;">';
	$contenu .= '<table class="table text-center">';
		// Affichage des entêtes :
		$contenu .='<tr>';
			for($i=0;$i<$resultat->columnCount();$i++){
				// debug($resultat->getColumnMeta($i)); // pour voir ce que retourne cette méthode : un array avec notamment un indice "name" qui contient le nom du champ
				$colonne =$resultat->getColumnMeta($i); // array
					if(($colonne['name'] != 'titre') && ($colonne['name'] !='email')){
						$contenu .='<th class="text-center">'.$colonne['name']. '</th>';
					}
			}
			$contenu .= '<th class="text-center">Actions</th>';
		$contenu .='</tr>';	
		
	$contenu .='<hr>';
	// Affichage des autres lignes :
	while ($ligne = $resultat->fetch(PDO::FETCH_ASSOC)) {
		// debug($ligne);
		$contenu .= '<tr>';
			foreach($ligne as $indice=>$info){
				if ($indice != 'email' && $indice != 'titre'){
					if($indice == 'id_membre'){
						$contenu .= '<td>'.$info . ' - '. $ligne['email'] .'</td>';
					} else if($indice == 'id_salle'){
						$contenu .= '<td>'.$info . ' - '. $ligne['titre'] .'</td>';
					} else if($indice == 'commentaire'){
						$contenu .= '<td>'.substr($info,0, 15). '...</td>';
					}
					else{
						$contenu .= '<td>'.$info . '</td>';
					}
				}
			}
			$contenu .= '<td>
							<a href="?action=affichage&id_avis='.$ligne['id_avis'].'">afficher </a>
							/
							<a href="?action=suppression&id_avis='.$ligne['id_avis'].'" onclick="return(confirm(\'Etes-vous certain de vouloir supprimer cet avis ? \'))" >Supprimer </a>
						</td>';
						
		$contenu .= '</tr>';
	}		
		
	$contenu .= '</table>';
	$contenu .= '</div>';
	
if (isset($_GET['action']) && ($_GET['action'] == 'affichage')) {
	$r = executeRequete("SELECT a.id_avis, a.id_membre,a.id_salle,a.commentaire, a.note, a.date_enregistrement,s.titre,m.email,m.pseudo FROM avis a LEFT JOIN salle s ON s.id_salle = a.id_salle LEFT JOIN membre m ON m.id_membre = a.id_membre WHERE :id_avis = a.id_avis", array(':id_avis' => $_GET['id_avis']));
	$l = $r->fetch(PDO::FETCH_ASSOC);
	$contenu .= '<ul>Avis de : '.$l['pseudo'] .' <li>Id_avis : '.$l['id_avis'] .'</li><li>Pseudo : '.$l['pseudo'] .'</li><li>Commentaire : '.$l['commentaire'] .'</li><li>Note : '.$l['note'] .'/5</li><li>Date d\'enregistrement : '.$l['date_enregistrement'].'</li></ul>' ;
}	

require_once('../inc/haut.inc.php');

// é- création des onglets "affichage" et "ajout" des produits :

echo $contenu;

// 3- Formulaire HTML : on affiche le formulaire uniquement en action "ajout" ou "modification" de produit :
 // syntaxe en if() :...endif; utile quand on mélange beaucoup de HTML/PHP dans la condition



require_once('../inc/haut.inc.php');






require_once('../inc/bas.inc.php');




