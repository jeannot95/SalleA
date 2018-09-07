<?php
//-------------------------------------------
// EXERCICE
//-------------------------------------------

/*   Vous allez créer la page de gestion des membres dans le back-office :
	 1- Seul l’administrateur doit avoir accès à cette page. Les membres classiques seront redirigés vers la page connexion.php  
	 2- Afficher dans cette page tous les membres inscrits sur le site sous forme de table HTML, avec toutes les infos du membre sauf son mot de passe.  
     3- Dans cette même page, ajoutez la possibilité à l’administrateur de pouvoir supprimer un membre inscrit au site (même s'il a passé des commandes), sauf lui-même ! 
	 4- Donner la possibilité à l'administrateur de modifier le statut des membres pour en faire un admin ou un membre, sauf lui-même.
*/	

require_once("../inc/init.inc.php");

// 1- Vérification si Admin :
if(!internauteEstConnecteEtEstAdmin())
{
	header("location:../connexion.php");
	exit();
}

// 3- Suppression d'un membre :
if(isset($_GET['action']) && $_GET['action'] == "supprimer_membre" && isset($_GET['id_membre']))
{	// on ne peut pas supprimer son propre profil :
	if ($_SESSION['membre']['id_membre'] != $_GET['id_membre']) {
		executeRequete("DELETE FROM membre WHERE id_membre=:id_membre", array(':id_membre' => $_GET['id_membre']));
	} else {
		$contenu .= '<div class="bg-danger">Vous ne pouvez pas supprimer votre propre profil ! </div>';
	}
	
}
//debug($_SESSION);

if ($_POST){// si le formulaire est soumis ou posté
		
		// Validation du formulaire :
	if (!isset($_POST['pseudo']) || strlen($_POST['pseudo']) < 4 || strlen($_POST['pseudo']) > 20 ) {
		// si l'indice 'pseudo' n'existe pas, ou que sa longueur est <4 ou >20, on met un message d'erreur :
		$contenu .= '<div class="bg-danger">Le pseudo doit contenir entre 4 et 20 caractères.</div>';		
	}
	//if (!isset($_POST['mdp']) || strlen($_POST['mdp']) < 4 || strlen($_POST['mdp']) > 20 ) {		
	//	$contenu .= '<div class="bg-danger">Le mot de passe doit contenir entre 4 et 20 caractères.</div>';		
	//}
	if (!isset($_POST['nom']) || strlen($_POST['nom']) < 4 || strlen($_POST['nom']) > 20 ) {		
		$contenu .= '<div class="bg-danger">Le nom doit contenir entre 4 et 20 caractères.</div>';		
	}
	if (!isset($_POST['prenom']) || strlen($_POST['prenom']) < 4 || strlen($_POST['prenom']) > 20 ) {		
		$contenu .= '<div class="bg-danger">Le prénom doit contenir entre 4 et 20 caractères.</div>';		
	}
	if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
		// filter_var permet ici de valider le format de type email : retourne true si c'est ok, sinon false. Note : ici on vérifie la négation, qu'il ne s'agit pas d'un email(d'où le "!")
		$contenu .= '<div class="bg-danger">Email incorrect !</div>';		
	}

	if (!isset($_POST['civilite']) || ($_POST['civilite'] != 'm' && $_POST['civilite'] != 'f' )) {
		$contenu .= '<div class="bg-danger">Civilité incorrecte !</div>';
	}
	
	if (isset($_POST['statut']) && $_POST['statut'] != '1' && $_GET['id_membre'] == $_SESSION['membre']['id_membre'] ) {
		$contenu .= '<div class="bg-danger">Vous ne pouvez pas changer votre propre statut!!!</div>';
	}	
	
	// Si pas d'erreur dans $contenu, on vérifie l'unicité du pseudo en base de données puis on vérifie l'inscription :
	if (empty($contenu)){ // si $contenu est vide, c'est qu'il n'y a pas d'erreur
		$membre = executeRequete("SELECT * FROM membre WHERE pseudo= :pseudo AND NOT id_membre = :id_membre", array(':pseudo' => $_POST['pseudo'], ':id_membre' => $_POST['id_membre'])); // on fait cette requête pour vérifier la disponibilité du pseudo
	 //debug($membre);
	//$m = $membre->fetch(PDO::FETCH_ASSOC);
	//debug($m);
		if ($membre->rowCount() > 0 /* && $_POST['pseudo'] != $_POST['pseudo'] */) { // si la requête retourne au moins 1 ligne, c'est que le pseudo existe déjà
			$contenu .= '<div class="bg-danger">Pseudo indisponible : veuillez en choisir un autre !</div>'; 
		} else { // sinon on peut inscrire le membre en bdd
		//$mdp = md5($_POST['mdp']);	
		
	// Enregistrement du produit en BDD :
	executeRequete("UPDATE membre SET pseudo = :pseudo, nom = :nom, prenom = :prenom, email = :email, civilite = :civilite, statut = :statut, date_enregistrement = NOW() WHERE id_membre = :id_membre", 
					array(
						':id_membre' 			=> $_POST['id_membre'],
						':pseudo' 				=> $_POST['pseudo'],
						//':mdp' 					=> $mdp,
						':nom' 					=> $_POST['nom'],
						':prenom'				=> $_POST['prenom'],
						':email'				=> $_POST['email'],
						':civilite' 			=> $_POST['civilite'],
						':statut' 				=> $_POST['statut']											
					));
	// Note : quand on ne spécifie pas les champs impactés par le REPLACE, il faut mettre dans VALUES tous les champs de la table exactement dans le même ordre que dans cette table
	$contenu .= '<div class="bg-success">Le membre a bien été enregistrée.</div>';
	
	//$_GET['action'] = 'affichage'; // on met unn indice "action" et une valeur "affichage dans $_GET pour forcer l'affichage du tableau HTML de tous les produits un peu plus bas (cf chapitre 6)
	}}		
}//

