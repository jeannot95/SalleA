<?php 
require_once('../inc/init.inc.php');

//-----------------TRAITEMENT-----------------
// 1- On vérifie que le membre est admin :
if (!internauteEstConnecteEtEstAdmin()){
	header('location:../connexion.php');
	exit();
}

// 7- Suppression du produit :
if (isset($_GET['action']) && $_GET['action']=='suppression' && isset($_GET['id_produit'])){ // si les indices "action" et "id_produit", c'est que l'url est complète
	
	//$resultat = executeRequete("SELECT photo FROM produit WHERE id_produit = :id_produit", array(':id_produit' => $_GET['id_produit']));
	
	/*if ($resultat->rowCount() == 1){
		// ici le produit existe
		$produit_a_supprimer = $resultat->fetch(PDO::FETCH_ASSOC); // pas de boucle car on a qu'un seul produit par id
		
		if(!empty($produit_a_supprimer['photo'])){ // si il y a une photo dans la BDD on peut supprimer la photo physique :
			$chemin_photo_a_supprimer = $_SERVER['DOCUMENT_ROOT'] . RACINE_SITE . $produit_a_supprimer['photo']; // chemin complet du fichier photo : 
			// C:/wamp64/www/PHP/08-site/photo/nomphoto.jpg
			
			if (file_exists($chemin_photo_a_supprimer))	unlink($chemin_photo_a_supprimer);	// si le fichier existe, on le supprime avec unlink()		
			
		}*/
		
		executeRequete("DELETE FROM produit WHERE id_produit = :id_produit", array(':id_produit'=> $_GET['id_produit']));
		$contenu .= '<div class="bg-success">Produit supprimé !</div>';
		
	/*} else {
		// ici le produit n'existe pas
		$contenu .= '<div class="bg-danger">Produit inexistant !</div>';
	}	//$_GET['action']='affichage'; // afficher automatiquement le tableau des produits après suppression*/
}

// 4- Traitement du formulaire : enregistrement du produit :
if ($_POST){// si le formulaire est soumis ou posté
	
	
	
	 if (empty($_POST['prix'])) {
		// si l'indice 'pseudo' n'existe pas, ou que sa longueur est <4 ou >20, on met un message d'erreur :
		$contenu .= '<div class="bg-danger">Veuillez rentrez un prix.</div>';		
	}	 
	if ( $_POST['prix'] > 1200) {
		// si l'indice 'pseudo' n'existe pas, ou que sa longueur est <4 ou >20, on met un message d'erreur :
		$contenu .= '<div class="bg-danger">Veuillez rentrez un prix inférieur à 1200 €.</div>';		
	}		
	
	if (!ctype_digit($_POST['prix']) && !empty($_POST['prix'])) {
		// si l'indice 'pseudo' n'existe pas, ou que sa longueur est <4 ou >20, on met un message d'erreur :
		$contenu .= '<div class="bg-danger">Veuillez rentrez un prix (en chiffre entier).</div>';		
	}	
	
 	 if (empty($_POST['date_arrivee'])) {
		// si l'indice 'pseudo' n'existe pas, ou que sa longueur est <4 ou >20, on met un message d'erreur :
		$contenu .= '<div class="bg-danger">Veuillez rentrez une date d\'arrivée.</div>';		
	}	 

	if (!strtotime($_POST['date_arrivee'])&& !empty($_POST['date_arrivee'])){
		$contenu .= '<div class="bg-danger">Veuillez rentrez une date d\'arrivée correcte.</div>';
	}
	
	if (!strtotime($_POST['date_depart'])&& !empty($_POST['date_depart'])){
		$contenu .= '<div class="bg-danger">Veuillez rentrez une date de départ correcte.</div>';
	}	
		
	
 	if (empty($_POST['date_depart'])) {
		// si l'indice 'pseudo' n'existe pas, ou que sa longueur est <4 ou >20, on met un message d'erreur :
		$contenu .= '<div class="bg-danger">Veuillez rentrez une date de départ.</div>';		
	} 

/* 	if($_POST['etat'] == 'reservation'){
		$contenu .= '<div class="bg-danger">Vous ne pouvez pas modifier un produit déjà réservé.</div>';
	} */
	
	if (empty($contenu)){
	if (isset($_POST['date_arrivee']) && isset($_POST['date_depart'])){
		$date_dispo = executeRequete("SELECT * FROM produit WHERE id_salle = :id_salle AND (( :date_arrivee >= date_arrivee AND :date_depart <= date_depart) OR (:date_arrivee < date_arrivee AND :date_depart >= date_arrivee AND :date_depart <= date_depart) OR (:date_depart > date_depart AND :date_arrivee <= date_depart AND :date_arrivee >= date_arrivee) OR (:date_arrivee < date_arrivee AND :date_depart > date_depart)) AND NOT id_produit  = :id_produit ", array(
				':id_salle' => $_POST['id_salle'],
				':date_arrivee' => $_POST['date_arrivee'],
				':date_depart' => $_POST['date_depart'],
				':id_produit' => $_POST['id_produit']
			
	));
	
	$rt = $date_dispo->fetch(PDO::FETCH_ASSOC);
	//debug($rt);
		if ($rt >= 1 ) {
			$contenu .= '<div class="bg-danger"> Ces plages de dates ne sont pas disponibles pour cette salle !!! </div>';
		} 
		else if ($_POST['date_arrivee'] <= $_POST['date_depart'])
		{	
			
			executeRequete("REPLACE INTO produit (id_produit, date_arrivee, date_depart, id_salle, prix, etat) VALUES(:id_produit, :date_arrivee, :date_depart, :id_salle, :prix, 'libre') ", 
					array(
						':id_produit' 	=> $_POST['id_produit'],
						':date_arrivee' 	=> $_POST['date_arrivee'],
						':date_depart' 	=> $_POST['date_depart'],
						':id_salle' 		=> $_POST['id_salle'],
						':prix'	=> $_POST['prix']				
					));
	// Note : quand on ne spécifie pas les champs impactés par le REPLACE, il faut mettre dans VALUES tous les champs de la table exactement dans le même ordre que dans cette table
			$contenu .= '<div class="bg-success">Le produit a bien été enregistré.</div>';
	
//$_GET['action'] = 'affichage'; 
		} else {$contenu .= '<div class="bg-danger"> La date de départ ne peut pas être antérieure à la date d\'arrivée !!! </div>';}
	// on met unn indice "action" et une valeur "affichage dans $_GET pour forcer l'affichage du tableau HTML de tous les produits un peu plus bas (cf chapitre 6)
	
	}//else {$contenu .= '<div class="bg-danger">Les dates se chevauchent!!!.</div>';}
	}
}

