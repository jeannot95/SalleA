<?php 
require_once('inc/init.inc.php');

//------------------TRAITEMENT PHP--------------------
// 2- déconnexion de l'internaute :
if (isset($_GET['action']) && $_GET['action'] == 'deconnexion'){ // si l'internaute demande une déconnexion
	session_destroy(); // on supprime la session à la fin de ce script
}

// 3- Vérification internaute déjà connecté :
if (internauteEstConnecte()){ // s'il est connecté, nous le redirigeons vers son profil :
	header('location:profil.php'); // attention : ne pas faire d'affichage avant cette instruction(exemple :echo, print, var_dump, print_r, ou debug)
	exit(); // c'est ici que nous quittons le script donc que nous exécutons le session_destroy
}











// 	debug($_POST);
	
// 1-traitement du formulaire :
if ($_POST){ // équivalent à (!empty($_POST)), car si il est vide $_POST vaut implicitement false, si il est rempli il vaut implicitement true
	
	// Les contrôles du formulaire :
	if (!isset($_POST['pseudo']) || empty($_POST['pseudo'])){
		$contenu.='<div class="bg-danger" >Le pseudo est requis.</div>';
	}
	
	if (!isset($_POST['mdp']) || empty($_POST['mdp'])){
		$contenu.='<div class="bg-danger" >Le mot de passe est requis.</div>';
	}
	
	if (empty($contenu)){ // si vide c'est qu'il n'y a pas d'erreur sur le formulaire : on peut donc vérifier le couple login/mdp en BDD:
		
		$mdp= md5($_POST['mdp']); // on passe aussi le mdp de connexion dans md5 pour le comparer avec celui de la bdd
		
		$resultat = executeRequete("SELECT * FROM membre WHERE pseudo = :pseudo AND mdp = :mdp", 
		array(
			':pseudo' => $_POST['pseudo'],
			':mdp' 	=> $mdp
		));
		
		if ($resultat->rowCount() !=0){ // si il y a une ligne dans $resultat, c'est que le login et le mdp existent et correspondent au m^me membre en BDD
			
			$membre = $resultat->fetch(PDO::FETCH_ASSOC); // pas de boucle while car nous n'avons qu'un seul membre avec ce lgin ou ce mot de passe
			$_SESSION['membre'] = $membre; // nous créons une session avec tous les éléments provenant de la BDD et contenus dans $membre (=array)
			// debug($_SESSION);
			
			header('location:profil.php'); // les identifiants étant corrects nous dirigeons l'internaute vers sa page profil
			exit(); // on quitte le script pour l'arrêter
			
		} else {
			// si le nombre de ligne est de 0 alors il n'y a pas de correspondance entre le login et le mdp
			$contenu .= '<div class="bg-danger">Erreur sur les identifiants !</div>';
		}
	}
	
} // fin du if ($_POST)





//------------------AFFICHAGE--------------------
require_once('inc/haut.inc.php');
echo $contenu;
?>
<h3>Formulaire de connexion</h3>
<form method="post" action="">
	<label for="pseudo">Pseudo</label><br>
	<input type="text" id="pseudo" name="pseudo"><br><br>

	<label for="mdp">Pseudo</label><br>
	<input type="password" id="mdp" name="mdp"><br><br>

	<input type="submit" value="Se connecter" class="btn">

</form>

<?php
require_once('inc/bas.inc.php');




