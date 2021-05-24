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
	include_once 'PHP_Requests/DB_Data.php';																	//include una volta il php con i dati del db
	$conn = mysqli_connect($DB_DATA['host'], $DB_DATA['user'], $DB_DATA['password'], $DB_DATA['name']);			//crea la connessione
	$email=mysqli_real_escape_string($conn,$session_email);														//escape della stringa email
	$query = 'SELECT * FROM user_courses WHERE email="'.$email.'"';												//seleziona tutti i corsi preferiti dell'utente
	$res=mysqli_query($conn,$query) or die(mysqli_error($conn));
	$count_favorites=mysqli_num_rows($res);																		//conta quanti corsi preferiti ha
	/*--------------------------------------------------
		Funzione per il caricamento degli abbonamenti
	----------------------------------------------------*/
	function load_subscriptions ($res,$type){
		global $conn;
		if(mysqli_num_rows($res)>0){
			while($subscriptions = mysqli_fetch_assoc($res)){
				echo('
				<div class="list_element" data-subscription_id="'.$subscriptions['subscription_code'].'">
					<div class="list_element_image">
				');
						$query='SELECT course_type FROM view_subscription_courses WHERE subscription_code='.$subscriptions['subscription_code'].' group by course_type';
						$res_img=mysqli_query($conn,$query) or die(mysqli_error($conn));
						while($images=mysqli_fetch_assoc($res_img)){
							switch ($images['course_type']){
								case 'fitness':
									echo('
						<img src="http://localhost/hw1/Images/Icons/Fitness.png"/>
									');
								break;
								case 'swimming':
									echo('
						<img src="http://localhost/hw1/Images/Icons/Swimming.png"/>
									');
								break;
								case 'wellness':
									echo('
						<img src="http://localhost/hw1/Images/Icons/Wellness.png"/>
									');
								break;
								case 'martial_arts':
									echo ('
						<img src="http://localhost/hw1/Images/Icons/Martial_arts.png"/>
									');
								break;
							}
						}
					echo ('
					</div>
					<div class="list_element_description">
						<span>
							<h4>'.$subscriptions['name'].'</h4>
					');
						$query='SELECT course_code, course_name FROM view_subscription_courses WHERE subscription_code='.$subscriptions['subscription_code'];
						$res_courses=mysqli_query($conn,$query) or die(mysqli_error($conn));
						$counter=mysqli_num_rows($res_courses);
						for($i=1;$i<=$counter;$i++){
							$course=mysqli_fetch_assoc($res_courses);
							if($i==$counter){
								echo '<em class="course" data-course_id="'.$course['course_code'].'">'.$course['course_name'].'</em>';
							}else{
								echo('
								<em class="course" data-course_id="'.$course['course_code'].'">'.$course['course_name'].' |</em>
								');
							}
						}
						echo('
						</span>
					</div>
					<div class="list_element_info">
						');
					switch ($type){
						case 'cost':
							echo('
						<p class="cost">'.$subscriptions['cost'].'€</p>
					</div>
					<div class="list_element_action">
						<img class="round_button" src="http://localhost/hw1/Images/Buttons/Add.png"/>
					</div>
							');
						break;
						case 'duration':
							$duration = '+'.$subscriptions['duration_months'].' months';
							$effectiveDate = date('d-m-Y', strtotime($duration, strtotime($subscriptions['start_date'])));
							echo('
						<p class="date">'.$effectiveDate.'</p>
					</div>
							');							
						break;
					}
				echo('
				</div>
				');
			}
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Triscele Fitness - Abbonamenti</title>
		<?php default_head(); ?>
		<link rel="stylesheet" href="CSS/Subscriptions_Style.css">
		<script src="JS/Subscriptions_Script.js" defer="true"></script>
	</head>  
	<body>  
		<header>
			<?php
				default_nav_bar();
			?>
			<div id="page_info">
				<h1>Abbonamenti</h1>
			</div>		
		</header>
		<main>
			<section
				<?php
					$query=
					'SELECT s.subscription_code, s.name, us.duration_months, us.start_date FROM subscription s 
					JOIN user_subscriptions_active us ON s.subscription_code=us.subscription_code 
					WHERE us.email="'.$email.'"';																//controlla se ci sono abbonamenti in corso
					$res=mysqli_query($conn,$query) or die(mysqli_error($conn));
					if(!mysqli_num_rows($res)>0){																//se nessun abbonamento è in corso nasconde
						echo('class="hidden"');
					}
				?>
			>
				<div class="list" id="active_subscriptions">
					<h2>Abbonamenti Attivi</h2>
					<div class="list_element">
						<h4 class="list_element_description">Descrizione</h4>
						<h4 class="list_element_info">Scadenza</h4>
					</div>
					<?php
						if(mysqli_num_rows($res)>0){
							load_subscriptions($res,'duration');
						}
					?>
				</div>
			</section>
			<section class="hidden">
				<div id="cart">
					<h2>Carello Acquisti</h2>
					<div class="list">
						<div class="list_element">
							<h4 class="list_element_description">Descrizione</h4>
							<h4 class="list_element_info">Costo</h4>
							<h4 class="list_element_action"></h4>
						</div>
					</div>
					<div class="content_block">
						<div id="payment_info" class="content_block_left list light_grey">
							<div class="list_element">
								<h4 class="list_element_description">Durata:</h4>
								<div class="list_element_info">
									<img class="left round_button" src="http://localhost/hw1/Images/Buttons/Arrow.png"/>
									<p id="duration">1</p>
									<img class="right round_button" src="http://localhost/hw1/Images/Buttons/Arrow.png"/>
								</div>
							</div>
							<div class="list_element">
								<h4 class="list_element_description">Costo totale:</h4>
								<div class="list_element_info">
									<p id="total_cost">0.00€</p>
								</div>
							</div>
						</div>
						<div id="payment_buttons" class="content_block_fill">
							<a class="buttons white" id="Gpay"></a>
							<a class="buttons white" id="Paypal"></a>
							<a class="buttons" id="Credit_card">🔒&nbspAquista&nbspin&nbspsicurezza</a>
						</div>
					</div>
				</div>
				<div id="empty_cart" class="empty">
					<h2>Il tuo carello è vuoto</h2>
					<img class="logo" src="http://localhost/hw1/Images/Icons/Empty_Cart.png"/>
				</div>
			</section>
			<section id="subscriptions">
				<section class="content_block">
					<section id="matching_subscriptions" class="content_block_fill">
						<div class="list
							<?php
								if(!$count_favorites){
									echo 'hidden';
								}
							?>
						">
							<h3>Abbonamenti consigliati in base ai preferiti</h3>
							<div class="list_element">
								<h4 class="list_element_description">Descrizione</h4>
								<h4 class="list_element_info">Costo</h4>
								<h4 class="list_element_action"></h4>
							</div>
							<?php
								if($count_favorites){
									$query=
										'SELECT vsc.subscription_code, vsc.subscription_name AS name,vsc.subscription_cost AS cost 
										FROM view_subscription_courses vsc JOIN user_courses uc ON vsc.course_code=uc.course_code 
										WHERE uc.email="'.$email.'" GROUP BY vsc.subscription_code HAVING COUNT(*)="'.$count_favorites.'" ORDER BY cost';
									$res=mysqli_query($conn,$query) or die(mysqli_error($conn));
									load_subscriptions($res,'cost');
								}
							?>
						</div>
						<div class="empty 
							<?php
								if($count_favorites){
									echo 'hidden';
								}
							?>
						">
							<h2>A quanto pare non hai corsi preferiti</h2>
							<a href="Courses.php"><img src="http://localhost/hw1/Images/Icons/No_Favorites.png"/></a>
						</div>
					</section>
					<section id="custom_subscriptions" class="light_grey 
						<?php
							if(!$count_favorites){
								echo 'hidden';
							}
						?>
					">
						<h3>Abbonamenti simili</h3>
						<div class="list">
						<?php
							if($count_favorites){
								$query=
									'SELECT vsc.subscription_code, vsc.subscription_name AS name,vsc.subscription_cost AS cost 
									FROM view_subscription_courses vsc JOIN user_courses uc ON vsc.course_code=uc.course_code 
									WHERE uc.email="'.$email.'" GROUP BY vsc.subscription_code HAVING COUNT(*)<"'.$count_favorites.'" ORDER BY cost DESC';
								$res=mysqli_query($conn,$query) or die(mysqli_error($conn));
								load_subscriptions($res,'cost');
							}
						?>
					</section>
				</section>
				<section>
					<a class="buttons" id="show_all">Mostra tutti gli abbonamenti</a>
					<div class="list hidden" id="all_subscriptions">
						<h2>Abbonamenti</h2>
						<div class="list_element">
							<h4 class="list_element_description">Descrizione</h4>
							<h4 class="list_element_info">Costo</h4>
							<h4 class="list_element_action"></h4>
						</div>
						<?php
							$query='SELECT * FROM subscription ORDER BY cost';
							$res=mysqli_query($conn,$query) or die(mysqli_error($conn));
							load_subscriptions($res,'cost');
							mysqli_close($conn);
						?>
					</div>
				</section>
			</section>
		</main>	
		<?php
		default_footer('default');
		?>
	</body>
</html>