// 6- Affichage des produits sous forme de table HTML :
// si on demande l'affichage en GET :

	$resultat = executeRequete("SELECT * FROM produit"); // on obtient un objet PDOStatement non exploitable directement : il faudra donc faire un fetch dessus

	//$contenu .= '<h3> Affichage des produits</h3>';
	$contenu .= 'Nombre de produits dans la boutique : ' . $resultat->rowCount();
	$contenu .=  '<div id="target" style="overflow: scroll; height: 55vh;">';
	$contenu .= '<table class="table text-center" >';
		// Affichage des entêtes :
		$contenu .='<tr>';
			for($i=0;$i<$resultat->columnCount();$i++){
				// debug($resultat->getColumnMeta($i)); // pour voir ce que retourne cette méthode : un array avec notamment un indice "name" qui contient le nom du champ
				$colonne =$resultat->getColumnMeta($i); // array
				$contenu .='<th class="text-center">'.$colonne['name']. '</th>';  
			}
			$contenu .= '<th class="text-center">Actions</th>';
		$contenu .='</tr>';	
		
	$contenu .='<hr>';
	

		
	// Affichage des autres lignes :
	while ($ligne = $resultat->fetch(PDO::FETCH_ASSOC)) {
		// debug($ligne);
		$contenu .= '<tr>';
		
							$photo2 = executeRequete("SELECT photo FROM salle WHERE id_salle = ". $ligne['id_salle'] ."");
							$photo = $photo2->fetch(PDO::FETCH_ASSOC);

							$titre2 = executeRequete("SELECT titre FROM salle WHERE id_salle = ". $ligne['id_salle'] ."");
							$titre = $titre2->fetch(PDO::FETCH_ASSOC);
							
		
			foreach($ligne as $indice=>$info){
				if ($indice == 'photo'){
					$contenu .= '<td><img src="../'.$info.'" width="90" height="90" ></td>';
					

					
				} else if ($indice == 'prix'){ $contenu .= '<td>'.$info . ' €</td>'; }
				else if ($indice == 'id_salle'){ $contenu .= '<td>'.$info . ' - '. $titre['titre'] .'<br><img src="../'.$photo['photo'].'" width="90" height="90" ></td>'; }
				else{	$contenu .= '<td>'.$info . '</td>';
				}
			}
			if($ligne['etat']=='reservation'){
			$contenu .= '<td>
							<a href="?action=modification&id_produit='.$ligne['id_produit'].'" onclick="return confirm(\'Souhaitez vous vraiment modifier une salle déjà réservée??\')">Modifier </a>';
			} else {
				$contenu .= '<td>
							<a href="?action=modification&id_produit='.$ligne['id_produit'].'">Modifier </a>';
			}				
			$contenu .=		'/
							<a href="?action=suppression&id_produit='.$ligne['id_produit'].'" onclick="return(confirm(\'Etes-vous certain de vouloir supprimer ce produit ? \'))" >Supprimer </a>
						</td>';
						
		$contenu .= '</tr>';
	}		
		
	$contenu .= '</table>';
	$contenu .= '</div>';