//debug($_POST);
// 4- Modification statut membre :
/*if(isset($_GET['action']) && $_GET['action'] == "modifier")
{
	if ($_GET['id_membre'] != $_SESSION['membre']['id_membre']) {
		
		$resultat = executeRequete("SELECT * FROM membre WHERE id_membre = :id_membre", array(':id_membre' => $_GET['id_membre']));
		$membre_actuel = $resultat->fetch(PDO::FETCH_ASSOC);
	} else {
		$contenu .= '<div class="bg-danger">Vous ne pouvez pas modifier votre propre profil ! </div>';	
	}
}*/


// 2- Préparation de l'affichage :
$resultat = executeRequete("SELECT * FROM membre");
$contenu .= '<h3> Membres inscrit </h3>';
$contenu .=  "Nombre de membre(s) : " . $resultat->rowCount();
$contenu .=  '<div id="target" style="overflow: scroll; height: 40vh;">';
$contenu .=  '<table class="table text-center"> <tr>';
		// Affichage des entêtes :
		for($i = 0; $i < $resultat->columnCount(); $i++)
		{
			$colonne = $resultat->getColumnMeta($i);  // Retourne les métadonnées pour une colonne dans le jeu de résultats $resultat sous forme de tableau
			//var_dump($colonne);  // on y trouve l'indice "name"
			if ( $colonne['name'] != 'mdp') 
			{   if($colonne['name'] == 'civilite')
				{
					$contenu .= '<th class="text-center">azdahige</th>';
				}
				else
				{
					$contenu .= '<th class="text-center">' . $colonne['name'] . '</th>';
				}
			}
			
		}
		
		$contenu .=  '<th> Supprimer </th>';
		$contenu .=  '<th> Modifier </th>';
		$contenu .=  '</tr>';

		// Affichage des lignes :
		while ($membre = $resultat->fetch(PDO::FETCH_ASSOC))
		{
			$contenu .=  '<tr>';
				foreach ($membre as $indice => $information)
				{
					if ($indice != 'mdp') $contenu .=  '<td>' . $information . '</td>';
				}
				$contenu .=  '<td><a href="?action=supprimer_membre&id_membre=' . $membre['id_membre'] . '" onclick="return(confirm(\'Etes-vous sûr de vouloir supprimer ce membre?\'));"> X </a></td>';
				$contenu .=  '<td><a href="?action=modifier&id_membre=' . $membre['id_membre'].'"> modifier </a></td>';
			$contenu .=  '</tr>';
		}
