<?php
	include_once 'Check_Session.php';																								//include il controllo della sessione
	if (isset($_POST['subscription_id']) && isset($_POST['duration']) && isset($_POST['payment_method']) && $active_session){		//se sono stati ricevuti i campi
		include_once 'DB_Data.php';																									//include i dati del db
		$conn = mysqli_connect($DB_DATA['host'], $DB_DATA['user'], $DB_DATA['password'], $DB_DATA['name']);							//crea la connessione
		$duration = mysqli_real_escape_string($conn, $_POST['duration']);															//escape delle stringhe
		$email= mysqli_real_escape_string($conn,$session_email);
		foreach($_POST['subscription_id'] AS $subscription_id){																		//per ogni id abbonamento ricevuto
			$id = mysqli_real_escape_string($conn, $subscription_id);																//escape dell'id
			$query='SELECT * FROM user_subscriptions_active WHERE email="'.$email.'" AND subscription_code="'.$id.'"';				//seleziona i corsi dell'utente con lo stesso id
			$res = mysqli_query($conn,$query) or die (mysqli_error($conn));
			if(mysqli_num_rows($res)>0){																							//se l'abbonamento è ancora in corso
				$query='UPDATE user_subscriptions_active SET duration_months = duration_months+'.$duration.'						
						WHERE email="'.$email.'" AND subscription_code="'.$id.'"';													//updata la durata
			}else{																													//se è un nuovo abbonamento
				$query='INSERT INTO user_subscriptions_active VALUES("'.$email.'","'.$id.'","'.$duration.'","'.date('Y-m-d').'")';	//lo aggiunge al db
			}
			$res = mysqli_query($conn,$query) or die(mysqli_error($conn));
		}
		$query = 'SELECT SUM(cost) as partial_cost FROM subscription WHERE';														//fa la somma degli abbonamenti 
		foreach($_POST['subscription_id'] AS $subscription_id){
			$id = mysqli_real_escape_string($conn, $subscription_id);
			$query = $query.' subscription_code="'.$id.'" OR';
		}
		$query = substr($query,0,-3);																								//rimuove gli ultimi 3 caratteri (' OR')
		$res = mysqli_query($conn,$query) or die (mysqli_error($conn));
		$total_cost=mysqli_fetch_assoc($res)['partial_cost']*$duration;																//calcola il costo totale
		echo json_encode(																											//lo stampa sotto forma di json
			array(
				'success'=>true,
				'payment_method'=>$_POST['payment_method'],
				'total_cost'=>$total_cost
			)
		);
		mysqli_close($conn);																										//chiude la connessione
	}else{
		echo json_encode(array('success' => false));
	}
?>