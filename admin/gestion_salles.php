<?php 
require_once('../inc/init.inc.php');

//-----------------TRAITEMENT-----------------
// 1- On vérifie que le membre est admin :
if (!internauteEstConnecteEtEstAdmin()){
	header('location:../connexion.php');
	exit();
}

// 7- Suppression du produit :
if (isset($_GET['action']) && $_GET['action']=='suppression' && isset($_GET['id_salle'])){ // si les indices "action" et "id_produit", c'est que l'url est complète
	
	$resultat = executeRequete("SELECT photo FROM salle WHERE id_salle = :id_salle", array(':id_salle' => $_GET['id_salle']));
	
	if ($resultat->rowCount() == 1){
		// ici le produit existe
		$produit_a_supprimer = $resultat->fetch(PDO::FETCH_ASSOC); // pas de boucle car on a qu'un seul produit par id
		
		if(!empty($produit_a_supprimer['photo'])){ // si il y a une photo dans la BDD on peut supprimer la photo physique :
			$chemin_photo_a_supprimer = $_SERVER['DOCUMENT_ROOT'] . RACINE_SITE . $produit_a_supprimer['photo']; // chemin complet du fichier photo : 
			// C:/wamp64/www/PHP/08-site/photo/nomphoto.jpg
			
			if (file_exists($chemin_photo_a_supprimer))	unlink($chemin_photo_a_supprimer);	// si le fichier existe, on le supprime avec unlink()		
			
		}
		
		executeRequete("DELETE FROM salle WHERE id_salle = :id_salle", array(':id_salle'=> $_GET['id_salle']));
		$contenu .= '<div class="bg-success">Salle supprimée !</div>';
		
	} else {
		// ici le produit n'existe pas
		$contenu .= '<div class="bg-danger">Salle inexistante !</div>';
	}	$_GET['action']='affichage'; // afficher automatiquement le tableau des produits après suppression
}

// 4- Traitement du formulaire : enregistrement du produit :
if ($_POST){// si le formulaire est soumis ou posté
	
	//ici  il faudriat mettre tous les contrôles sur le formulaire, ce qu'on ne fait pas...
	
	if (!ctype_digit($_POST['cp']) && !empty($_POST['cp'])) {
		// si l'indice 'pseudo' n'existe pas, ou que sa longueur est <4 ou >20, on met un message d'erreur :
		$contenu .= '<div class="bg-danger">Veuillez rentrez un code postal (en chiffre entier).</div>';		
	}	
	
	if (!isset($_POST['cp']) || strlen($_POST['cp']) != 5 ) {
		// si l'indice 'pseudo' n'existe pas, ou que sa longueur est <4 ou >20, on met un message d'erreur :
		$contenu .= '<div class="bg-danger">Le code postal doit contenir 5 chiffre.</div>';		
	}

	if (!isset($_POST['titre']) || strlen($_POST['titre']) < 4 || strlen($_POST['titre']) > 20 ) {
		// si l'indice 'pseudo' n'existe pas, ou que sa longueur est <4 ou >20, on met un message d'erreur :
		$contenu .= '<div class="bg-danger">Le titre doit contenir entre 4 et 20 caractères.</div>';		
	}

	if (!isset($_POST['description']) || strlen($_POST['description']) < 20 || strlen($_POST['description']) > 250 ) {
		// si l'indice 'pseudo' n'existe pas, ou que sa longueur est <4 ou >20, on met un message d'erreur :
		$contenu .= '<div class="bg-danger">La description doit contenir entre 20 et 250 caractères.</div>';		
	}	
	
	if (!isset($_POST['adresse']) || strlen($_POST['adresse']) < 4 || strlen($_POST['adresse']) > 50 ) {
		// si l'indice 'pseudo' n'existe pas, ou que sa longueur est <4 ou >20, on met un message d'erreur :
		$contenu .= '<div class="bg-danger">L\'adresse doit contenir entre 4 et 50 caractères.</div>';		
	}	
	
	
	
	$photo_bdd=''; // contiendra le chemin de la photo en BDD
	
	// 5- Traitement de la photo :
	if (isset($_GET['action']) && $_GET['action'] == 'modification'){
		// si je modifie le produit, je prend la photo actuelle que je remets en BDD :
		$photo_bdd = $_POST['photo_actuelle'];
	}
	
	if (empty($_FILES['photo']['name']) && empty($photo_bdd) ){
		$contenu .= '<div class="bg-danger">Mettez une photo ce serai plus sympa :)</div>';
	}		
	
	// debug($_FILES);
	
	if (!empty($_FILES['photo']['name'])){ // Si name n'est pas vide c'est que l'on est en train d'uploader une photo
		$nom_photo = $_POST['titre'].'_'. $_FILES['photo']['name']; // On contitue le nom unique(la référence étant unique)du fichier photo qui sera uploadé sur notre serveur
		
		$photo_bdd = 'photo/' . $nom_photo; // chemin de la photo enregistrée en BDD ( exemple : photo/nomphoto.jpg)
		
		// debug($_SERVER['DOCUMENT_ROOT']);
		 $photo_physique = $_SERVER['DOCUMENT_ROOT']. RACINE_SITE . $photo_bdd; // On obtient le chemin complet pour enregistrer physiquement le fichier photo dans le dossier /photo/. $_SERVER['DOCUMENT_ROOT'] = localhost ou C:/wamp64/www. Ainsi on obtient un chemin du type :
		 // C:/wamp64/www/PHP/08-site/photo/nomphoto.jpg
		 
		copy($_FILES['photo']['tmp_name'], $photo_physique); // enregistre le fichier temporaire qui est dans $_FILES['photo']['tmp_name'] à l'endroit indiqué par $photo_physique
	}
	

	
	if(empty($contenu)){
		// Enregistrement du produit en BDD :
		executeRequete("REPLACE INTO salle VALUES(:id_salle, :titre, :description, :photo_bdd, :pays, :ville, :adresse, :cp, :capacite, :categorie)", 
						array(
							':id_salle' 	=> $_POST['id_salle'],
							':titre' 		=> $_POST['titre'],
							':description' 	=> $_POST['description'],
							':photo_bdd' 	=> $photo_bdd,
							':pays'			=> $_POST['pays'],
							':ville'		=> $_POST['ville'],
							':adresse' 		=> $_POST['adresse'],
							':cp' 			=> $_POST['cp'],
							':capacite' 	=> $_POST['capacite'],
							':categorie' 	=> $_POST['categorie']					
						));
		// Note : quand on ne spécifie pas les champs impactés par le REPLACE, il faut mettre dans VALUES tous les champs de la table exactement dans le même ordre que dans cette table
		$contenu .= '<div class="bg-success">La salle a bien été enregistrée.</div>';
		
		$_GET['action'] = 'affichage'; // on met unn indice "action" et une valeur "affichage dans $_GET pour forcer l'affichage du tableau HTML de tous les produits un peu plus bas (cf chapitre 6)
	}
}// fin du if ($_POST)


