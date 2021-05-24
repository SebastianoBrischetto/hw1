<?php
	if(!isset($_GET['location'])){																					//se non viene ricevuto nessuno valore tramite GET
		header('location: Trainers.php');																			//reindirizza alla pagina delle sedi
		exit;
	};
	include_once 'PHP_Requests/DB_Data.php';																		//include i dati per la connessione al db
	$conn = mysqli_connect($DB_DATA['host'], $DB_DATA['user'], $DB_DATA['password'], $DB_DATA['name']);				//effettua la connessione al db
	$address=mysqli_real_escape_string($conn, $_GET['location']);													//fa l'escape della stringa ricevuta
	$query='SELECT * FROM trainer WHERE address="'.$address.'"';													//effettua la query
	$res=mysqli_query($conn,$query) or die(mysqli_error($conn));
	if(!mysqli_num_rows($res)>0){																					//se non torna risultati
		header('location: Trainers.php');																			//reindirizza alla pagina delle sedi
		mysqli_close($conn);																						//chiude la connessione
		exit;
	}
	include_once 'Default_Elements.php';																			//include gli elementi di default
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Triscele Fitness - Sedi</title>
		<?php default_head(); ?>
		<script src="JS/Load_Overlays_Script.js" defer="true"></script>
	</head>  
	<body>  
		<header>
			<?php
			default_nav_bar();
			?>
			<div id="page_info">
				<h1>
				<?php
					echo 'Trainers di '.$address;
				?>
				</h1>
			</div>		
		</header>
				<main>
			<section>
				<div class="list" id="trainers">
					<div class="list_row">
						<?php

							while($trainers = mysqli_fetch_assoc($res)){													//per ogni sede crea un elemento che la mostra
								echo('
						<div class="list_element">
							<img class="list_element_background" src="'.$trainers['image'].'"></img>
							<div class="transition_overlay">
							<h3>'.$trainers['name'].' '.$trainers['surname'].'</h3>
							<p class="hidden description">'.$trainers['description'].'</p>
							<p class="hidden note">&nbsp</p>
							</div>
						</div>
								');
							}
						?>
					</div>
				</div>
			</section>
		</main>
		<?php
			mysqli_close($conn);
			default_footer('default');
		?>
	</body>
</html>