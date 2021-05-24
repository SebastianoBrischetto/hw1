<?php
	/*--------------------------------------------------
	 Inserimento dei corsi inviati nella lista preferiti
	----------------------------------------------------*/
	include_once 'Check_Session.php';
	if(!$active_session){																						//se non ce una sessione attiva torna un errore
		echo json_encode(array(
				'success'=>false,
				'error'=>'Effettua il login per poter salvare i preferiti'
			)
		);
		exit;
	}
	if (isset($_GET['add_course']) || isset($_GET['remove_course'])){											//se si uno dei 2 parametri
		include_once 'DB_Data.php';																				//include i dati del db
		$conn = mysqli_connect($DB_DATA['host'], $DB_DATA['user'], $DB_DATA['password'], $DB_DATA['name']);		//crea la connessione
		$email=mysqli_real_escape_string($conn,$session_email);													//effettua l'escape della stringa email
		switch($_GET){																							//in base alla nome della chiave ricevuta imposta query e id
			case isset($_GET['add_course']):
				$id=mysqli_real_escape_string($conn,$_GET['add_course']);
				$query='INSERT INTO user_courses VALUES("'.$email.'","'.$id.'")';
			break;
			case isset($_GET['remove_course']):
				$id=mysqli_real_escape_string($conn,$_GET['remove_course']);
				$query = 'DELETE FROM user_courses WHERE email="'.$email.'" AND course_code="'.$id.'"';
			break;
		}
		$res = mysqli_query($conn,$query);																		//effettua la query al db
		mysqli_close($conn);																					//chiude la connessione
		echo json_encode(array('success' => $res));																//torna il risultato
	}
?>