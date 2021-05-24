<?php
	if(!isset($_GET['location'])){																					//se non viene ricevuto nessuno valore tramite GET
		header('location: Locations.php');																			//reindirizza alla pagina delle sedi
		exit;
	};
	include_once 'PHP_Requests/DB_Data.php';																		//include i dati per la connessione al db
	$conn = mysqli_connect($DB_DATA['host'], $DB_DATA['user'], $DB_DATA['password'], $DB_DATA['name']);				//effettua la connessione al db
	$address=mysqli_real_escape_string($conn, $_GET['location']);													//fa l'escape della stringa ricevuta
	$query='SELECT * FROM location WHERE address="'.$address.'"';													//effettua la query
	$res=mysqli_query($conn,$query) or die(mysqli_error($conn));
	if(!mysqli_num_rows($res)>0){																					//se non torna risultati
		header('location: Locations.php');																			//reindirizza alla pagina delle sedi
		mysqli_close($conn);																						//chiude la connessione
		exit;
	}
	include_once 'Default_Elements.php';																			//include gli elementi di default
	$location = mysqli_fetch_assoc($res);																			//ottiene i dati della sede
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Triscele Fitness - Sedi</title>
		<?php default_head(); ?>
		<link rel="stylesheet" href="https://api.mqcdn.com/sdk/mapquest-js/v1.3.2/mapquest.css"/>
		<link rel="stylesheet" href="CSS/Location_Style.css">
		<script src="https://api.mqcdn.com/sdk/mapquest-js/v1.3.2/mapquest.js" defer="true"></script>
		<script src="JS/Location_Script.js" defer="true"></script>
	</head>  
	<body>  
		<header>
			<?php
			default_nav_bar();
			?>
			<div id="page_info">
				<h1>
				<?php
					echo 'Sede di '.$address;
				?>
				</h1>
			</div>		
		</header>
		<section class="slide_show">
			<?php
				$query='SELECT image FROM location_images WHERE address="'.$address.'"';							//selezione le immagini relative alla sede 
				$res=mysqli_query($conn,$query) or die(mysqli_error($conn));
				while($images = mysqli_fetch_assoc($res)){															//crea slide con le immagini ricevute
					echo('
			<div class="slide_element">
				<img src="'.$images['image'].'">
			</div>
					');
				}
			?>
			<div class="slide_shortcuts">
			<?php
				for($i=0;$i<mysqli_num_rows($res);$i++){															//crea una scorciatoia per ogni slide
					echo('
				<div data-slide_index="'.$i.'" class="shortcut"></div>
					');
				}
			?>
			</div>
			<img src="images/buttons/arrow.png" class="precedent round_button">
			<img src="images/buttons/arrow.png" class="next round_button right">
		</section>
		<main>
			<section>
				<section class="content_block">
					<div class="content_block_fill">
						<span>
							<h2>La struttura</h2>
							<p>
								<?php 
									echo $location['description'];
								?>
							</p>
						</span>
					</div>
					<div class="content_block_right">
						<div class="light_grey">
							<h3>Corsi Disponibili</h3>
							<?php
								$query='SELECT c.course_code, c.name FROM location_courses lc JOIN course c ON lc.course_code=c.course_code
										WHERE lc.address="'.$address.'"';												//carica tutti corsi disponibili in questa sede
								$res=mysqli_query($conn,$query) or die(mysqli_error($conn));
								while($course=mysqli_fetch_assoc($res)){
									echo('
							<a class="buttons" href="http://localhost/hw1/Courses.php?id='.$course['course_code'].'">'.$course['name'].'</a>
									');
								}
							?>
							<a class="buttons white" href="http://localhost/hw1/Trainer.php?location=<?php echo $address?>">Trainers</a>
						</div>
					</div>
				</section>
				<section class="content_block">
					<div class="content_block_fill" id="map">
					</div>
					<div class="content_block_right light_grey">
						<span>
							<h3>Dove Trovarci</h3>
							<p id="full_address">
								<?php
									echo $address.' - '.$location['city'];
								?>
							</p>
							<h3>Contatti</h3>
							<p>
								<?php
									echo '📞&nbsp'.$location['phone_number'].' | ✉&nbsp'.$location['email'];
								?>
							</p>
							<h3>Orari</h3>
							<?php
								$query='SELECT * FROM location_times WHERE address="'.$address.'"';					//carica gli orari di apertura e chiusura
								$res=mysqli_query($conn,$query) or die(mysqli_error($conn));
								while($times=mysqli_fetch_assoc($res)){
								echo('
							<p>'.$times['days'].': '.$times['times'].'</p>
								');
								}
							?>
						</span>
					</div>
				</section>
			</section>
		</main>
		<?php
			mysqli_close($conn);
			default_footer('default');
		?>
	</body>
</html>