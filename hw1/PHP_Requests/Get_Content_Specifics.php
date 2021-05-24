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
		$query= 'SELECT column_name, column_comment FROM information_schema.COLUMNS ic WHERE ic.table_name="'.$table.'"';
		$res=mysqli_query($conn,$query) or die(mysqli_error($conn));
		while($column = mysqli_fetch_assoc($res)){
			$values=null;
			$column_name=$column['column_name'];
			$type=$column['column_comment'];
			switch (true){
				case strpos($type,'INDEX_OF')!==false:
					$reference_table=str_replace('INDEX_OF','',$type);
					$type='SELECT';
					$res_INDEX_OF=mysqli_query($conn,'SELECT * FROM '.$reference_table);
					while($value = mysqli_fetch_assoc($res_INDEX_OF)){
						$values[]=$value[$column_name];
					}
				break;
				case $type==='AUTO_INCREMENT':
					$res_next_value=mysqli_query($conn,'SELECT '.$column_name.' FROM '.$table.' ORDER BY '.$column_name.' DESC LIMIT 1');
					if(mysqli_num_rows($res_next_value)>0){
						$values=mysqli_fetch_row($res_next_value)[0]+1;
					}else{
						$values=1;
						
					}
				break;
				case $type==='':
					$type='default';
				break;
			}
			if($values){
				$array[]=array(
					'name'=>$column_name,
					'type'=>$type,
					'values'=> $values
				);
			}else{
				$array[]=array(
					'name'=>$column_name,
					'type'=>$type
				);
			}
		}
		echo json_encode($array);
		mysqli_close($conn);
	}
?>