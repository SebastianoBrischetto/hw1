addEvents(document.querySelectorAll('.list_element'));
/*-----------------------------------------------------------
				Aggiunta di event listener 
-----------------------------------------------------------*/
function addEvents(list){
	for(let i =0;list.length>i;i++){																					//scorre fra gli elementi della lista ricevuta
		list[i].addEventListener('mouseenter', details_expand);	
		list[i].addEventListener('mouseleave', details_shrink);
	}
}
/*-----------------------------------------------------------
			event listener
-----------------------------------------------------------*/
addEvents(document.querySelectorAll('.list_element'));

