function show_message(message,type,duration){
	if(document.querySelector('.message_window')){											//se sta venendo già mostrato un messaggio
		document.querySelector('.message_window').remove();									//lo elimina
	}	
	const message_window=document.createElement('div');										//crea il messaggio è gli inposta lo stile in base a che tipo è
	message_window.classList.add('message_window');
	switch(type){
		case 'error':
			message_window.classList.add('error_message');
		break;
		case 'warning':
			message_window.classList.add('warning_message');
		break;
		case 'success':
			message_window.classList.add('success_message');
		break;
		case 'info':
			message_window.classList.add('info_message');
		break;
	}
	message_window.innerHTML=message;														//setta il messaggio
	document.querySelector('body').appendChild(message_window);								//lo attacca
	setTimeout(function(){																	//alla fine della durata
		message_window.classList.add('fadeout');											//aggiunge la classe per il fadeout
		setTimeout(function(){																//alla fine del fadeout
			message_window.remove();														//lo elimina
		},1000);
	},duration);
}
function details_expand(){
	const sub_div=event.currentTarget.querySelector('.transition_overlay');												//seleziona il div dei dettagli
	sub_div.classList.add('open');																						//modifica i parametri degli elementi per mostrarlo
	sub_div.querySelector('.description').classList.remove('hidden');
	if(sub_div.querySelector('.round_button')){
		sub_div.querySelector('img').classList.remove('hidden');
	}
	sub_div.querySelector('.note').classList.remove('hidden');
}
function details_shrink(){
	const sub_div=event.currentTarget.querySelector('.transition_overlay');												//seleziona il div dei dettagli
	sub_div.classList.remove('open');
	sub_div.querySelector('.description').classList.add('hidden');
	if(sub_div.querySelector('.round_button')){
		sub_div.querySelector('img').classList.add('hidden');
	}
	sub_div.querySelector('.note').classList.add('hidden');
}