<?php
	include_once 'PHP_Requests/Check_Session.php';
	/*----------------------------------------------------------------
			Carica gli elementi dell'head sempre richiesti
	-----------------------------------------------------------------*/
	function default_head(){
		echo('
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<link rel="icon" type="image/png" href="favicon.png">
			<link rel="stylesheet" href="CSS/Default_Style.css">
			<script src="JS/Default_Script.js" defer="true"></script>
		');
	}
	/*----------------------------------------------------------------
					Mostra la nav di default
	-----------------------------------------------------------------*/
	function default_nav_bar(){
		global $active_session;
		global $is_admin;
		echo('
		<nav>
			<div id="nav_logo">
				<a class="logo" href="Homepage.php"><img class="logo" src="Images/icons/logo.png"/></a>
			</div>
			<div id="nav_links">
				<a href="Homepage.php">Home</a>
				<a href="Courses.php">Corsi</a>
				<a href="Locations.php">Sedi</a>
				<a href="Trainers.php">Trainers</a>
		');
		if($active_session){
			echo('
				<a href="Subscriptions.php">Abbonamenti</a>
				<a href="Library.php">Libreria&nbspEsercizi</a>
			');
			if($is_admin){
				echo('
				<a href="Manage_Content.php">Gestisci&nbspContenuto</a>
				');
			}
		}
		echo('
			</div>
			<div id="nav_buttons">
		');
		if($active_session){
			echo('
				<a class="buttons" href="PHP_Requests/Logout.php">Log Out</a>
			');
		}else{
			echo('
				<a class="buttons" href="Signup.php">Log In</a>
			');
		}
		echo('
			</div>
		</nav>
		');
	}
	/*----------------------------------------------------------------
					Mostra il footer di default
	-----------------------------------------------------------------*/
	function default_footer($type){
		switch ($type){
			case 'default':
				$class='';
			break;
			case 'white':
				$class='class="white border_top"';
			break;
		}
		echo('
		<footer '.$class.'>
			<div id="footer_info">
				<img class="logo" src="images/icons/logo.png"/>
				<div id="footer_social_media">
					<h2>SEGUICI SU</h2>
					<img class="round_button" src="images/buttons/fb.png"/>
					<img class="round_button" src="images/buttons/twitter.png"/>
					<img class="round_button" src="images/buttons/instagram.png"/>			
				</div>
			</div>
			<em>Designed by Sebastiano Brischetto O46001573</em>
		</footer>
		');
	}