<?php 
require_once('../inc/init.inc.php');

//-----------------TRAITEMENT-----------------
// 1- On vérifie que le membre est admin :
if (!internauteEstConnecteEtEstAdmin()){
	header('location:../connexion.php');
	exit();
}



if (isset($_GET['action']) && $_GET['action'] == 'note'){ 

$donnees = executeRequete("SELECT s.id_salle,s.photo , s.titre,ROUND(AVG(a.note),1)AS note FROM salle s LEFT JOIN avis a ON s.id_salle = a.id_salle GROUP BY id_salle ORDER BY note DESC LIMIT 5 ");

	$contenu .= '<br><h4>Top 5 des salles les mieux notées</h4><br>';
	$contenu .= '<table class="table text-center">';
	$contenu .= '<tr>';
	$contenu .= '<th class="text-center">Rang</th><th class="text-center">Membre</th><th class="text-center">Moyenne notes</th>';
	$contenu .= '</tr>';
 if ($donnees->rowCount() > 0){
	 $i=1;
	while($produit = $donnees->fetch(PDO::FETCH_ASSOC)){
		$contenu .= '<tr>';
		$contenu .= '<td>'.$i.'</td>';
		$contenu .= '<td>'.$produit['titre'].'</td>';
		$contenu .= '<td>'.$produit['note'].'</td>';
		$contenu .= '</tr>';
		$i++;
		//$contenu .= $produit['titre']. ' - ' . $produit['note'] . '<br>';
	} 
 }$contenu .= '</table>';

}

if (isset($_GET['action']) && $_GET['action'] == 'commandes'){ 

$donnees = executeRequete("SELECT s.id_salle, s.titre, COUNT(c.id_commande) as commandes, p.id_produit FROM salle s LEFT JOIN produit p ON p.id_salle = s.id_salle LEFT JOIN commande c ON c.id_produit = p.id_produit GROUP BY id_salle ORDER BY commandes DESC LIMIT 5 ");

	$contenu .= '<br><h4>Top 5 des salles les plus commandées</h4><br>';
	$contenu .= '<table class="table text-center">';
	$contenu .= '<tr>';
	$contenu .= '<th class="text-center">Rang</th><th class="text-center">Membre</th><th class="text-center">Nombre de commandes</th>';
	$contenu .= '</tr>';
 if ($donnees->rowCount() > 0){
	 $i=1;
	while($produit = $donnees->fetch(PDO::FETCH_ASSOC)){
		$contenu .= '<tr>';
		$contenu .= '<td>'.$i.'</td>';
		$contenu .= '<td>'.$produit['titre'].'</td>';
		$contenu .= '<td>'.$produit['commandes'].'</td>';
		$contenu .= '</tr>';
		$i++;
	} 
 } $contenu .= '</table>';
}

if (isset($_GET['action']) && $_GET['action'] == 'quantite'){ 

$donnees = executeRequete("SELECT c.id_membre, COUNT(c.id_commande) as commandes, m.pseudo FROM membre m LEFT JOIN commande c ON m.id_membre = c.id_membre GROUP BY id_membre ORDER BY commandes DESC LIMIT 5 ");

	$contenu .= '<br><h4>Top 5 des membres qui achètent le plus(en termes de quantité)</h4><br>';
	$contenu .= '<table class="table text-center">';
	$contenu .= '<tr>';
	$contenu .= '<th class="text-center">Rang</th><th class="text-center">Membre</th><th class="text-center">Nombre de commandes</th>';
	$contenu .= '</tr>';
 if ($donnees->rowCount() > 0){
	 $i=1;
	while($produit = $donnees->fetch(PDO::FETCH_ASSOC)){
		$contenu .= '<tr>';
		$contenu .= '<td>'.$i.'</td>';
		$contenu .= '<td>'.$produit['pseudo'].'</td>';
		$contenu .= '<td>'.$produit['commandes'].'</td>';
		$contenu .= '</tr>';
		$i++;
	} 
 } $contenu .= '</table>';
}

if (isset($_GET['action']) && $_GET['action'] == 'cher'){ 

$donnees = executeRequete("SELECT m.pseudo, c.id_membre, ROUND(AVG(p.prix))AS mprix FROM commande c LEFT JOIN produit p ON c.id_produit = p.id_produit  LEFT JOIN membre m ON m.id_membre = c.id_membre  GROUP BY id_membre ORDER BY mprix DESC LIMIT 5 ");

	$contenu .= '<br><h4>Top 5 des membres qui achètent le plus cher(en termes de prix)</h4><br>';
	$contenu .= '<table class="table text-center">';
	$contenu .= '<tr>';
	$contenu .= '<th class="text-center">Rang</th><th class="text-center">Membre</th><th class="text-center">Panier Moyen</th>';
	$contenu .= '</tr>';
 if ($donnees->rowCount() > 0){
	  $i=1;
	while($produit = $donnees->fetch(PDO::FETCH_ASSOC)){
		$contenu .= '<tr>';
		$contenu .= '<td>'.$i.'</td>';
		$contenu .= '<td>'.$produit['pseudo'].'</td>';
		$contenu .= '<td>'.$produit['mprix'].' € </td>';
		$contenu .= '</tr>';
		$i++;

	} 
 } $contenu .= '</table>';
}

require_once('../inc/haut.inc.php');
echo'<h1> Statistiques</h1>';
echo '<ul >
		<li><a href="?action=note">Top 5 des salles les mieux notées</a></li>	
		<li><a href="?action=commandes">Top 5 des salles les plus commandées</a></li>	
		<li><a href="?action=quantite">Top 5 des membres qui achètent le plus(en termes de quantité)</a></li>	
		<li><a href="?action=cher">Top 5 des membres qui achètent le plus cher(en termes de prix)</a></li>	
	  </ul>';
	  
echo $contenu;	  
?>



















<?php	

require_once('../inc/bas.inc.php');