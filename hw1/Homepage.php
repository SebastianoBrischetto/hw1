<?php
	include_once 'Default_Elements.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Triscele Fitness - Homepage</title>
		<?php default_head(); ?>
		<link rel="stylesheet" href="CSS/Homepage_Style.css">
	</head>
	<body>  
		<header>
			<?php
				default_nav_bar();
			?>
			<div id="page_info">
				<span>
					<h1>OFFERTE DI RIAPERTURA</h1>
					<p>Abbonamenti per ogni tua esigenza a un prezzo imperdibile!</p>
				</span>
				<a class="buttons" href="Courses.php">SCOPRI DI PIÙ</a>
			</div>		
		</header>
		<main>	
			<section>
				<span>
					<h2>Triskelion Fitness</h2>
					<p>13 sedi, attrezzature di ultima generazione, trainer qualificati e ambienti pensati 
					per soddisfare le esigenze di tutti, da più di diec’anni lavoriamo ogni giorno con
					passione e dedizione nel mondo del benessere e dello sport. La nostra avventura è iniziata
					nel 2011 con un solo obiettivo: regalare ai siciliani un luogo dove prendersi cura del proprio corpo.</p>
				</span>
				<div class="list">
					<span>
						<h2>I nostri Corsi</h2>
						<p>Esperienza, innovazione, qualità: tutti i nostri corsi sono pensati per regalarti un’esperienza coinvolgente e risultati concreti.</p>
					</span>
					<div class="list_row">
						<div class="list_element">
							<img class="list_element_background" src="images/courses/weightlifting.png" />
							<div class="transition_overlay">
								<h3>Fitness</h3>
							</div>
						</div>
						<div class="list_element">
							<img class="list_element_background" src="images/courses/swimming.png" />
							<div class="transition_overlay">
								<h3>Nuoto</h3>
							</div>
						</div>
						<div class="list_element">
							<img class="list_element_background" src="images/courses/yoga.png" />
							<div class="transition_overlay">
								<h3>Benessere</h3>
							</div>
						</div>
						<div class="list_element">
							<img class="list_element_background" src="images/courses/MartialArts.png" />
							<div class="transition_overlay">
								<h3>Arti Marziali</h3>
							</div>
						</div>
					</div>
				</div>			
				<div class="content_block border_top">
					<div class="content_block_fill">
						<span>
							<h2>I Trainer</h2>
							<p>Scegliamo i migliori trainer per la tua forma fisica perché il nostro obiettivo è rendere il tuo allenamento irresistibile.</p>
						</span>
						<a href="Trainers.php" class="buttons">SCOPRI I TRAINER</a>
					</div>
					<div class="content_block_right">
						<img class="content_block_image" src="images/Trainers/trainer.png"/>
					</div>
				</div>			
				<div class="content_block border_top">
					<div class="content_block_left">
						<img class="content_block_image" src="images/Locations/location1.png" />
					</div>
					<div class="content_block_fill">
						<span>
							<h2>Sedi</h2>
							<p>Diverse sedi sparse sul territorio catanese, cosi da essere sempre a un passo da casa tua.</p>
						</span>
						<a href="Locations.php" class="buttons">TROVA LE SEDI</a>
					</div>
				</div>			
			</section>		
		</main>	
		<?php
			default_footer('default');
		?>
	</body>
</html>