	</div>
		<!-- /.container -->

		<div class="container">

			<hr>

			<!-- Footer -->
			<footer>
				<div class="row">
					<div class="col-lg-12">
						<p>Copyright &copy; sallea - 2018. Ce Site est FICTIF.<a href="<?php echo RACINE_SITE ?>mentions.php">Mentions Légales</a>-<a href="<?php echo RACINE_SITE ?>cvg.php">C.V.G</a></p>
					</div>
				</div>
			</footer>

		</div>
    		<script>
			$(function(){
				 // 3- fonction callback :
				 function reponse(retourPHP){
					$("#selection").html(retourPHP); // on affiche le html envoyé en réponse par le serveur
					 // on affiche le html envoyé en réponse par le serveur
				 }
				 
				 function reponse2(retourPHP2){
					$("#nombre").html(retourPHP2); // on affiche le html envoyé en réponse par le serveur
					 // on affiche le html envoyé en réponse par le serveur
				 }
				 
				 // 1- fonction d'envoi de la requête au serveur en AJAX :
				 function envoi_ajax(){
					var donnees = $("#form2").serialize(); // transforme les données du formulaire en string avant envoi vers le serveur en AJAX, string formaté pour pouvoir remplir l'array $_POST automatiquement
				  console.log(donnees);
					$.post('selection.php', donnees , reponse , 'html'); // url de destination, données envoyées(objet ou string), callback de traitement de la réponse du serveur, format de retour 
					$.post('selection2.php', donnees , reponse2 , 'html');
				  }
				 				 				 
				 // 2- appels de notre fonction :
					envoi_ajax(); /// pour afficher tout de suite tous les produits disponibles
					
					$("#form2").change(envoi_ajax);// si les valeurs du formulaire changes on appel de nouveau la fonction pour mettre à jour la sélection
					 //$( "select" ).on( "change", envoi_ajax );
					//envoi_ajax();
				//$.( "#date_arrivee" ).setDefaults( $.( "#date_arrivee" ).regional[ "fr" ] );
				$( "#date_arrivee" ).datepicker({
	   				minDate: 0,
					altField: "#date_arrivee",
					closeText: 'Fermer',
					prevText: 'Précédent',
					firstDay: 1 ,
					nextText: 'Suivant',
					currentText: 'Aujourd\'hui',
					monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
					monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
					dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
					dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
					dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
					weekHeader: 'Sem.',
					dateFormat: 'yy/mm/dd 09:00'
					
	   			});
				$( "#date_depart" ).datepicker({
	   				minDate: 0,
					altField: "#date_depart",
					closeText: 'Fermer',
					prevText: 'Précédent',
					firstDay: 1 ,
					nextText: 'Suivant',
					currentText: 'Aujourd\'hui',
					monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
					monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
					dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
					dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
					dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
					weekHeader: 'Sem.',
					dateFormat: 'yy/mm/dd 19:00'
	   			});				
				$( "#date_arrivee2" ).datepicker({
	   				minDate: 0,
					altField: "#date_arrivee",
					closeText: 'Fermer',
					prevText: 'Précédent',
					firstDay: 1 ,
					nextText: 'Suivant',
					currentText: 'Aujourd\'hui',
					monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
					monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
					dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
					dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
					dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
					weekHeader: 'Sem.',
					dateFormat: 'yy/mm/dd 09:00:00'
					
	   			});
				$( "#date_depart2" ).datepicker({
	   				minDate: 0,
					altField: "#date_depart",
					closeText: 'Fermer',
					prevText: 'Précédent',
					firstDay: 1 ,
					nextText: 'Suivant',
					currentText: 'Aujourd\'hui',
					monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
					monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
					dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
					dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
					dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
					weekHeader: 'Sem.',
					dateFormat: 'yy/mm/dd 19:00:00'
	   			});						
			}); // fin du document ready 
				
			 
		</script>
    

</body>

</html>