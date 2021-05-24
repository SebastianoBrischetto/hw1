const existing_div=document.querySelector('#existing_content');
const new_content=document.querySelector('select[name="new_content"]');
const existing_content=document.querySelector('select[name="existing_content"');
/*----------------------------------------------------------
			Creazione di un elemento select
-----------------------------------------------------------*/
function create_select(array,name){
	const select = document.createElement('select');
	select.name=name;
	const not_clickable = document.createElement('option');
	not_clickable.value='none';
	not_clickable.selected=true;
	not_clickable.disabled=true;
	not_clickable.hidden=true;
	not_clickable.textContent='Seleziona un valore';
	select.appendChild(not_clickable);
	if(array){
		for(let i=0;i<array.length;i++){
			const option = document.createElement('option');
			option.value=array[i];
			option.textContent=array[i];
			select.appendChild(option);
		}
	}
	return select;
}
/*----------------------------------------------------------
			Creazione di un elemento select
-----------------------------------------------------------*/
function manage_content(){
	const formdata=new FormData();
	const request_type=event.currentTarget.dataset.request_type;
	formdata.append('request',request_type);
	const table_name=event.currentTarget.dataset.table;
	formdata.append('table',table_name);
	if(request_type=='add' || request_type=='update'){
		const inputs=document.querySelectorAll('.is_input');
		for(let i=0;i<inputs.length;i++){
			formdata.append('columns[]',inputs[i].name);
			formdata.append('values[]',inputs[i].value);
			if(inputs[i].classList.contains('is_file')){
				formdata.append(inputs[i].name,inputs[i].files[0]);
			}
		}
	}
	if(request_type=='remove'||request_type=='update'){
		const keys=document.querySelectorAll('.is_key');
		for(let i=0;i<keys.length;i++){
			formdata.append('key_names[]',keys[i].name);
			formdata.append('key_values[]',keys[i].value);
		}
	}
	fetch('http://localhost/hw1/PHP_Requests/Manage_Content.php',{
		method:'post',
		body:formdata
	}).then(onResponse)
		.then(function(json){
			if(json.success){
				show_message(json.message,'success',1000);
			}else{
				show_message(json.message,'error',1000);
			}
		}
	);
}
/*----------------------------------------------------------
			fetch
-----------------------------------------------------------*/
function get_form(){
	const target=event.currentTarget;
	fetch('http://localhost/hw1/PHP_Requests/Get_Content_Specifics.php?table_name='+target.value).then(onResponse).then(json_form).then(
		function(list){
			target.parentNode.appendChild(list);
			if(target===existing_content){
				replace_input();
			}
		}
	);
}
function replace_input(){
	const type=existing_content.value;
	fetch('http://localhost/hw1/PHP_Requests/Get_Existing_Content.php?table_name='+type).then(onResponse).then(json_key);	
}
function get_content(){
	const table_name=existing_content.value;
	const target=event.currentTarget;
	const key=target.name;
	fetch('http://localhost/hw1/PHP_Requests/Get_Existing_Content.php?table_name='+table_name+'&key='+key+'&value='+encodeURI(target.value)).then(onResponse).then(json_content);
}
/*----------------------------------------------------------
					Response
-----------------------------------------------------------*/
function onResponse(response) {
	return response.json();
}
/*----------------------------------------------------------
					Json
-----------------------------------------------------------*/
function json_form(json){
	if(document.querySelector('.list')){
		document.querySelector('.list').remove();
	}
	const list=document.createElement('div');
	list.classList.add('list');
	list.classList.add('light_grey');
	for(let i=0;i<json.length;i++){
		const new_row=document.createElement('div');
		new_row.classList.add('list_element');
		list.appendChild(new_row);
		const show_name=document.createElement('p');
		show_name.textContent=json[i].name;
		new_row.appendChild(show_name);
		let input;
		switch(json[i].type){
			case 'AUTO_INCREMENT':
				input=document.createElement('input');
				input.value=json[i].values;
				input.disabled=true;
			break;
			case 'TYPE':
				input=create_select(['fitness','swimming','wellness','martial_arts'],json[i].name);
				input.classList.add('is_input');
			break;
			case 'LONG_TEXT':
				input=document.createElement('textarea');
				input.name=json[i].name;
				input.classList.add('is_input');
			break;
			case 'IMAGE':
				input=document.createElement('input');
				input.name=json[i].name;
				input.type='file';
				input.accept='jpg, png, jpeg';
				input.classList.add('is_input');
				input.classList.add('is_file');
			break;
			case 'SELECT':
				input=create_select(json[i].values,json[i].name);
				input.classList.add('is_input');
			break;
			default:
				input=document.createElement('input');
				input.name=json[i].name;
				input.type='text';
				input.classList.add('is_input');
		}
		new_row.appendChild(input);
	}
	const button=document.createElement('a');
	button.classList.add('buttons');
	button.textContent='Aggiungi';
	button.dataset.table=new_content.value;
	button.dataset.request_type='add';
	button.addEventListener('click',manage_content);
	list.appendChild(button);
	return(list);			
}
function json_key(json){
	const key=Object.keys(json)[0];
	if(json[key].length){
		let values=[];
		for(let i=0;i<json[key].length;i++){
			values.push(json[key][i]);
		}
		const input=create_select(values,key);
		input.classList.add('is_key');
		input.addEventListener('change',get_content);
		const names=existing_div.querySelectorAll('p');
		for(let i=0;i<names.length;i++){
			if(names[i].textContent===key){
				let element=names[i].parentNode;
				element.querySelector('input').remove();
				element.appendChild(input);
			}
		}
		existing_div.querySelector('.buttons').remove();
		const button_update=document.createElement('a');
		button_update.classList.add('buttons');
		button_update.textContent='Modifica';
		button_update.dataset.table=existing_content.value;
		button_update.dataset.request_type='update';
		button_update.addEventListener('click',manage_content);
		const button_remove=document.createElement('a');
		button_remove.classList.add('buttons');
		button_remove.textContent='Rimuovi';
		button_remove.dataset.table=existing_content.value;
		button_remove.dataset.request_type='remove';
		button_remove.addEventListener('click',manage_content);
		input.parentNode.parentNode.appendChild(button_update);
		input.parentNode.parentNode.appendChild(button_remove);
	}else{
		show_message('La tabella è vuota, aggiungi qualcosa prima','warning',2000);
	}
}
function json_content(json){
	const keys=Object.keys(json);
	for(let i=0;i<keys.length;i++){
		let element=document.querySelector('[name="'+keys[i]+'"]');
		if(element.type!=='file'){
			element.value=json[keys[i]];
		}
	}
}
/*----------------------------------------------------------
			Creazione di un elemento select
-----------------------------------------------------------*/
new_content.addEventListener('change',get_form);
existing_content.addEventListener('change',get_form);