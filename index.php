<?php 
require_once('inc/init.inc.php');
$contenu_avis='';
//--------------------TRAITEMENT-----------------
// 1- Affichage des catégories de produits :
/*
$resultat = executeRequete("SELECT DISTINCT categorie FROM salle");

$contenu_gauche .= '<p class ="lead">SalleA</p>';
$contenu_gauche .= '<div class ="list-group">';
$contenu_gauche .= '<form>';
$contenu_gauche .= '<a href="?categorie=all" class="list-group-item">Catégorie</a>';
	
	while($cat = $resultat->fetch(PDO::FETCH_ASSOC)) {
		// debug($cat);
		$contenu_gauche .= '<a href="?categorie='.$cat['categorie'].'" class="list-group-item">'.$cat['categorie'].'</a>'; 
	}


$contenu_gauche .= '</div>';

$resultat = executeRequete("SELECT DISTINCT ville FROM salle");
$contenu_gauche .= '<div class ="list-group">';
$contenu_gauche .= '<a href="?ville=all" class="list-group-item">Ville</a>';
	
	while($vil = $resultat->fetch(PDO::FETCH_ASSOC)) {
		// debug($cat);
		$contenu_gauche .= '<a href="?ville='.$vil['ville'].'" class="list-group-item">'.$vil['ville'].'</a>'; 
	}


$contenu_gauche .= '</div>';



$resultat = executeRequete("SELECT DISTINCT capacite FROM salle");
$contenu_gauche .= '<div class ="list-group">';
$contenu_gauche .= '<a href="?categorie=all" class="list-group-item">Capacite</a>';
$contenu_gauche .= 		'<select name="capacite">
			<option value="10" >10</option>
			<option value="20" >20</option>
			<option value="30" >30</option>
			<option value="40" >40</option>
			<option value="50" >50</option>
			<option value="60" >60</option>
			<option value="70" >70</option>
			<option value="80" >80</option>
			<option value="90" >90</option>
			<option value="100" >100</option>
			<option value="110" >110</option>
			<option value="120" >120</option>
		</select><br><br> ';
	


$contenu_gauche .= '</div>';

$contenu_gauche .= '<div class ="list-group">';
$contenu_gauche .= '<a href="?categorie=all" class="list-group-item">Prix</a><br>';
$contenu_gauche .= '</div>';

$contenu_gauche .= '<div class ="list-group">';
$contenu_gauche .= '<a href="?categorie=all" class="list-group-item">Période</a><br>';
$contenu_gauche .= '</div>';
$contenu_gauche .= '</form>';



// 2- Affichage des produits selon la catégorie choisie :
 if (isset($_GET['categorie']) && $_GET['categorie'] != 'all'){
	// si on a cliqué sur une catégorie différente de "all", on sélectoinne en BDD les produits de cette catégorie :
	$donnees = executeRequete("SELECT p.id_produit, s.photo , s.titre, p.prix, s.description, p.date_arrivee, p.date_depart,s.id_salle, ROUND(AVG(a.note),1)AS note FROM produit p JOIN salle s ON s.id_salle=p.id_salle LEFT JOIN avis a ON s.id_salle=a.id_salle WHERE p.etat= 'libre' AND s.categorie= :categorie GROUP BY id_produit ", array(':categorie' => $_GET['categorie']));
		
} 
	else if (isset($_GET['ville']) && $_GET['ville'] != 'all'){
	// si on a cliqué sur une catégorie différente de "all", on sélectoinne en BDD les produits de cette catégorie :
	$donnees = executeRequete("SELECT p.id_produit, s.photo , s.titre, p.prix, s.description, p.date_arrivee, p.date_depart,s.id_salle, ROUND(AVG(a.note),1)AS note FROM produit p JOIN salle s ON s.id_salle=p.id_salle LEFT JOIN avis a ON s.id_salle=a.id_salle WHERE p.etat= 'libre' AND s.ville= :ville GROUP BY id_produit ", array(':ville' => $_GET['ville']));
		
} 


else {
	// sinon si catégorie n'existe pas ou qu'elle est égale à "all", on sélectionne tous les produits :
	$donnees = executeRequete("SELECT p.id_produit, s.photo , s.titre, p.prix, s.description, p.date_arrivee, p.date_depart,s.id_salle, ROUND(AVG(a.note),1)AS note FROM produit p JOIN salle s ON s.id_salle=p.id_salle LEFT JOIN avis a ON s.id_salle=a.id_salle WHERE p.etat= 'libre' GROUP BY id_produit  
	");
	
}
*/
			
