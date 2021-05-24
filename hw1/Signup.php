<?php
	/*--------------------------------------------------
					Gestione sessione
	----------------------------------------------------*/
	include_once 'Default_Elements.php';
	if($active_session)																										//se è presente una sessione
	{																														
			header('location: Homepage.php');																				//reindirizza alla homepage
			exit;
	}
	/*--------------------------------------------------
						SIGNUP
	----------------------------------------------------*/
	include_once 'PHP_Requests/DB_Data.php';																				//include i dati per la connessione al db
	$conn = mysqli_connect($DB_DATA['host'], $DB_DATA['user'], $DB_DATA['password'], $DB_DATA['name']);						//crea connessione al db
	if (isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['birthdate']) && 									//se sono presenti tutti i campi per la registrazione
		isset($_POST['email']) &&  isset($_POST['password']) && isset($_POST['c_password'])){
		$name = mysqli_real_escape_string($conn, $_POST['name']);															//escape delle stringhe
		$surname = mysqli_real_escape_string($conn, $_POST['surname']);
		$birthdate = mysqli_real_escape_string($conn, date('Y-m-d',strtotime($_POST['birthdate'])));						//conversione in un formato utilizzabile dal db
		$email = mysqli_real_escape_string($conn, $_POST['email']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {																	//se il formato dell'email è coretto
			$query = 'SELECT email FROM user WHERE email = "'.$email.'"';													//effettua il controllo di unicità della email
			$res = mysqli_query($conn,$query) or die(mysqli_error($conn));
			if(mysqli_num_rows($res)>0){																					//se l'email non è univoca
				$error_mail='E-mail già in uso';
			}
		} else {																											//se il formato dell'email è errato
			$error_mail='E-mail non valida';
		}
		if (strlen($password)<8){																							//se la password non è lunga almeno 8 caratteri
			$error_pwd = 'Inserire una password di almeno 8 caratteri';
		}else{																												//se la password è lunga almeno 8 caratteri
			if (strcmp($password, $_POST['c_password'])!=0) {																//se le password non coincidono
				$error_pwd = 'Le password non coincidono';
			}
        }
		if($error_mail==null && $error_pwd==null){																			//se non è presente nessun errore
			$query = 'INSERT INTO user (email,name,surname,birth_date,password) VALUES("'									//effettua l'inserimento nel db
					 .$email.'","'.$name.'","'.$surname.'","'.$birthdate.'","'.$password.'")';
			$res = mysqli_query($conn,$query);
			if($res){																										//se da esito positivo
				$_SESSION['session']=array('email'=>$email,'admin'=>0);														//crea una nuova sessione
				header('Location: HOMEPAGE.php');																			//reindirizza alla homepage
				mysqli_close($conn);
				exit;
			}
		}
	}
	/*--------------------------------------------------
						LOGIN
	----------------------------------------------------*/
	if (isset($_POST['log_email']) && isset($_POST['log_password'])){														//se sono presenti tutti i campi per il login
		$email = mysqli_real_escape_string($conn, $_POST['log_email']);														//escape delle stringhe
		$password = mysqli_real_escape_string($conn, $_POST['log_password']);
		$query = 'SELECT * FROM user WHERE email="'.$email.'" AND password = "'.$password.'"';								//query per il controllo di email e password
		$res = mysqli_query($conn,$query) or die(mysqli_error($conn));
		if(mysqli_num_rows($res)>0){																						//se torna almeno 1 risultato
			$_SESSION['session']=array('email'=>$email,'admin'=>mysqli_fetch_assoc($res)['admin']);							//crea una nuova sessioen
			header('Location: HOMEPAGE.php');																				//reindirizza alla homepage
			mysqli_close($conn);
			exit;
		}else{																												//se i entrambi i campi di login non sono presenti
			$error_login='Username e/o password errati';
		}
	}else if(isset($_POST['log_email']) || isset($_POST['log_password'])){													//se solo uno dei campi non è presente
		$error_login='Inserire entrambi i campi';
	}
	mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Triscele Fitness - Login</title>
		<?php default_head(); ?>
		<link rel='stylesheet' href='CSS/Signup_Style.css'>
		<script src="JS/Signup_Script.js" defer="true"></script>
	</head>
	<body>
		<header class="white">
			<div id="page_info" class="white">
				<a class="logo" href="Homepage.php"><img class="logo" src="Images/Icons/logo.png"/></a>
			</div>
		</header>
		<main>
			<section>
				<form method='POST' action='Signup.php' id='Login'>
					<h1>Log In</h1>
					<label class='list_element'>E-mail<input type='text' name='log_email' 
						<?php
							if(isset($_POST['log_email'])){																	//se il login non è andato a buon fine
								echo 'value='.$_POST['log_email'];															//reinserisce il campo email
							} 
						?>>
						<p class='error'>
							<?php
								if (isset($error_login)) {																	//se è presente un errore di login lo mostra
									echo $error_login;
								}
							?>
						</p>
					</label>
					<label class='list_element'>Password<input type='password' name='log_password'
						<?php
							if(isset($_POST['log_password'])){																//se il login non è andato a buon fine
								echo 'value='.$_POST['log_password'];														//reinserisce il campo password
							}
						?>>
					</label>
					<div class='list_element'>
						<input class="buttons" type='submit' value='Accedi'>
					</div>
				</form>
				<form method='POST' action='Signup.php' id='Signup'>
					<h1>Sign In</h1>
					<label class='list_element'>Nome<input type='text' name='name'
						<?php 
							if(isset($_POST['name'])){																		//se il signup non è andato a buon fine
							echo 'value='.$_POST['name'];																	//reinserisce il campo nome
						} 
					?>>
						<p class='error' data-status=404></p>
					</label>
					<label class='list_element'>Cognome<input type='text' name='surname'
						<?php 
							if(isset($_POST['surname'])){																	//se il signup non è andato a buon fine
								echo 'value='.$_POST['surname'];															//reinserisce il campo username
							} 
						?>>
						<p class='error' data-status=404></p>
					</label>
					<label class='list_element'>Data di nascita<input type='date' name='birthdate'
						<?php 
							if(isset($_POST['birthdate'])){																	//se il signup non è andato a buon fine
								echo 'value='.$_POST['birthdate'];															//reinserisce il campo birthdate
							} 
						?>>
						<p class='error' data-status=404></p>
					</label>
					<label class='list_element'>E-Mail<input type='text' name='email'
						<?php 
							if(isset($_POST['email'])){																		//se il signup non è andato a buon fine
								echo 'value='.$_POST['email'];																//reinserisce il campo email
							} 
						?>>
						<p class='error' data-status=404>
							<?php 
								if (isset($error_mail)) {																	//se è presente un errore email lo mostra
									echo $error_mail;
								}
							?>
						</p>
					</label>
					<label class='list_element'>Password<input type='password' name='password'
						<?php 
							if(isset($_POST['password'])){																	//se il signup non è andato a buon fine
								echo 'value='.$_POST['password'];															//reinserisce il campo password
							} 
						?>>
						<p class='error' data-status=404>
							<?php 
								if (isset($error_pwd)) {																	//se è presente un errore password lo mostra
									echo $error_pwd;
								}
							?>
						</p>
					</label>
					<label class='list_element'>Conferma password<input type='password' name='c_password'
						<?php 
							if(isset($_POST['c_password'])){																//se il signup non è andato a buon fine
								echo 'value='.$_POST['c_password'];															//reinserisce il campo conferma password
							} 
						?>>
						<p class='error' data-status=404></p>
					</label>
					<div class='list_element'>
						<input class="buttons" type='submit' value='Registrati'></input>
					</div>
				</form>
			</section>
		</main>
		<?php
			default_footer('white');
		?>
	</body>
</html>