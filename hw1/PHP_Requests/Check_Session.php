<?php
	session_start();
	$active_session=isset($_SESSION['session']);				//sessione presente = true , non presente = false
	if($active_session){										//se ce una sessione
		$session_email=$_SESSION['session']['email'];			//crea constanti per email e controllo admin
		$is_admin=$_SESSION['session']['admin'];
	}