// 6- Affichage des produits sous forme de table HTML :
// si on demande l'affichage en GET :

	$resultat = executeRequete("SELECT * FROM salle"); // on obtient un objet PDOStatement non exploitable directement : il faudra donc faire un fetch dessus

	//$contenu .= '<h3> Affichage des salles</h3>';
	$contenu .= 'Nombre de salles dans la boutique : ' . $resultat->rowCount();
	$contenu .=  '<div id="target" style="overflow: scroll; height: 50vh;">';
	$contenu .= '<table class="table text-center">';
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
			foreach($ligne as $indice=>$info){
				if ($indice == 'photo'){
					$contenu .= '<td><img src="../'.$info.'" width="90" height="90" ></td>';
				} else {
					$contenu .= '<td>'.$info . '</td>';
				}
			}
			$contenu .= '<td>
							<a href="?action=modification&id_salle='.$ligne['id_salle'].'">Modifier </a>
							/
							<a href="?action=suppression&id_salle='.$ligne['id_salle'].'" onclick="return(confirm(\'Etes-vous certain de vouloir supprimer cette salle ? \'))" >Supprimer </a>
						</td>';
						
		$contenu .= '</tr>';
	}		
		
	$contenu .= '</table>';
	$contenu .= '</div>';
	









//-----------------AFFICHAGE------------------
require_once('../inc/haut.inc.php');
echo'<h1> SALLES</h1>';
// é- création des onglets "affichage" et "ajout" des produits :

echo $contenu;

// 3- Formulaire HTML : on affiche le formulaire uniquement en action "ajout" ou "modification" de produit :
if (isset($_GET['action']) && ($_GET['action'] == 'modification')) : // syntaxe en if() :...endif; utile quand on mélange beaucoup de HTML/PHP dans la condition


	// 8- Formulaire de modification de produit :
	if (isset($_GET['id_salle'])){ // si id_produit est dans l'url c'est que l'on modifie un produit existant : on requête en BDD les infos du produit à afficher :
		$resultat = executeRequete("SELECT * FROM salle WHERE id_salle = :id_salle", array(':id_salle' => $_GET['id_salle']));
		$produit_actuel = $resultat->fetch(PDO::FETCH_ASSOC); // pas de while car un seul produit
		//debug ($produit_actuel);
		
	}

