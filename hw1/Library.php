<?php
	/*--------------------------------------------------
			Controllo presenza della sessione
	----------------------------------------------------*/
	include_once 'Default_Elements.php';
	if(!$active_session)																						//se non è presente una sessione reindirizza
	{																											//alla homepage
		header('location: Homepage.php');
		exit;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Triscele Fitness - Abbonamenti</title>
		<?php default_head(); ?>
		<link rel="stylesheet" href="CSS/Library_Style.css">
		<script src="JS/Library_Script.js" defer="true"></script>
	</head>
	<body>
		<header>
			<?php
				default_nav_bar();
			?>
			<div id="page_info">
				<h1>Libreria Esercizi</h1>
			</div>		
		</header>
		<main>
			<section>
				<div id="info">
					<h2>Cerca un tipo di esercizio</h2>
					<select name='type'>
						<option value="0" selected disabled hidden>Tipo</option>
						<option value='8'>Braccia</option>
						<option value='9'>Gambe</option>
						<option value='10'>Addominali</option>
						<option value='11'>Petto</option>
						<option value='12'>Dorso</option>
						<option value='13'>Spalle</option>
						<option value='14'>Polpacci</option>
					</select>
				</div>
				<div class="list border_top" id="exercise_library"></div>
			</section>
		</main>	
		<?php
		default_footer('default');
		?>
	</body>
</html>