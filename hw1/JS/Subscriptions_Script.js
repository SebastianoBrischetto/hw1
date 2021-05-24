/*-----------------------------------------------------------
				Variabili globali
-----------------------------------------------------------*/
const cart = document.querySelector('#cart').querySelector('.list');											//carello
const empty_cart=document.querySelector('#empty_cart');															//div per il carello vuoto
const duration=document.querySelector('#duration');																//durata
const payment_buttons = document.querySelectorAll('#payment_buttons .buttons');									//bottoni
const subscriptions=document.querySelectorAll('#subscriptions .list_element');									//tutte le iscrizioni
/*----------------------------------------------------------
				Riduzione/Aumento della durata
-----------------------------------------------------------*/
function minus(){
	const duration_value=parseInt(duration.textContent);
	if(duration_value===1){																						//se la durata è pari a 1
		show_message('Durata minima 1 mese','info',1000);														//blocca la riduzione e mostra un messaggio
	}else{
		duration.textContent=duration_value-1;																	//riduce la durata
		update_total_cost();																					//aggiorna il costo
	}
}
function plus(){
	duration.textContent=parseInt(duration.textContent)+1;														//aumenta la durata
	update_total_cost();																						//aggiorna il costo
}
/*----------------------------------------------------------
			Crea i dati da mandare per il pagamento
-----------------------------------------------------------*/
function payment(){
	const cart_subscriptions_id=get_subscriptions_id(cart);														//prende gli id degli abbonamenti nel carello
	const duration=document.querySelector('#duration').textContent;												//seleziona la durata
	const formdata=new FormData();																				//crea un nuovo formdata
	for (let i=0;i<cart_subscriptions_id.length;i++){															//gli aggiunge tutti i dati necessari
		formdata.append('subscription_id[]',cart_subscriptions_id[i]);
	}
	formdata.append('duration',duration);
	formdata.append('payment_method',event.currentTarget.id);
	fetch('http://localhost/hw1/PHP_Requests/Payment.php',{														//esegue la fetch
		method:'post',
		body:formdata
	}).then(onResponse).then(
		function (json){
			if(json.success){																					//se ha successo
				const block_inputs=document.createElement('div');												//crea un div di overlay al body che blocca gli input
				block_inputs.classList.add('block_inputs');
				document.querySelector('body').appendChild(block_inputs);
				show_message('Pagamento di '+json.total_cost.toFixed(2)+'€ tramite '+json.payment_method+' effettuato<br>Verrai ora reindirizzato alla homepage','info',5000);
				setTimeout(function(){																			//mostra un messaggio e dopo 5 secondi reindirizza alla home
				window.location.replace('Homepage.php');
				},5000);
			}else{																								//se fallisce
				show_message('Pagamento fallito','error',5000);													//mostra un errore
			}
		}
	);
}
function onResponse(response) {
	return response.json();
}
/*-----------------------------------------------------------
			Inserimento nel carello
-----------------------------------------------------------*/
function add_subscription(){
	let error_message;
	const this_subscription = event.currentTarget.parentNode.parentNode;										//seleziona l'abbonamento da aggiungere
	const subscription_id = this_subscription.dataset.subscription_id;											//seleziona l'id
	const courses = this_subscription.querySelectorAll('.course');												//prende i corsi dell'abbonamento da aggiugnere
	let courses_id =[];
	for (let i=0;i<courses.length;i++){
		courses_id.push(courses[i].dataset.course_id);															//crea un array con gli id dei corsi
	}
	const active_subscriptions = document.querySelector('#active_subscriptions');								//prende i corsi attivi
	const active_subscriptions_id = get_subscriptions_id(active_subscriptions);									//prende l'id degli abbonamenti attivi
	const active_subscriptions_courses_id = get_courses_id(active_subscriptions);								//prende l'id dei corsi degli abbonamenti attivi
	if(courses_id.every(id=>active_subscriptions_courses_id.includes(id)) && 									//se tutti i corsi dell'abbonamento scelto sono presenti negli
	!active_subscriptions_id.includes(subscription_id)){														//abbonamenti attivi e l'id dell'abbonamento non è presente negli
		error_message='Il tuo piano attuale già include tutti i corsi offerti da questo abbonamento';			//abbonamenti attivi
	}
	const cart_subscriptions_id = get_subscriptions_id(cart);													//prende gli id di tutti gli abbonamenti nel carello
	const cart_courses_id = get_courses_id(cart);																//prende gli id dei corsi nel carello
	if(cart_subscriptions_id.includes(subscription_id)){														//se l'abbonamento è gia presente nel carello
		error_message='Abbonamento già presente';
	}else if (courses_id.some(id=>cart_courses_id.includes(id))) {												//se il carello contiene almeno uno dei corsi
		error_message='Uno o piu corsi già compresi negli abbonamenti scelti';
	}
	if(error_message){																							//se il messaggio di errore non è vuoto
		show_message(error_message,'warning',2000);																//lo mostra
	}else{
		const images = this_subscription.querySelector('.list_element_image').querySelectorAll('img');			//seleziona tutti i dati per creare un clone del corso
		const name = this_subscription.querySelector('.list_element_description').querySelector('h4').textContent;
		const cost = this_subscription.querySelector('.list_element_info').querySelector('.cost').textContent;
		const new_subscription = create_subscription(subscription_id,images,name,courses,cost,'remove');		//crea il clone
		cart.appendChild(new_subscription);																		//lo attacca al carello
		update_total_cost();																					//aggiorna il costo
		cart.parentNode.parentNode.classList.remove('hidden');													//mostra il div padre del carello
		cart.parentNode.classList.remove('hidden');																//mostra il carello
		empty_cart.classList.add('hidden');																		//nasconde il messaggio carello vuoto
	}
}
/*-----------------------------------------------------------
			Ottengono l'id dell'abbonamento/corso
-----------------------------------------------------------*/
function get_subscriptions_id(list) {
	let subscriptions_id=[];																					//crea un array
	const subscriptions = list.querySelectorAll('.list_element');												//seleziona tutti gli abbonamenti
	for(let i=0; i<subscriptions.length; i++){																	//li scorre
		if(subscriptions[i].dataset.subscription_id){															//se è presente l'id del corso
			subscriptions_id.push(subscriptions[i].dataset.subscription_id);									//lo aggiunge all'array
		}
	}
	return subscriptions_id;
}
function get_courses_id(list) {
	let courses_id=[];																							//crea un array
	const courses = list.querySelectorAll('.course');															//seleziona tutti i corsi
	for(let i=0; i<courses.length; i++){																		//li scorre
		courses_id.push(courses[i].dataset.course_id);															//lo aggiunge all'array
	}
	return courses_id;
}
/*-----------------------------------------------------------
			Creazione di una subscription
-----------------------------------------------------------*/
function create_subscription(subscription_id,images,name,courses,cost,button_function){
	const new_subscription = document.createElement('div');														//crea un nuovo div
	new_subscription.classList.add('list_element');																//setta classe e id
	new_subscription.dataset.subscription_id=subscription_id;
	const new_subscription_image = document.createElement('div');												//crea l'immaggine e gli da la classe
	new_subscription_image.classList.add('list_element_image');
	for(let i=0;i<images.length;i++){																			//gli attacca le immagini ricevute
		let new_image = document.createElement('img');
		new_image.src=images[i].src;
		new_subscription_image.appendChild(new_image);
	}
	const new_subscription_description = document.createElement('div');											//crea la descrizione e gli da la classe
	new_subscription_description.classList.add('list_element_description');
	const new_description_content = document.createElement('span');												//crea lo span che conterra la descrizione
	const new_name = document.createElement('h4');																//crea il nome e lo setta
	new_name.textContent=name;
	new_description_content.appendChild(new_name);																//attacca allo span il nome
	for(let i=0;i<courses.length;i++){																			//crea un em per ogni corso e lo attacca allo span
		let new_course = document.createElement('em');
		new_course.classList.add('course');
		new_course.dataset.course_id=courses[i].dataset.course_id;
		new_course.textContent=courses[i].textContent;
		new_description_content.appendChild(new_course);
	}
	new_subscription_description.appendChild(new_description_content);											//attacca lo span al div
	const new_subscription_info = document.createElement('div');												//crea l'info e gli da la classe
	new_subscription_info.classList.add('list_element_info');
	const new_info = document.createElement('p');																//crea il costo gli da la classe e valore
	new_info.classList.add('cost');
	new_info.textContent=cost;
	new_subscription_info.appendChild(new_info);																//attacca il costo al div info
	const new_subscription_action = document.createElement('div');												//crea il div action  e classe
	new_subscription_action.classList.add('list_element_action');
	const new_action = document.createElement('img');															//crea il pulsante e classe
	new_action.classList.add('round_button');
	new_subscription_action.appendChild(new_action);															//lo attacca al div action
	switch (button_function){																					//in base al tipo cambia immagine e funzione
		case 'add':
			new_action.src='http://localhost/hw1/Images/Buttons/Add.png';
			new_action.addEventListener('click',add_subscription);
		break;
		case 'remove':
			new_action.src='http://localhost/hw1/Images/Buttons/Remove.png';
			new_action.addEventListener('click',remove_subscription);
		break;
	}
	new_subscription.appendChild(new_subscription_image);														//attacca tutti i div all'elemento nuovo
	new_subscription.appendChild(new_subscription_description);
	new_subscription.appendChild(new_subscription_info);
	new_subscription.appendChild(new_subscription_action);
	return new_subscription;
}
/*-----------------------------------------------------------
			Rimozione dal carello
-----------------------------------------------------------*/
function remove_subscription(){
	const this_subscription = event.currentTarget.parentNode.parentNode;										//seleziona l'abbonamento
	this_subscription.remove();																					//lo rimuove
	update_total_cost();																						//updata il costo
	const cart_subscriptions_id = get_subscriptions_id(cart);													//prende gli id degli abbonamenti nel carello
	if(!cart_subscriptions_id.length){																			//se il carello è vuoto
		cart.parentNode.classList.add('hidden');																//nascone il carello
		empty_cart.classList.remove('hidden');																	//mostra il messaggio carello vuoto
	}
}
/*-----------------------------------------------------------
			Calcolo del costo e update
-----------------------------------------------------------*/
function get_partial_cost(){
	const subscription_cost_list = cart.querySelectorAll(".cost");												//seleziona il costo degli abbonamenti nel carello
	let partial_cost=0;
	for(let i=0;i<subscription_cost_list.length;i++){															//somma tutti i costi
		partial_cost+=parseFloat(subscription_cost_list[i].textContent);
	}
	return partial_cost;
}
function update_total_cost(){
	const partial_cost = get_partial_cost();																	//prende la somma dei costi
	const total_cost = document.querySelector('#total_cost');
	const subscription_duration = parseInt(duration.textContent);												//prende la durata
	total_cost.textContent=(partial_cost*subscription_duration).toFixed(2)+'€';									//aggiorna il costo totale
}
/*-----------------------------------------------------------
				Mostra tutti i corsi
-----------------------------------------------------------*/
function show_all(){
	document.querySelector('#all_subscriptions').classList.remove('hidden');									//mostra tutti gli abbonamenti
	event.currentTarget.remove();
}
/*-----------------------------------------------------------
				Event Listener
-----------------------------------------------------------*/
document.querySelector('.left').addEventListener('click',minus);
document.querySelector('.right').addEventListener('click',plus);
for(let i=0;i<payment_buttons.length;i++){
	payment_buttons[i].addEventListener('click',payment);
}
for(let i=0;i<subscriptions.length;i++){
	if(subscriptions[i].querySelector('.list_element_action img')){
		let button=subscriptions[i].querySelector('.list_element_action img');
		button.addEventListener('click',add_subscription);
	}
}
document.querySelector('#show_all').addEventListener('click',show_all);