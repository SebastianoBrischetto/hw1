<?php
	if (isset($_GET['email'])){																					//se il campo ricevuto tramite GET ha nome email
		include_once 'DB_Data.php';																				//include i dati necessari alla connessione col db
		$conn = mysqli_connect($DB_DATA['host'], $DB_DATA['user'], $DB_DATA['password'], $DB_DATA['name']);		//si stabilisce una connessione con il db
		$email = mysqli_real_escape_string($conn, $_GET['email']);												//si effettua l'escape della stringa ricevuta
		$query='SELECT * FROM user WHERE email= "'.$email.'"';													//si effettua il controllo dell'email
		$res = mysqli_query($conn,$query) or die(mysqli_error($conn));
		if(mysqli_num_rows($res)>0){																			//se si riceve almeno 1 risultato
			$check = array('exists' => true);																	//imposta il valore a vero
		}else{																									//se non viene ricevuto niente
			$check= array('exists' => false);																	//imposta il valore a falso
		}
		echo json_encode($check);																				//ritorna sotto forma di json $check
		mysqli_close($conn);																					//chiude la connessione
	}else{																										//se non viene ricevuta l'email
		echo json_encode(array('exists'=>false));																//mostra un messaggio di errore
	}
?>