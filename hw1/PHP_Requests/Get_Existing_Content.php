<?php
	include_once 'Check_Session.php';
	if(!$is_admin){
		header('location: Homepage.php');
		exit;
	}
	if(isset($_GET['table_name'])){
		include_once 'DB_Data.php';
		$conn = mysqli_connect($DB_DATA['host'], $DB_DATA['user'], $DB_DATA['password'], $DB_DATA['name']);
		$table=mysqli_real_escape_string($conn,$_GET['table_name']);
		if(isset($_GET['key']) && isset($_GET['value'])){
			$key=mysqli_real_escape_string($conn,$_GET['key']);
			$value=mysqli_real_escape_string($conn,$_GET['value']);
			$query='SELECT * FROM '.$table.' WHERE '.$key.'="'.$value.'"';
			$res=mysqli_query($conn,$query) or die(mysqli_error($conn));
			echo json_encode(mysqli_fetch_assoc($res));
		}else{
			$query='SELECT column_name FROM information_schema.columns ic WHERE ic.table_name="'.$table.'" AND ic.column_key="PRI"';
			$res_keys=mysqli_query($conn,$query) or die(mysqli_error($conn));
			$array=[];
			while($key = mysqli_fetch_assoc($res_keys)){
				$query='SELECT '.$key['column_name'].' FROM '.$table;
				$res=mysqli_query($conn,$query) or die (mysqli_error($conn));
				while($values=mysqli_fetch_assoc($res)){
					$array[$key['column_name']][]=$values[$key['column_name']];
				}
			}
			echo json_encode($array);
		}
		mysqli_close($conn);
	}
?>