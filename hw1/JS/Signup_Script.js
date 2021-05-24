/*--------------------------------------------------
				Variabili globali
----------------------------------------------------*/
const login = document.querySelector('#Login');																		//form di login
const signup = document.querySelector('#Signup');																	//form di signup
const fetch_url = './PHP_Requests/Check_Email.php?email=';															//url del file php a cui fare la richiesta
/*--------------------------------------------------
				Controllo Login
----------------------------------------------------*/
function login_check(){
	const email = login.querySelector('input[name="log_email"]').value;												//email di login
	const password =login.querySelector('input[name="log_password"]').value;										//password di login
	if(!email.length || !password.length){																			//se uno dei campi è vuoto
		login.querySelector('.error').textContent='Inserire entrambi i campi';										//mostra un errore
		event.preventDefault();																						//blocca l'invio dei dati
	}
}
/*--------------------------------------------------
				Controllo Signup
----------------------------------------------------*/
function signup_check(){
	const inputs = signup.querySelectorAll('label');																//campi input di registrazione
	const password=signup.querySelector('input[name="password"]');													//password
	const password_error=password.parentNode.querySelector('.error');												//campo errore della password
	const c_password=signup.querySelector('input[name="c_password"]');												//password di conferma
	for(let i=0; i<inputs.length; i++){																				//scorre fra gli input
		let error = inputs[i].querySelector('.error');																//campo errore dell'input
		if(!inputs[i].querySelector('input').value.length){															//se l'input è vuoto
			error.dataset.status=404;
			error.textContent='Inserire tutti i campi';																//mostra l'errore
		}
		else if(error.dataset.status==404){																			//se l'input non è vuoto e ha status 404
			error.dataset.status=200;
			error.textContent='';																					//cancella l'errore
		}
	}
	if(password.value.length<8){																					//se la password è piu corta di 8 caratteri
		password_error.dataset.status=411;
		password_error.textContent='Inserire una password di almeno 8 caratteri';									//mostra l'errore
	}else{																											//se la password è lunga almeno 8 caratteri
		if(password.value!=c_password.value){																		//se le password non combaciano
			password_error.dataset.status=406;
			password_error.textContent='Le password non combaciano';												//mostra l'errore
		}else{																										//se l	e password combaciano
			password_error.dataset.status=200;
			password_error.textContent='';																			//cancella l'errore
		}
	}
}
/*--------------------------------------------------
				invio Signup
----------------------------------------------------*/
function signup_send(){
	console.log('prova');
	signup_check();																									//controlla tutti i campi di input
	let count_errors=0;
	const statuses = signup.querySelectorAll('.error');																//seleziona tutti i campi di errore degli input
	for(let i=0;i<statuses.length;i++){																				//scorre fra i campi di errore
		if(statuses[i].dataset.status!=200){																		//se è presente uno status diverso da 200
			count_errors+=1;																						//lo conta come errore
		}
	}
	if(count_errors>0){																								//se il numero di errori è maggiore di 0
		event.preventDefault();																						//blocca l'invio dei dati
	}
}
/*--------------------------------------------------
			controllo di unicità email
----------------------------------------------------*/
function Check_Email(){
	const email = signup.querySelector('input[name="email"]');														//email
	const email_error = email.parentNode.querySelector('.error');													//campo di errore dell'email
	fetch(fetch_url+encodeURIComponent(email.value)).then(onResponse).then(											//effettua una fetch per controllare la mail
		function(json){
			if(json.exists){																						//se il campo exists del json ricevuto è true
				email_error.dataset.status=403;
				email_error.textContent='Email già in uso';															//mostra l'errore
			}else{																									//se è false
				email_error.textContent='';																			//cancella l'errore
				email_error.dataset.status=200;
			}
		}			
	);
}
function onResponse(response) {
	return response.json();
}
/*--------------------------------------------------
			event listener
----------------------------------------------------*/
login.addEventListener('submit',login_check);																		//aggiunge event listener ai pulsanti di login/signup
signup.addEventListener('submit',signup_send);
signup.querySelector('input[name="email"]').addEventListener('keyup',Check_Email);									//aggiugne event listener al keyup nel campo email