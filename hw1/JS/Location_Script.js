/*-----------------------------------------------------------
			variabili globali
-----------------------------------------------------------*/
const map_key='fWuD4P38hJTPB6mwWZjmArcc1bqe2XGw';												//chiave dell'api che carica la mappa
const container = document.querySelector('#map');												//elemento dove caricare la mappa
const full_address=document.querySelector('#full_address').textContent;							//indirizzo completo
const slide_show=document.querySelector('.slide_show');											//slide show
const slides = slide_show.querySelectorAll('.slide_element');									//seleziona tutte le slide
const shortcuts=slide_show.querySelectorAll('.shortcut');										//scorciatoie del slide show
const api='http://localhost/hw1/PHP_Requests/Get_Coordinates.php';								//url per recuperare le coordinate
/*-----------------------------------------------------------
			creazione della mappa
-----------------------------------------------------------*/
function loadmap(container,lat,lng){
		L.mapquest.key=map_key;
		const this_map=L.mapquest.map(
			container, {
				center: [lat, lng],																//la mappa viene centrata a queste coordinate
				layers: L.mapquest.tileLayer('map'),											//stile della mappa (map,satellite,ecc);
				zoom: 15																		//zoom iniziale
			}
		);
        L.marker([lat, lng], {																	//crea un marker alle coordinate lat, lng
          icon: L.mapquest.icons.marker(),
          draggable: false
        }).bindPopup('Ci trovi qui').addTo(this_map);											//al click mostra il messaggio 'ci trovi qui'
}
/*-----------------------------------------------------------
			fetch delle coordinate
-----------------------------------------------------------*/
fetch(api+'?full_address='+full_address).then(onResponse).then(									//esegue la fetch delle coordinate
	function(json){
		loadmap(container,json.lat,json.lng);													//quando arrivono crea la mappa
	}
);
function onResponse(response) {
	return response.json();
}
/*-----------------------------------------------------------
			aggiorna le slide
-----------------------------------------------------------*/
function update_slides(index) {
	for(let i=0;i<slides.length;i++) {															//nasconde tutte le slide
		slides[i].classList.add('hidden');
	}
	for(let i=0;i<shortcuts.length;i++) {
		shortcuts[i].classList.remove('active_shortcut');										//rende tutte le scorciatoie non attive
	}
	if(index<0){																				//se l'index ricevuto è minore di 0 lo setta al massimo
		index=slides.length-1;
	}
	if(index>slides.length-1){																	//se l'index è maggiore dell'index massimo lo setta a 0
		index=0;
	}
	slides[index].classList.remove('hidden');													//mostra la slide con l'indice ricevuto
	shortcuts[index].classList.add('active_shortcut');											//rende la shortcut con l'indice ricevuto attiva
}
/*-----------------------------------------------------------
			gestione degli indici
-----------------------------------------------------------*/
function get_current_slide(){
	for(let i=0;i<slides.length;i++){
		if(!slides[i].classList.contains('hidden')){											//ritorna l'indice della slide non nascosta (attiva)
			return i;
		}			
	}
}
function precedent(){
	const index=get_current_slide()-1;															//mostra la slide precedente
	update_slides(index);
}
function next(){
	const index=get_current_slide()+1;															//mostra la prossima slide
	update_slides(index);
}
function shortcut_jump(){
	const index=event.currentTarget.dataset.slide_index;										//seleziona l'indice della scorciatoia premuta
	update_slides(index);																		//mostra la slide con lo stesso indice
}
/*-----------------------------------------------------------
			eventListener
-----------------------------------------------------------*/
slide_show.querySelector('.precedent').addEventListener('click',precedent);
slide_show.querySelector('.next').addEventListener('click',next);
for(let i=0;i<shortcuts.length;i++){
	shortcuts[i].addEventListener('click',shortcut_jump);
}
/*-----------------------------------------------------------
			funzioni da eseguire all'avvio
-----------------------------------------------------------*/
update_slides(0);