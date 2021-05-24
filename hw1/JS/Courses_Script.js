/*-----------------------------------------------------------
				Variabili globali
-----------------------------------------------------------*/
const php_api='http://localhost/hw1/PHP_Requests/Update_Favorites.php';													//pagina php a cui effettuare la fetch
const favorites_list=document.querySelector('#favorites').querySelector('.list_row');									//tutte le varie liste(preferiti,fitness,ecc)
const fitness_list= document.querySelector('#fitness').querySelector('.list_row');
const swimming_list = document.querySelector('#swimming').querySelector('.list_row');
const wellness_list = document.querySelector('#wellness').querySelector('.list_row');
const martial_arts_list = document.querySelector('#martial_arts').querySelector('.list_row');
const searchbar = document.querySelector('input[name="searchbar"]');													//l'input di ricerca																		//aggiungo un event listener all'input di ricerca
/*-----------------------------------------------------------
				Aggiunta di event listener 
-----------------------------------------------------------*/
function addEvents(list,type){
	let button;
	switch(type){																										//in base al tipo di evento cambia la funzione da aggiungere
		case 'add':
			button=add_favorite;
		break;
		case 'remove': 
			button=remove_favorite;
		break;
	}
	for(let i =0;list.length>i;i++){																					//scorre fra gli elementi della lista ricevuta
		list[i].addEventListener('mouseenter', details_expand);															//aggiunge gli event listener
		list[i].addEventListener('mouseleave', details_shrink);
		list[i].querySelector('.round_button').addEventListener('click', button);
	}
}
/*-----------------------------------------------------------
				Nasconde le liste vuote
-----------------------------------------------------------*/
function hide_if_empty(list){
	if(list.querySelectorAll('.list_element').length){																	//se la lista ricevuta ha almeno 1 elemento
		list.parentNode.classList.remove('hidden');																		//la mostra
	}else{																												//se non ha alcun elemento
		list.parentNode.classList.add('hidden');																		//la nasconde
	}
}
/*-----------------------------------------------------------
			Creazione di nuovi blocchi di contenuto
-----------------------------------------------------------*/
function create_course(image,title,description,button_function,id){
	const new_courses_element = document.createElement('div');															//crea il div principale che contiene tutto
	new_courses_element.classList.add('list_element');																	//aggiunge classe per il css
	new_courses_element.addEventListener('mouseenter', details_expand);													//aggiunge event listener all'entrata del mouse
	new_courses_element.addEventListener('mouseleave', details_shrink);													//aggiunge event listener all'uscita del mouse
	const new_img = document.createElement('img');																		//crea elemento immagine
	new_img.classList.add('list_element_background');																	//aggiunge classe per il css
	const new_div = document.createElement('div');																		//crea il div dei dettagli (titolo,descrizione,ecc)
	const new_title = document.createElement('h3');																		//elemento titolo
	const new_description = document.createElement ('p');																//elemento descrizione
	const new_button = document.createElement ('img');																	//pulsante per gestione dei prefiti(add/remove)
	const new_note= document.createElement('p');																		//nota
	new_div.classList.add('transition_overlay');																		//aggiunge tutte le classi a questi elementi
	new_description.classList.add('description');
	new_description.classList.add('hidden');
	new_button.classList.add('round_button');
	new_button.classList.add('hidden');
	new_note.classList.add('note');
	new_note.classList.add('hidden');
	switch (button_function){																							//in base al funzionamento richiesto imposta gli elementi
		case 'add':																										//se per inserire nei preferiti
			new_button.src='images/buttons/add.png';
			new_button.addEventListener('click', add_favorite);
			new_note.textContent='Aggiungi ai preferiti';
		break;
		case 'remove':																									//se per cancellare dai preferiti
			new_button.src='images/buttons/remove.png';	
			new_button.addEventListener('click', remove_favorite);
			new_note.textContent='Rimuovi dai preferiti';
		break;
	}
	new_courses_element.dataset.course_id=id;																			//imposta l'id del corso nel dataset	
	new_courses_element.appendChild(new_img);																			//imposta gli elementi e li attacca ai div
	new_img.src=image;
	new_courses_element.appendChild(new_div);
	new_div.appendChild(new_title);
	new_title.textContent=title;
	new_div.appendChild(new_description);
	new_description.textContent=description;
	new_div.appendChild(new_button);
	new_div.appendChild(new_note);
	return new_courses_element;																							//ritorna il corso appena creato
}
/*-----------------------------------------------------------
			Ritorna gli id dei corsi preferiti
-----------------------------------------------------------*/
function get_courses_id(list) {
	let courses_id=[];																									//crea un array dove salvare gli id dei corsi preferiti
	const courses= list.querySelectorAll('.list_element');																//seleziona tutti i corsi preferiti
	for(let i=0; i<courses.length; i++){																				//scorre fra i corsi preferiti
		courses_id.push(courses[i].dataset.course_id);																	//aggiunge l'id del corso alla lista degli id preferiti
	}
	return courses_id;																									//ritorna un array con gli id dei corsi preferiti
}
/*-----------------------------------------------------------
			Inserimeno/Rimozione dai preferiti
-----------------------------------------------------------*/
function add_favorite() {
	const add_div=event.currentTarget.parentNode.parentNode;															//seleziona il div da aggiungere nei preferiti
	const id = add_div.dataset.course_id;																				//prendo l'id del corso
	const courses_id = get_courses_id(favorites_list);																	//recupero gli id dei corsi già aggiunti ai preferiti
	if(courses_id.includes(id)){																						//se la lista dei preferiti contiene già il corso
		show_message('Corso gia aggiunto','error',1000);																//mostro un messaggio di avvertimento e blocco l'aggiunta
	}
	else {																												//se invece il corso non appartiene ai preferiti
		const url= php_api+'?add_course='+id;																			//effettuo la fetch
		fetch(url).then(onResponse).then(
			function (json){
				if(json.success){																						//se ha avuto successo
					const image = add_div.querySelector('img').src;														//seleziona i valori del corso da aggiungere
					const title = add_div.querySelector('h3').textContent;
					const description = add_div.querySelector('.description').textContent;
					const new_course=create_course(image,title,description,'remove',id);								//crea un clone del div nella lista dei preferiti
					favorites_list.appendChild(new_course);																//lo attacco alla lista dei preferiti
					show_message('Corso aggiunto','success',1000);														//mostra un messaggio di successo
					hide_if_empty(favorites_list);																		//mostra la lista se non è vuota
				}else{																									//se non ha avuto successo
					show_message(json.error,'error',1000);																//mostra un messaggio di errore
				}
			}
		);
	}
}
function remove_favorite() {																											
	const remove_div=event.currentTarget.parentNode.parentNode;															//seleziona il div da rimuovere
	const id = remove_div.dataset.course_id;																			//prendo l'id del corso
	const url= php_api+'?remove_course='+id;
	fetch(url).then(onResponse).then(																					//effettuo la fetch
		function (json){
			if(json.success){																							//se ha avuto successo
				remove_div.remove();																					//rimuove il div
				hide_if_empty(favorites_list);																			//nasconde la lista se vuota
			}else{																										//se non ha avuto successo
				show_message(json.error,'error',1000);																	//mostra un messaggio di errore
			}
		}
	);
}
function onResponse(response) {
  return response.json();
}
/*-----------------------------------------------------------
				Ricerche
-----------------------------------------------------------*/
function find_all(){
	let text=searchbar.value.toLowerCase();																				//seleziona la stringa su cui fare la ricerca
	find(favorites_list,text);																							//effettua la ricerca su ogni tipo
	find(fitness_list,text);
	find(swimming_list,text);
	find(wellness_list,text);
	find(martial_arts_list,text);	
}
function find(list,search) {
	const find_in_list=list.querySelectorAll('.list_element');															//seleziona tutti i corsi della lista ricevuta
	for(var i=0; i<find_in_list.length; i++){																			//scorre fra i corsi
		if(find_in_list[i].querySelector('h3').textContent.toLowerCase().includes(search)){								//se il titolo comprende la parola cercata
			find_in_list[i].classList.remove('hidden');																	//lo mostra
		}
		else{																											//se il titolo non comprende la parola cercata
			find_in_list[i].classList.add('hidden');																	//lo nasconde
		}
	}
}
/*-----------------------------------------------------------
			event Listener
-----------------------------------------------------------*/
searchbar.addEventListener('keyup', find_all);	
addEvents(favorites_list.querySelectorAll('.list_element'),'remove');
addEvents(fitness_list.querySelectorAll('.list_element'),'add');
addEvents(swimming_list.querySelectorAll('.list_element'),'add');
addEvents(wellness_list.querySelectorAll('.list_element'),'add');
addEvents(martial_arts_list.querySelectorAll('.list_element'),'add');
/*-----------------------------------------------------------
			funzioni da eseguire all'avvio
-----------------------------------------------------------*/
hide_if_empty(favorites_list);
hide_if_empty(fitness_list);
hide_if_empty(swimming_list);
hide_if_empty(wellness_list);
hide_if_empty(martial_arts_list);