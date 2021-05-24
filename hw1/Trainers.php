<?php
	include_once 'Default_Elements.php';
	include_once 'PHP_Requests/DB_Data.php';
	$conn = mysqli_connect($DB_DATA['host'], $DB_DATA['user'], $DB_DATA['password'], $DB_DATA['name']);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Triscele Fitness - Trainers</title>
		<?php default_head(); ?>
		<script src="JS/Load_Overlays_Script.js" defer="true"></script>
	</head>  
	<body>  
		<header>
			<?php
				default_nav_bar();
			?>
			<div id="page_info">
				<h1>Trainers</h1>
			</div>		
		</header>
		<main>
			<section>
				<div class="list" id="locations">
					<div class="list_row">
						<?php
							$query='SELECT * FROM location';																//query per selezionare tutte le sedi
							$res=mysqli_query($conn,$query) or die(mysqli_error($conn));
							while($locations = mysqli_fetch_assoc($res)){													//per ogni sede crea un elemento che la mostra
								$query='SELECT image FROM location_images WHERE address="'.$locations['address'].'"';
								$res_image=mysqli_query($conn,$query) or die (mysqli_error($conn));
								echo('
						<div class="list_element">
							<img class="list_element_background" src="'.mysqli_fetch_assoc($res_image)['image'].'"></img>
							<div class="transition_overlay">
							<h3>Trainers di '.$locations['address'].'</h3>
							<p class="hidden description">&nbsp</p>
							<a href="http://localhost/hw1/Trainer.php?location='.$locations['address'].'">
								<img class="round_button right hidden" src="images/buttons/arrow.png">
							</a>
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
			default_footer('default');
		?>
	</body>
</html>