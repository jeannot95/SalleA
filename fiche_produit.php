<?php 
require_once('inc/init.inc.php');

//--------------------TRAITEMENT-----------------
// variables d'affichage :
$panier ='';
$suggestion = '';
$avis = '';



// 1- Contrôle de l'existence du produit demandé (un produit en favoris a pu être supprimé...) :
if (isset($_GET['id_produit'])){
	// si un produit en particulier est demandé :
	$resultat = executeRequete("SELECT p.id_produit, s.photo , s.id_salle, s.titre, p.prix, s.description, p.date_arrivee, p.date_depart, a.note,s.categorie, s.capacite, s.adresse, s.cp, s.ville, p.etat FROM produit p LEFT JOIN salle s ON p.id_salle=s.id_salle LEFT JOIN avis a ON s.id_salle=a.id_salle WHERE id_produit= :id_produit", array(':id_produit'=> $_GET['id_produit']));
	
	// petit rappel : $resultat est un objet issu de la classe PDPStatement. Par conséquent, le jeu de résultats qu'il contient n'est pas exploitable directement. C'est pourquoi on doit faire un fetch dessus !
	
	if ($resultat->rowCount() == 0){
		// s'il n'y a pas de ligne dans $resultat c'est qu'il n'y a pas de produit de cet id_produit en BDD : on redirige donc l'internaute vers la boutique :
		header('location:index.php');
		exit();
	}
	
	// 2-Mise en forme des infos du produit :
	$produit = $resultat->fetch(PDO::FETCH_ASSOC); // pas de while car qu'un seul produit par id_produit
	extract($produit); // crée des variables nommées comme des indices de l'array et qui prennent leur valeur.
	//debug($produit);
	
	if (internauteEstConnecte()){
	
	if ($etat == 'libre'){
		// si stock positif, on met le bouton ajout au panier :
		$panier .= '<form method="post" action=""onsubmit="return confirm(\'Confirmez-vous votre réservation?\');">';
			// champs caché pour envoyé l'id_produit à panier.php :
			$panier .= '<input type="hidden" name="id_produit" value="'.$id_produit.'">';
			
			// Sélecteur de quantité :

			
			$panier .= '<input type="submit" name ="commander" value="Commander" class="btn">';
			
			
		$panier .= '</form>';
	} else {
		// si stock nul :
		$panier .='<p>Salle déjà louée !</p>';
	}
	}	else $panier .= '<a href="connexion.php">connectez-vous</a>';
} // fin du if (isset($_GET['id_produit']))
	else { // si id_produit n'existe pas dans l'url, donc dans $_GET : 
	header('location:index.php');
	exit();	
} 


// 4- Affichage de la modal de confirmation d'ajout au panier :
if (isset($_POST['commander'])){ // si l'indice "statut_produit" est dans l'url et qu'il vaut "ajoute" c'est que le produit a bien été ajouté au panier(cf panier.php) :

			executeRequete(
			"REPLACE INTO commande(id_membre, id_produit, date_enregistrement)
			VALUES (:id_membre, :id_produit, NOW())", 
			array(
			':id_membre' => $_SESSION['membre']['id_membre'],
			':id_produit' => $_GET['id_produit']
			)
			); 			
			
			executeRequete(
			"	 UPDATE produit SET etat = 'reservation' WHERE id_produit= :id_produit", 
			array(
			':id_produit' => $_GET['id_produit']
			)
			); 			
			
			$contenu .= '<div class="bg-success">Votre commande a bien été enregistrée.</div>';


$contenu_gauche .= '<div class="modal fade" id="myModal" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">Le produit a bien été commandé !</h4>
								</div>

								<div class="modal-body">
									<p><a href="profil.php">Voir la commande</a></p>
									<p><a href="index.php">Continuer votre visite</a></p>
								</div>
							</div>
						</div>
					</div>';	
}


//------
// Exercice : suggestion de produits
// Afficher 2 produits(photo et titre) aléatoirement appartenant à la catégorie du produit affiché dans la fiche_produit. Ces produits doivent être différents du produit affiché. La photo est cliquable et mène à la fiche du produit. Utilisez la variable $suggestion pour afficher le résultat.
$resultat = executeRequete("SELECT p.id_produit, s.photo , s.titre, p.prix, s.description, p.date_arrivee, p.date_depart, a.note,s.categorie, s.capacite, s.adresse, s.cp, s.ville FROM produit p LEFT JOIN salle s ON p.id_salle=s.id_salle LEFT JOIN avis a ON s.id_salle=a.id_salle WHERE p.etat= 'libre' AND id_produit!= :id_produit AND categorie = :categorie AND date_arrivee >= NOW() GROUP BY id_produit ORDER BY RAND() LIMIT 0,4", array(
':id_produit'=> $_GET['id_produit'],
':categorie'=> $categorie
));


while ($okay = $resultat->fetch(PDO::FETCH_ASSOC)){
$suggestion .= '<div class="col-sm-3">';
$suggestion .=  '<a href="fiche_produit.php?id_produit='.$okay['id_produit'].'"><img src="'.$okay['photo'].'" class="img-responsive" ></a>';
$suggestion .=  '<h4>'.$okay['titre']. '</h4>';
$suggestion .= '</div>';
}

