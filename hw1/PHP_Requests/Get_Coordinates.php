<?php
	if(isset($_GET['full_address'])){																														
		$key = "fWuD4P38hJTPB6mwWZjmArcc1bqe2XGw";
		$url = 'http://www.mapquestapi.com/geocoding/v1/address?thumbMaps=false&outFormat=json&key='.$key;
		$curl = curl_init();																				//avvio curl
		curl_setopt($curl,CURLOPT_URL,$url.'&location='.urlencode($_GET['full_address']));					//imposta l'url
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);;														//ci fa tornare una stringa
		$response = curl_exec($curl);																		//lo esegue
		curl_close($curl);																					//chiude curl
		$coordinates = json_decode($response,true);															//trasformo il json in un array associativo(true)
		echo json_encode($coordinates['results'][0]['locations'][0]['latLng']);								//ricreo un json ma solo con latitudine e longitudine
	}
?>