endif; 
?>	
	<h3>Ajout ou modification d'une salle</h3>
	<form method="post" action="" enctype="multipart/form-data"><!-- multipart/form-data spécifie que le formulaire envoie des données texte(champs du formulaire) et des données binaires (=fichier) -->
	
	<div class="row">
	<div class="col-sm-4">
		<input type="hidden" id="id_salle" name="id_salle" value="<?php echo $produit_actuel['id_salle']?? 0; ?>"> <!-- champ caché pour ne pas pouvoir le modifier. Il est utile pour connaître l'id du produit que l'on est en train de modifier -->
		<label for="titre">Titre</label><br>
		<input type="text" id="titre" name="titre" value="<?php echo $produit_actuel['titre']?? ''; ?>"><br><br>
		
		<label for="description">Description</label><br>
		<textarea id="description" name="description"><?php echo $produit_actuel['description']?? ''; ?></textarea><br><br>
		
		<label for ="photo">Photo</label><br>
		<input type="file" id="photo" name="photo"><br><br> <!-- ne pas oublier enctype="multipart/form-data" dans la balise <form> -->
		
		<!--9- Modification de la photo : --> 
		<?php
		if (isset($produit_actuel['photo'])) {
			// En cas de modification, on affiche la photo actuellement en BDD :
			echo '<i>Vous pouvez uploader une nouvelle photo.</i><br>';
			echo '<p>Photo actuelle : </p>';
			echo '<img src="../'. $produit_actuel['photo'] .'" width="90" height="90" ><br>';
			echo '<input type="hidden" name="photo_actuelle" value="'. $produit_actuel['photo'] .'"><br>'; // renseigne $_POST['photo_actuelle'] qui remplace en BDD l'ancienne photo
		}
		?>
		</div>
		<div class="col-sm-4">
		<label for="pays">Pays</label>
		<select name="pays">
			<option value="France" <?php if(isset($produit_actuel['pays']) && $produit_actuel['pays'] == 'France') echo 'selected'; ?> >France</option>
		</select><br><br>
		
		<label for="ville">Ville</label>
		<select name="ville">
			<option value="Paris" <?php if(isset($produit_actuel['ville']) && $produit_actuel['ville'] == 'Paris') echo 'selected'; ?> >Paris</option>
			<option value="Marseille" <?php if(isset($produit_actuel['ville']) && $produit_actuel['ville'] == 'Marseille') echo 'selected'; ?> >Marseille</option>
			<option value="Lyon" <?php if(isset($produit_actuel['ville']) && $produit_actuel['ville'] == 'Lyon') echo 'selected'; ?> >Lyon</option>
		</select><br><br>
		
		<label for="adresse">Adresse</label><br>
		<input type="text" id="adresse" name="adresse" value="<?php echo $produit_actuel['adresse']?? ''; ?>"><br><br>		
		
		<label for="cp">Code postal</label><br>
		<input type="text" id="cp" name="cp" value="<?php echo $produit_actuel['cp']?? ''; ?>"><br><br>
		
		</div>
		<label for="capacite">Capacite</label>
		<select name="capacite">
			<option value="10" <?php if(isset($produit_actuel['capacite']) && $produit_actuel['capacite'] == '10') echo 'selected'; ?> >10</option>
			<option value="20" <?php if(isset($produit_actuel['capacite']) && $produit_actuel['capacite'] == '20') echo 'selected'; ?> >20</option>
			<option value="30" <?php if(isset($produit_actuel['capacite']) && $produit_actuel['capacite'] == '30') echo 'selected'; ?> >30</option>
			<option value="40" <?php if(isset($produit_actuel['capacite']) && $produit_actuel['capacite'] == '40') echo 'selected'; ?> >40</option>
			<option value="50" <?php if(isset($produit_actuel['capacite']) && $produit_actuel['capacite'] == '50') echo 'selected'; ?> >50</option>
			<option value="60" <?php if(isset($produit_actuel['capacite']) && $produit_actuel['capacite'] == '60') echo 'selected'; ?> >60</option>
			<option value="70" <?php if(isset($produit_actuel['capacite']) && $produit_actuel['capacite'] == '70') echo 'selected'; ?> >70</option>
			<option value="80" <?php if(isset($produit_actuel['capacite']) && $produit_actuel['capacite'] == '80') echo 'selected'; ?> >80</option>
			<option value="90" <?php if(isset($produit_actuel['capacite']) && $produit_actuel['capacite'] == '90') echo 'selected'; ?> >90</option>
			<option value="100" <?php if(isset($produit_actuel['capacite']) && $produit_actuel['capacite'] == '100') echo 'selected'; ?> >100</option>
			<option value="110" <?php if(isset($produit_actuel['capacite']) && $produit_actuel['capacite'] == '110') echo 'selected'; ?> >110</option>
			<option value="120" <?php if(isset($produit_actuel['capacite']) && $produit_actuel['capacite'] == '120') echo 'selected'; ?> >120</option>
		</select><br><br>

		<label for="categorie">Catégorie</label>
		<select name="categorie">
			<option value="Réunion" <?php if(isset($produit_actuel['categorie']) && $produit_actuel['categorie'] == 'Réunion') echo 'selected'; ?> >Réunion</option>
			<option value="Bureau" <?php if(isset($produit_actuel['categorie']) && $produit_actuel['categorie'] == 'Bureau') echo 'selected'; ?> >Bureau</option>
			<option value="Formation" <?php if(isset($produit_actuel['categorie']) && $produit_actuel['categorie'] == 'Formation') echo 'selected'; ?> >Formation</option>
		</select><br><br>
								
		<input type="submit" value="valider" class="btn">
	</div >
		
	</form>
	
<?php	
// Ce endif ferme le if du début du chapitre 3
require_once('../inc/bas.inc.php');
