<?php
	/*--------------------------------------------------
	 Inserimento dei corsi inviati nella lista preferiti
	----------------------------------------------------*/
	include_once 'Check_Session.php';
	if(!$active_session){																						//se non ce una sessione attiva torna un errore
		echo 'Effettua il login';
		exit;
	}
	if(isset($_GET['type'])){
		$url='https://wger.de/api/v2/exercise.json/?limit=50&language=2&category='.$_GET['type'];
		$curl = curl_init();	
		curl_setopt($curl,CURLOPT_URL,$url);																//imposta l'url
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);;														//ci fa tornare una stringa
		$response = curl_exec($curl);																		//lo esegue
		curl_close($curl);	
		echo $response;																						//già è in formato json
	}
?>