////////////////////////////

	$resultat2 = executeRequete("SELECT * FROM salle");



//-----------------AFFICHAGE------------------
require_once('../inc/haut.inc.php');

// é- création des onglets "affichage" et "ajout" des produits :
// quand on débute un href âr un "?" c'est que l'on envoi en GET dans l'url des infos à la même page


echo $contenu;
$contenu2='';


// 3- Formulaire HTML : on affiche le formulaire uniquement en action "ajout" ou "modification" de produit :
if (isset($_GET['action']) && ($_GET['action'] == 'modification')) : // syntaxe en if() :...endif; utile quand on mélange beaucoup de HTML/PHP dans la condition


	// 8- Formulaire de modification de produit :
	if (isset($_GET['id_produit'])){ // si id_produit est dans l'url c'est que l'on modifie un produit existant : on requête en BDD les infos du produit à afficher :
		$resultat = executeRequete("SELECT DATE_FORMAT(date_arrivee,'%Y/%m/%d 09:00:00') as date_a, DATE_FORMAT(date_depart,'%Y/%m/%d 19:00:00') as date_d, id_produit, id_salle, prix  FROM produit WHERE id_produit = :id_produit", array(':id_produit' => $_GET['id_produit']));
		$produit_actuel = $resultat->fetch(PDO::FETCH_ASSOC); // pas de while car un seul produit
		//debug ($produit_actuel);
		
	} 
endif;

echo $contenu2;

?>	
	<h3><a href="gestion_produits.php">Ajout</a> ou modification d'un produit</h3>
	<form method="post" action="" enctype="multipart/form-data"><!-- multipart/form-data spécifie que le formulaire envoie des données texte(champs du formulaire) et des données binaires (=fichier) -->
		<div class="col-sm-5">
		<input type="hidden" id="id_produit" name="id_produit" value="<?php echo $produit_actuel['id_produit']?? 0; ?>"> <!-- champ caché pour ne pas pouvoir le modifier. Il est utile pour connaître l'id du produit que l'on est en train de modifier -->
		<label for="date_arrivee">Date d'arrivée</label><br>
		<input type="text" id="date_arrivee2" name="date_arrivee" value="<?php echo $produit_actuel['date_a']?? ''; ?>"><br><br>
		
		<label for="date_depart">Date de départ</label><br>
		<input type="text" id="date_depart2" name="date_depart" value="<?php echo $produit_actuel['date_d']?? ''; ?>"><br><br>		
		</div>
		<label for="id_salle">Salle</label><br>
		<select name="id_salle">
			<?php for($i = 0; $i<$resultat2->rowCount();$i++){ 	while ($ligne2 = $resultat2->fetch(PDO::FETCH_ASSOC)) {
			//debug($ligne2);
			
			$salle = $ligne2['id_salle'].' - '.$ligne2['titre'].' - '.$ligne2['adresse'].', '.$ligne2['cp'].', '.$ligne2['ville'].' - '.$ligne2['capacite'].' pers';
			//echo $ligne2['titre'];
			//debug($salle);
	 
			echo '<option value="'.$ligne2['id_salle'].'"'?> <?php if(isset($produit_actuel['id_salle']) && $produit_actuel['id_salle'] == $ligne2['id_salle']) echo 'selected'; ?> ><?php echo $salle ?></option><?php }} ?>
			
		</select><br><br>
		
		<label for="prix">Tarif €</label><br>
		<input type="text" id="prix" name="prix" value="<?php echo $produit_actuel['prix']?? ''; ?>"><br><br>

		<input type="submit" value="valider" class="btn">		
	</form>
	
<?php	
 // Ce endif ferme le if du début du chapitre 3
require_once('../inc/bas.inc.php');

































