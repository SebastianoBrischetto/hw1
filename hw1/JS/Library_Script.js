const show_max=50;
function onJson(json) {
	const library = document.querySelector('#exercise_library');
	library.innerHTML = '';
	for(let i=0;i<json.results.length;i++){
		let exercise=json.results[i];
		const exercise_div=document.createElement('div');
		exercise_div.classList.add('list_element');
		const name = document.createElement('h3');
		name.textContent=exercise.name;
		const exercise_description=document.createElement('div');
		exercise_description.classList.add('details');
		exercise_description.classList.add('hidden');
		exercise_description.innerHTML=exercise.description;
		exercise_div.appendChild(name);
		exercise_div.appendChild(exercise_description);
		exercise_div.addEventListener('click',show_details);
		library.appendChild(exercise_div);
	}
}

function show_details(){
	const details=event.currentTarget.querySelector('.details');
	if(details.classList.contains('hidden')){
		setTimeout(function(){
			details.classList.remove('hidden');
		},10);
	}else{
		setTimeout(function(){
			details.classList.add('hidden');
		},10);
	}
}
function onResponse(response){
	return response.json();
}
function search(){
	event.preventDefault();
	const type=document.querySelector('select[name="type"]');
	if(type.value==0){
		show_message('Selezionare un tipo di esercizio','error',2000);
	}
	else{
		const rest_url='http://localhost/hw1/PHP_Requests/Get_Library.php?type='+type.value;
		fetch(rest_url).then(onResponse).then(onJson);
	}
}
document.querySelector('select[name="type"]').addEventListener('change',search);