//$suggestion = '<img src="'.$okay['photo'].'" width="100" height="100" >'.$okay['titre'];

//Affichage lien déposer un avis
if (internauteEstConnecte()){
	$avis .= '<a href="?action=deposer_avis&id_produit=' . $produit['id_produit'] . '&id_salle=' . $produit['id_salle'] . '">Déposer une note ou un commentaire</a>';
}	else $avis .= '<a href="connexion.php">connectez-vous</a>';
//debug($_SESSION);
if (isset($_GET['action']) && ($_GET['action'] == "deposer_avis")){
	$l = executeRequete("SELECT id_membre, id_salle FROM avis WHERE :id_membre = id_membre AND :id_salle = id_salle",array(':id_membre'=> $_SESSION['membre']['id_membre'],
	':id_salle' => $_GET['id_salle']));
	//debug($l->rowCount());
	if($l->rowCount() == 0 ){
		if (isset($_POST['ajout_avis'])) {
			
					executeRequete(
					"INSERT INTO avis(id_membre, id_salle, commentaire, note, date_enregistrement)
					VALUES (:id_membre, :id_salle, :commentaire, :note, NOW())", 
					array(
					':id_membre' => $_SESSION['membre']['id_membre'],
					':id_salle' => $id_salle,
					':commentaire' => $_POST['commentaire'],
					':note' => $_POST['note']
					)
					); 			
					
					
					$contenu .= '<div class="bg-success">Votre avis a bien été enregistrée.</div>';
		}
	
	$contenu_gauche .= '
					<div class="modal fade" id="myModal2" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">Déposez votre avis !</h4>
									        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
											  <span aria-hidden="true">&times;</span>
											</button>
								</div>
								
								<div class="modal-body">
									<form method="post" action="" enctype="multipart/form-data" id="form3">
										<input type="hidden" id="id_membre" name="id_membre" value="<?php echo '.$titre.' ; ?>">										
											<label for="note">Note</label>
												<select name="note" id="note">
																										
													<option value="1" >1</option>
													<option value="2" >2</option>
													<option value="3" >3</option>
													<option value="4" >4</option>
													<option value="5" >5</option>
												</select><br><br>
											<label for="commentaire">Commentaire</label><br>
												<textarea id="commentaire" name="commentaire"> </textarea><br><br>
												<input type="submit" value="envoyer" name="ajout_avis" class="btn" id="sub" >												
									</form>
								</div>
							</div>
						</div>
					</div>';
	} else{$contenu .= '<div class="bg-danger">Vous avez déjà déposez un avis sur cette salle !.</div>';}
}




//-------------------AFFICHAGE------------------
require_once('inc/haut.inc.php');
echo $contenu_gauche; // affiche la modal de confirmation d'ajout au panier
echo $contenu; // affiche la modal de confirmation d'ajout au panier
?>
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo $titre; ?></h1>
		</div>
		<div class="col-md-8">
			<img class="img-responsive" width="100%" src="<?php echo $photo; ?>">
		</div>
		<div class="col-md-4">
			<h3>Description :</h3>
			<p><?php echo $description ?></p>
			
			<h3>Détails</h3>
			<ul>
				<li>Catégorie : <?php echo $categorie; ?></li>
				<li>Adresse : <?php echo $adresse.' , '.$cp.' , '.$ville ; ?></li>
				<li>Capacité : <?php echo $capacite; ?> pers</li>
				<li>Arrivée : <?php echo $date_arrivee; ?> </li>
				<li>Départ : <?php echo $date_depart; ?> </li>
			</ul>
			<p class="lead">€ Tarif : <?php echo $prix; ?> €</p>
			<?php echo $panier; ?>
			<br>
			<iframe src="https://www.google.com/maps/embed/v1/place?key= AIzaSyDAMN8GzF4qd27_kym1YkoVl9rv7x5ApuE&q=<?= $adresse; ?>+<?= $ville; ?>" allowfullscreen></iframe>

		</div>
	</div> <!-- .row -->
	<div class="row">
		<div class="col-lg-12">
		<form>
		
		</form>
	</div>
	<!-- Exercice -->
	<div class="row">
		<div class="col-lg-12">
			<h3 class="page-header">Suggestion de produits</h3>
		</div>
		
		<?php echo $suggestion; ?>
		
	</div>
	<hr>
		<div class="col-lg-3 col-md-9 col-sm-7">
			<?php echo $avis; ?>
		</div>
		<div class="col-lg-7">
		</div>
		<a href="index.php" >Retour vers le catalogue</a>

	<script>
		$(function(){

			$("#myModal").modal("show");
			$("#myModal2").modal("show");

			
		});

	</script>


	
		<script src=" inc/js/jquery.barrating.min.js"></script>
	<script type="text/javascript">
		$(function() {
			$('#note').barrating({
        theme: 'fontawesome-stars'
			});
		});
	</script>
<?php
require_once('inc/bas.inc.php');

















