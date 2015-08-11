<?php
    session_start();
    require('classes/gallery.php');
//define page title
$title = 'Einstellungen';

    include('classes/user.php');

$user = new User($db); 

//check if already logged in move to home page
if( !$user->is_logged_in() ){ header('Location: index.php'); } 

    if (isset($_POST['send'])) {
        $user->upload_avatar();
    }
    $images = $user->getAvatar($_SESSION['userId']);
    $amountImages = sizeof($images);

if(isset($_POST['update-user'])){
	

	$email = $_POST['email'];
	$password = $_POST['password'];
	$password2 = $_POST['passwordConfirm'];

	if(!empty($password) && !empty($password2)) {
	if(strlen($password) < 3){
		$registererror[] = 'Password is too short.';
	}

	if(strlen($password2) < 3){
		$registererror[] = 'Confirm password is too short.';
	
	}

	if($password != $password2){
		$registererror[] = 'Passwords do not match.';
	
	}
	}

	//email validation
	
	if($email != $user->get_mail($_SESSION['userId'])) {
		
		
	if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
	    $registererror[] = 'Please enter a valid email address';

	} else {
		
		if ($user->mail_exists($_POST['email']) && $email != $user->get_mail($_SESSION['userId'])) {
			$registererror[] = 'Email provided is already in use.';
		}
	}
	


	//if no errors have been created carry on
	if(!isset($registererror)){
			$erfolg = false;
		//hash the password
		if(!empty($password)) {
		$hashedpassword = $user->password_save($password);
		
			if($user->change_password($hashedpassword,$_SESSION['userId'])) {
				$erfolg = true;
			};
		}
		
		if($user->update_user($email,$_SESSION['userId'])) {
		$erfolg = true;
			};

		
	}

}
}

//include header template
require('layout/header.php'); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
    <title></title>
</head>

<body>
    <div class="container-fluid">
        <div class="content settings-page">
            <?php require('layout/nav.php'); ?>

            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-12">
                    <div class="inner">
                        <h1>Einstellungen</h1>
                        <hr>
                    </div>
                </div>
            </div>
  <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-2 col-md-offset-1">
	                <div class="inner">
                    <?php
                                    if ($amountImages > 0) {
                                    echo '<img src="'.$images.'" class="img-circle avatar" />';
                                }
                                else {
                                    echo '<img src="https://www.gravatar.com/avatar/83e4eb193d866390749a946d35c0503f?s=100&d=mm&r=g" class="img-circle avatar">';
                                }
                    ?>
	                </div>
                </div>
                 <div class="col-xs-12 col-sm-4 col-md-3">
                <div class="inner">
                    <h4>Avatar bearbeiten</h4>

                    <form method="post" action="settings.php" enctype="multipart/form-data">
                        <input type="file" name="image" accept="image/jpg,image/png,image/jpeg,image/gif">
                        
                         <div class="form-group pull-left avatarbutton">
                        <input type="submit" name="send" value="Edit Avatar" class="btn btn-default btn-sm"> 
                         </div>
                    </form>
                </div>
            </div>
  </div>
  
 
                         
            <div class="row">
	            <div class="inner">
                <div class="col-xs-12 col-sm-4 col-md-5 col-md-offset-1">
                    <form role="form" class="form-horizontal" method="post" id="editUser" action="<?php echo $_SERVER['PHP_SELF']?>" autocomplete="off">
	                    <?php if($erfolg){ 
					echo '<p class="bg-success">Daten aktualisiert.</p>';
					?>
		<?php } ?>	
                        <?php
                                        //check for any errors
                                        if(isset($registererror)){
                                            foreach($registererror as $rerror){
                                                echo '<p class="bg-danger">'.$rerror.'</p>';
                                            }
                                        }

                                        ?>

                        <div class="form-group">
	                        <label class="control-label col-sm-3 settingsform" for="username">Username:</label>
	                        <div class="col-sm-9">
                            <input type="text" name="username" id="username" readonly="readonly" class="form-control input-lg" placeholder="User Name" value="<?php if(isset($_SESSION['user'])){ echo $_SESSION['user']; } ?>" tabindex="1">
	                        </div>
                        </div>

                        <div class="form-group">
	                           <label class="control-label col-sm-3" for="email">Email:</label>
	                        <div class="col-sm-9">
                            <input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Address" value="<?php echo $user->get_mail($_SESSION['userId']); ?>" tabindex="2">
                        </div>
                        </div>

                        <div class="form-group">
	                         <label class="control-label col-sm-3" for="password">Passwort:</label>
	                        <div class="col-sm-9">
                            <input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="3">
                            <span class="help-inline">Nur wenn Passwort geändert werden soll</span>
                        </div>
                        </div>

                        <div class="form-group">
	                         <label class="control-label col-sm-3" for="passwordConfirm">Passwort (x2):</label>
	                        <div class="col-sm-9">
                            <input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Confirm Password" tabindex="4">
                        </div>
                        </div>

                        <div class="form-group pull-right col-sm-3">
                            <input type="submit" id="updateUser" name="update-user" value="Bearbeiten" class="btn btn-success"> 
                        </div>
                    </form>
                </div>
	            </div>
            </div>
    </div>
</body>
</html>