/* while($produit= $donnees->fetch(PDO::FETCH_ASSOC)){
	 //debug($produit);
	$contenu_droite .= '<div class="col-sm-4">';
		$contenu_droite .= '<div class="thumbnail">';
			// image cliquable :
			$contenu_droite .= '<a href="fiche_produit.php?id_produit='. $produit['id_produit'] .'"><img src="'.$produit['photo'].'" width="100" height="100"></a>';
		//debug($produit);
			// les infos du produit :
			$contenu_droite .= '<div class="caption">';
				$contenu_droite .='<h4 class="pull-right">'.$produit['prix'].' €</h4>';
				$contenu_droite .='<h4>'.$produit['titre'].'</h4>';
				//$contenu_droite .='<p>'.$produit['description'].' </p>';
				$contenu_droite .='<p>'.$produit['date_arrivee'].' au '. $produit['date_depart'].'</p>';
				
				if($produit['note']>0){	//$contenu_droite .= '<p>'.$produit['note']*20 .'</p>';
				$contenu_droite .= '<a class="etoile" href=""><div class="star-ratings-css" title="'.$produit['note'].'/5">
					<div class="star-ratings-css-top" style="width: '.$produit['note']*20 .'%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
					<div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
				</div></a>';}
				
				//$contenu_droite .='<p>'.$produit['note'].' </p>';
		
			$contenu_droite .= '</div>';
		$contenu_droite .= '</div>'; // .thumbnail
	$contenu_droite .= '</div>'; // .col-sm-4
	}
*/
	
	if(isset($_GET['action']) && $_GET['action']=='avis' && isset($_GET['id_salle'])){
	
			$req= executeRequete("SELECT *,m.pseudo FROM avis a LEFT JOIN membre m ON m.id_membre = a.id_membre WHERE id_salle = :id_salle",array(':id_salle' => $_GET['id_salle']));
					$contenu_avis .= '<div class="modal fade" id="myModal" role="dialog">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<h4 class="modal-title">Liste des avis</h4>
														<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Close</button>
													</div>

													<div class="modal-body"> ';	
			if ($req->rowCount() > 0){
				while($produit = $req->fetch(PDO::FETCH_ASSOC)){
					

								$contenu_avis .=		'<div class="star-ratings-css">
															<div class="star-ratings-css-top" style="width: '.$produit['note']*20 .'%"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
															<div class="star-ratings-css-bottom"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
														</div>
														<div>'. $produit['pseudo'] .' le '.$produit['date_enregistrement'].' </div>
														<div><em>'. $produit['commentaire'] .'</em> </div>
														';		
						
				}	
			}		$contenu_avis .='	</div>
												</div>
											</div>
										</div>';
		}	else {
			
									
	}   
echo $contenu_avis;  
//-------------------AFFICHAGE------------------
require_once('inc/haut.inc.php');
?>
	<div class="row">
		<div class="col-md-3">
			<?php echo $contenu_gauche; ?>
			
			
			
		<form method="post" action="" id="form2">
			<label>Catégories</label><br>
				<input type="checkbox" name="categorie[]" value="reunion">Réunion<br>
				<input type="checkbox" name="categorie[]" value="bureau">Bureau<br>
				<input type="checkbox" name="categorie[]" value="formation">Formation<br><br>
			<label>Villes</label><br>
				<input type="checkbox" name="ville[]" value="paris">Paris<br>
				<input type="checkbox" name="ville[]" value="lyon">Lyon<br>
				<input type="checkbox" name="ville[]" value="marseille">Marseilles<br><br>
			<label>Capacité min</label><br>
				<select name="capacite">
					<option  value="10" >10 pers.</option>
					<option value="20" >20 pers.</option>
					<option  value="30" >30 pers.</option>
					<option  value="40" >40 pers.</option>
					<option  value="50" >50 pers.</option>
					<option value="60" >60 pers.</option>
					<option value="70" >70 pers.</option>
					<option  value="80" >80 pers.</option>
					<option value="90" >90 pers.</option>
					<option  value="100" >100 pers.</option>
					<option  value="110" >110 pers.</option>
					<option  value="120" >120 pers.</option>
				</select><br><br>
					<label>Prix max</label><br>
				<select name="prix">
					<option  value="1200" >1200 €</option>
					<option  value="1100" >1100 €</option>
					<option  value="1000" >1000 €</option>
					<option value="900" >900 €</option>
					<option  value="800" >800 €</option>
					<option value="700" >700 €</option>
					<option value="600" >600 €</option>
					<option  value="500" >500 €</option>
					<option  value="400" >400 €</option>
					<option  value="300" >300 €</option>
					<option value="200" >200 €</option>
					<option  value="100" >100 €</option>
				</select><br><br>
				<label>Date d'arrivée</label><br>
				<input type="text" name="date_arrivee" id="date_arrivee" value="<?php echo date("Y/m/d 09:00" ); ?>"><br><br>
				<label>Date de départ</label><br>
				<input type="text" name="date_depart" id="date_depart" value="<?php echo date("Y/m/d 19:00", strtotime("+3 months") ); ?>"><br><br>
				<label id="nombre"></label><br>
		</form>
			
			
			
			
			
			
		
		</div>
		
		<div class="col-md-9">
			<div class="row">
						<div id="selection">
			</div>
			</div>
		</div>


	</div>
	<script>
		$(function(){
			$("#myModal").modal("show");
		
		});

	</script>

<?php 
require_once('inc/bas.inc.php');





















