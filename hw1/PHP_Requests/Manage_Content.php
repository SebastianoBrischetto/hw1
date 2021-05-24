<?php
	include_once 'Check_Session.php';
	if(!$is_admin){
		header('location: Homepage.php');
		exit;
	}
	function check_inputs(){
		if(isset($_POST['columns']) && isset($_POST['values']) && count($_POST['columns'])==count($_POST['values'])){
			return true;
		}else{
			return false;
		}
	}
	function check_keys(){
		if(isset($_POST['key_names']) && isset($_POST['key_values']) && count($_POST['key_names'])==count($_POST['key_values'])){
			return true;
		}else{
			return false;
		}
	}
	if(isset($_POST['request']) && isset($_POST['table'])){
		include_once 'DB_Data.php';
		$conn = mysqli_connect($DB_DATA['host'], $DB_DATA['user'], $DB_DATA['password'], $DB_DATA['name']);
		$table_name=mysqli_real_escape_string($conn,$_POST['table']);
		switch($_POST['request']){
			case 'add':
				if(check_inputs()){
					$data_names='';
					$data_values='';
					$data=array_combine($_POST['columns'],$_POST['values']);
					foreach($data as $name=>$value){
						$name_escaped=mysqli_real_escape_string($conn,$name);
						if (isset($_FILES[$name])) {
							$file=$_FILES[$name];
							$extension = pathinfo($file['name'],PATHINFO_EXTENSION);
							$new_name='Images/Uploads/'.uniqid($table_name,true).'.'.$extension;
							move_uploaded_file($file['tmp_name'], '../'.$new_name);
							$value_escaped=$new_name;
						}else{
							$value_escaped=mysqli_real_escape_string($conn,$value);
						}
						$data_names=$data_names.$name_escaped.',';
						$data_values=$data_values.'"'.$value_escaped.'",';
					}
					$data_names=' ('.substr($data_names,0,-1).') ';
					$data_values=' ('.substr($data_values,0,-1).')';
					$query = 'INSERT INTO '.$table_name.$data_names.'VALUES'.$data_values;
				}
			break;
			case 'update':
				if(check_inputs() && check_keys()){
					$query = 'UPDATE '.$table_name.' SET ';
					$data=array_combine($_POST['columns'],$_POST['values']);
					foreach($data as $name=>$value){
						if(empty($value)){continue;}
						$name_escaped=mysqli_real_escape_string($conn,$name);
						if (isset($_FILES[$name])) {
							$file=$_FILES[$name];
							$extension = pathinfo($file['name'],PATHINFO_EXTENSION);
							$new_name='Images/Uploads/'.uniqid($table_name,true).'.'.$extension;
							move_uploaded_file($file['tmp_name'], '../'.$new_name);
							$value_escaped=$new_name;
						}else{
							$value_escaped=mysqli_real_escape_string($conn,$value);
						}
						$query=$query.$name_escaped.'="'.$value_escaped.'",';
					}
					$query = substr($query,0,-1).' WHERE ';
					$keys=array_combine($_POST['key_names'],$_POST['key_values']);
					foreach($keys as $name=>$value){
						$name_escaped=mysqli_real_escape_string($conn,$name);
						$value_escaped=mysqli_real_escape_string($conn,$value);
						$query=$query.$name_escaped.'="'.$value_escaped.'" AND ';
					}
					$query = substr($query,0,-5);
				}
			break;
			case 'remove':
				if(check_keys()){
					$query = 'DELETE FROM '.$table_name.' WHERE ';
					$keys=array_combine($_POST['key_names'],$_POST['key_values']);
					foreach($keys as $name=>$value){
						$name_escaped=mysqli_real_escape_string($conn,$name);
						$value_escaped=mysqli_real_escape_string($conn,$value);
						$query=$query.$name_escaped.'="'.$value_escaped.'" AND ';
					}
					$query = substr($query,0,-5);
				}
			break;
		}
		if(!$query){
			echo json_encode(array('success'=>false,'message'=>'Uno o più dati non presenti'));	
		}else{
			$res=mysqli_query($conn,$query);
			if($res){
				echo json_encode(array('success'=>true,'message'=>'Operazione avvenuta con successo'));
			}else{
				echo json_encode(array('success'=>false,'message'=>'Errore con inserimento nel database'));
			}
		}
		mysqli_close($conn);
		exit;
	}else{
		echo json_encode(array('success'=>false,'message'=>'Uno o più dati non presenti'));
	}
?>