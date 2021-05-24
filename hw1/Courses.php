<?php
	include_once 'Default_Elements.php';																		//include una volta il php degli elementi di default
	include_once 'PHP_Requests/DB_Data.php';																	//include una volta il php con i dati del db
	$conn = mysqli_connect($DB_DATA['host'], $DB_DATA['user'], $DB_DATA['password'], $DB_DATA['name']);			//crea la connessione
	/*----------------------------------------------------------------
					Ottiene la query in base al tipo inserito
	-----------------------------------------------------------------*/
	function get_query_by_type($type){
		$query='SELECT * FROM course WHERE type="'.$type.'"';
		return $query;
	}
	/*----------------------------------------------------------------
						carica i corsi
	-----------------------------------------------------------------*/
	function show_courses ($query,$action) {
		global $conn;																							//usa la variabile globale $conn
		switch($action){																						//in base all'azione da eseguire cambia pulsante e nota
			case 'add':
				$action_image='images/buttons/add.png';
				$action_note='Aggiungi ai preferiti';
			break;
			case 'remove':
				$action_image='images/buttons/remove.png';
				$action_note='Rimuovi dai preferiti';
			break;
		}
		$res=mysqli_query($conn,$query) or die(mysqli_error($conn));
		while($course = mysqli_fetch_assoc($res)){																//scorre fra tutti i corsi risultati
			echo('
			<div class="list_element" data-course_id="'.$course['course_code'].'">
				<img class="list_element_background" src="'.$course['image'].'"></img>
				<div class="transition_overlay">
					<h3>'.$course['name'].'</h3>
					<p class="hidden description">'.$course['description'].'</p>
					<img class="hidden round_button" src="'.$action_image.'">
					<p class="hidden note">'.$action_note.'</p>
				</div>
			</div>
			');																									//crea il div che mostra il corso
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Triscele Fitness - Corsi</title>
		<?php default_head(); ?>
		<link rel="stylesheet" href="CSS/Courses_Style.css">
		<script src="JS/Courses_Script.js" defer="true"></script>
	</head>  
	<body>  
		<header>
			<?php
				default_nav_bar();
			?>
			<div id="page_info">
				<h1>Corsi</h1>
			</div>		
		</header>
		<main>	
			<section>
				<div class="search_row">
					<h2>I nostri corsi</h2>
					<input type='text' name='searchbar' placeholder="Cerca corso">
				</div>
				<div class="list border_top" id="favorites">
					<h2>Corsi preferiti</h2>
					<div class="list_row">
						<?php
							if($active_session){																//se è presente una sessione
								$query='SELECT  c.course_code, c.name, c.description, c.image
										FROM course c JOIN user_courses uc ON c.course_code=uc.course_code 
										WHERE uc.email="'.mysqli_real_escape_string($conn,$session_email).'"';
								show_courses($query,'remove');													//carica i corsi preferiti
							}
						?>
					</div>
				</div>
				<div class="list border_top" id="fitness">
					<h2>Fitness</h2>
					<div class="list_row">
						<?php
							show_courses(get_query_by_type('fitness'),'add');
						?>
					</div>
				</div>
				<div class="list border_top" id="swimming">
					<h2>Nuoto</h2>
					<div class="list_row">
						<?php
							show_courses(get_query_by_type('swimming'),'add');
						?>
					</div>
				</div>
				<div class="list border_top" id="wellness">
					<h2>Benessere</h2>
					<div class="list_row">
						<?php
							show_courses(get_query_by_type('wellness'),'add');
						?>
					</div>
				</div>
				<div class="list border_top" id="martial_arts">
					<h2>Artimarziali</h2>
					<div class="list_row">
						<?php
							show_courses(get_query_by_type('martial_arts'),'add');
							mysqli_close($conn);
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