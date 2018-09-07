<?php 
require_once('inc/init.inc.php');

require_once('inc/haut.inc.php');
if (isset($_POST['date_arrivee']) && isset($_POST['date_depart'])){
$date_dispo = executeRequete("SELECT * FROM produit WHERE id_salle = :id_salle AND (( :date_arrivee >= date_arrivee AND :date_depart <= date_depart) OR (:date_arriv < date_arrivee AND :date_depart >= date_arrivee AND : date_depart<= date_depart) OR (:date_depart > date_depart AND :date_arrivee<= date_depart AND :date_arrivee >= date_arrivee) OR (:date_arrivee < date_arrivee AND :date_depart > date_depart)) ",array(
		':id_salle' => $_POST['id_salle'],
		':date_arrivee' => $_POST['date_arrivee'],
		':date_depart' => $_POST['date_depart'],
		
);
$rt = $date_dispo->fetch(PDO::FETCH_ASSOC);
if ($rt >= 1) {
	$contenu .= '<div class="bg-danger"> Ces plages de dates ne sont pas disponibles pour cette salle !!! </div>'
}
}
require_once('inc/bas.inc.php');
