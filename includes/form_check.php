<?php 	
	session_start();

//process login form if submitted
if(isset($_POST['login-form'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if($user->login($username,$password)){ 
		$_SESSION['username'] = $username;
		header('Location: index.php');
		exit;
	
	} else {
		$loginerror[] = 'Wrong username or password.';
	}

}//end if submit

if(isset($_POST['register-form'])){
	$show_modal = false;
	$username = $_POST['username'];
	$password = $_POST['password'];
	$email = $_POST['email'];

	//very basic validation
	if(strlen($_POST['username']) < 3){
		$registererror[] = 'Username is too short.';
		 $show_modal = true;
	} else {
			if($user->user_exists($username)) {
				
			
			$registererror[] = 'Username provided is already in use.';
			$show_modal = true;
			}
		}

	if(strlen($_POST['password']) < 3){
		$registererror[] = 'Password is too short.';
		$show_modal = true;
	}

	if(strlen($_POST['passwordConfirm']) < 3){
		$registererror[] = 'Confirm password is too short.';
		$show_modal = true;
	}

	if($_POST['password'] != $_POST['passwordConfirm']){
		$registererror[] = 'Passwords do not match.';
		$show_modal = true;
	}

	//email validation
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	    $registererror[] = 'Please enter a valid email address';
	    $show_modal = true;
	} else {
		
		if ($user->mail_exists($_POST['email'])) {
			$registererror[] = 'Email provided is already in use.';
			$show_modal = true;
		}
	}
	


	//if no errors have been created carry on
	if(!isset($registererror)){
			$erfolg = false;
		//hash the password
		$hashedpassword = $user->password_save($_POST['password']);
		
		if($user->register_user($username,$hashedpassword,$email)) {
		$erfolg = true;
		};

	}

}

?>