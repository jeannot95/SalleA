<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
   
	<title>SALLEA</title>

    <!-- Bootstrap Core CSS -->
	<link href="<?php echo RACINE_SITE . 'inc/css/bootstrap.min.css'; ?>" rel="stylesheet">
    <!-- Custom CSS -->
	<link href="<?php echo RACINE_SITE . 'inc/css/shop-homepage.css'; ?>" rel="stylesheet">
	<link href="<?php echo RACINE_SITE . 'inc/css/etoile.css'; ?>" rel="stylesheet">
	<!-- AJOUTER LE LIEN CSS SUIVANT POUR LE DETAIL PRODUIT-->
	<link href="<?php echo RACINE_SITE . 'inc/css/portfolio-item.css'; ?>" rel="stylesheet">	
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
	<link href="<?php echo RACINE_SITE . 'inc/css/fontawesome-stars.css'; ?>" rel="stylesheet">	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<!-- jQuery -->
	<script src="<?php echo RACINE_SITE . 'inc/js/jquery.js'; ?>"></script>
	<script src="http://code.jquery.com/jquery-1.12.4.js"></script>
	 <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<!--<script src="http://jqueryui.com/resources/demos/datepicker/datepicker-fr.js"></script> -->
    <!-- Bootstrap Core JavaScript -->
	<script src="<?php echo RACINE_SITE . 'inc/js/bootstrap.min.js'; ?>"></script>	
			

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            
			<!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                  
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>	
				<!-- La marque -->
				<a class="navbar-brand" href="<?php echo RACINE_SITE . 'index.php'; ?>">SALLEA</a>
           
		   </div>
		   
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                 	<!-- le menu de navigation -->
					<?php 
						echo '<li><a href="'.RACINE_SITE .'index.php">Boutique</a></li>';
						

						// Lien 'panier' pour tous :
						//echo '<li><a href="'.RACINE_SITE .'panier.php">Panier ('. nombreProduitPanier() .')</a></li>';
						
						if  (internauteEstConnecteEtEstAdmin ()){ // Pour l'admin, on ajoute les liens de back-office :  
							echo '<li><a href="'.RACINE_SITE .'admin/gestion_salles.php">Gestion salles</a></li>';
							echo '<li><a href="'.RACINE_SITE .'admin/gestion_produits.php">Gestion produits</a></li>';
							echo '<li><a href="'.RACINE_SITE .'admin/gestion_commande.php">Gestion commandes</a></li>';
							echo '<li><a href="'.RACINE_SITE .'admin/gestion_membre.php">Gestion membres</a></li>';
							echo '<li><a href="'.RACINE_SITE .'admin/gestion_avis.php">Gestion avis</a></li>';
							echo '<li><a href="'.RACINE_SITE .'admin/statistiques.php">Statistiques</a></li>';
						}
						
						if (internauteEstConnecte()){ // si membre connecté on affiche les liens 'profil' et 'se déconnecter' :
								echo '<li><a href="'.RACINE_SITE .'profil.php">Profil</a></li>';
								echo '<li><a href="'.RACINE_SITE .'connexion.php?action=deconnexion">Se déconnecter</a></li>';
						}	else { // membre non connecté : on affiche les liens 'incription' et 'connexion' :
								echo '<li><a href="'.RACINE_SITE .'inscription.php">Inscription</a></li>';
								echo '<li><a href="'.RACINE_SITE .'connexion.php">Connexion</a></li>';
						}						
						
					?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div> <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container" style="min-height: 80vh;">

	
    