<?php
	include_once 'Default_Elements.php';
	if(!$is_admin){
		header('location: Homepage.php');
		exit;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Triscele Fitness - New Content</title>
		<?php default_head(); ?>
		<link rel="stylesheet" href="CSS/Manage_Content_Style.css">
		<script src="JS/Manage_Content_Script.js" defer="true"></script>
	</head>
	<body>  
		<header>
			<?php
				default_nav_bar();
			?>
			<div id="page_info">
				<h1>Gestisci Contenuto</h1>
			</div>		
		</header>
		<main>	
			<section>
				<div id="new_content">
					<select name="new_content">
						<option value="none" selected disabled hidden>Seleziona cosa aggiungere</option>
						<option value="course">Nuovo corso</option>
						<option value="subscription">Nuovo abbonamento</option>
						<option value="subscription_courses">Nuovi corsi per un abbonamento</option>
						<option value="location">Nuova sede</option>
						<option value="location_images">Nuove immagini per una sede</option>
						<option value="location_courses">Nuovi corsi per una sede</option>
						<option value="location_times">Nuovi orari per una sede</option>
						<option value="trainer">Inserisci un nuovo trainer</option>
					</select>
				</div>
				<div id="existing_content">
					<select name="existing_content">
						<option value="none" selected disabled hidden>Seleziona cosa modificare</option>
						<option value="course">Modifica corsi</option>
						<option value="subscription">Modifica abbonamenti</option>
						<!--<option value="subscription_courses">Modifica corsi assegnati ad un abbonamento</option>-->
						<option value="location">Modifica sedi</option>
						<option value="trainer">Modifica trainers</option>
						<!--<option value="location_courses">Modifica corsi assegnati ad una sede</option>
						<option value="location_times">Modifica orari di una sede</option>-->
					</select>
				</div>
			</section>
		</main>	
		<?php
			default_footer('default');
		?>
	</body>
</html>