$contenu .=  '</table>';
$contenu .=  '</div>';


//-------------------------------------------------- Affichage ---------------------------------------------------------//
require_once("../inc/haut.inc.php");
echo $contenu;

if (isset($_GET['action']) && ($_GET['action'] == 'modifier')) { // syntaxe en if() :...endif; utile quand on mélange beaucoup de HTML/PHP dans la condition


	// 8- Formulaire de modification de produit :
	//if (isset($_GET['id_membre']) && $_GET['id_membre'] == $_SESSION['membre']['id_membre']){ // si id_produit est dans l'url c'est que l'on modifie un produit existant : on requête en BDD les infos du produit à afficher :
		$resultat = executeRequete("SELECT * FROM membre WHERE id_membre = :id_membre", array(':id_membre' => $_GET['id_membre']));
		$membre_actuel = $resultat->fetch(PDO::FETCH_ASSOC); // pas de while car un seul produit
		//debug ($produit_actuel);
		
	//} else if (isset($_GET['id_membre']) && $_GET['id_membre'] !== $_SESSION['membre']['id_membre']){
	//	$contenu .= '<div class="bg-danger">Vous ne pouvez pas modifier le profil d\'un autre membre! </div>';}

}; 


?>	
	<h3>Modification d'un membre</h3>
	<form method="post" action="" enctype="multipart/form-data"><!-- multipart/form-data spécifie que le formulaire envoie des données texte(champs du formulaire) et des données binaires (=fichier) -->
	<div class="col-sm-4">
		<input type="hidden" id="id_membre" name="id_membre" value="<?php echo $membre_actuel['id_membre']?? 0; ?>"> <!-- champ caché pour ne pas pouvoir le modifier. Il est utile pour connaître l'id du produit que l'on est en train de modifier -->
		<label for="pseudo">Pseudo</label><br>
		<input type="text" id="pseudo" name="pseudo" value="<?php echo $membre_actuel['pseudo']?? ''; ?>"><br><br>
		
		<!-- <label for="mdp">Mot de passe</label><br>
		<input type="password" id="mdp" name="mdp" value="<?php //echo $membre_actuel['mdp']?? ''; ?>"><br><br> -->
		
		<label for="nom">Nom</label><br>
		<input type="text" id="nom" name="nom" value="<?php echo $membre_actuel['nom']?? ''; ?>"><br><br>

		<label for="prenom">Prénom</label><br>
		<input type="text" id="prenom" name="prenom" value="<?php echo $membre_actuel['prenom']?? ''; ?>"><br><br>
	</div>	
	<div class="col-sm-4">			
		<label for="email">Email</label><br>
		<input type="email" id="email" name="email" value="<?php echo $membre_actuel['email']?? ''; ?>"><br><br>
		
		<label for="civilite">Civilité</label>
		<select name="civilite">
			<option value="m" <?php if(isset($membre_actuel['civilite']) && $membre_actuel['civilite'] == 'm') echo 'selected'; ?> >Homme</option>
			<option value="f" <?php if(isset($membre_actuel['civilite']) && $membre_actuel['civilite'] == 'f') echo 'selected'; ?> >Femme</option>
		</select><br><br>
		
		<label for="statut">Statut</label>
		<select name="statut">
			<option value="0" <?php if(isset($membre_actuel['statut']) && $membre_actuel['statut'] == '0') echo 'selected'; ?> >Client</option>
			<option value="1" <?php if(isset($membre_actuel['statut']) && $membre_actuel['statut'] == '1') echo 'selected'; ?> >Admin</option>
		</select><br><br>		
	</div>	
								
		<input type="submit" value="enregistrer" class="btn">		
	</form>
	
<?php	




require_once("../inc/bas.inc.php");



