<?php
session_start();

include('classes/gallery.php');

//define page title
$title = 'Einstellungen';

include('classes/user.php');

$user = new User($db);

//check if already logged in move to home page
if (!$user->is_logged_in()) {
    header('Location: index.php');
}

$images       = $user->getAvatar($_SESSION['userId']);
$amountImages = sizeof($images);

if (isset($_POST['update-user'])) {
    
    
    $email     = $_POST['email'];
    $password  = $_POST['password'];
    $password2 = $_POST['passwordConfirm'];
    
    if (!empty($password) && !empty($password2)) {
        if (strlen($password) < 3) {
            $updateerror[] = 'Das Passwort ist zu kurz. Mindestens 3 Zeichen.';
        }
        
        if (strlen($password2) < 3) {
            $updateerror[] = 'Das Confirm Passwort ist zu kurz. Mindestens 3 Zeichen.';
            
        }
        
        if ($password != $password2) {
            $updateerror[] = 'Die Passw&ouml;rter stimmen nicht &uuml;berein..';
            
        }
    }
    
    //email validation
    
    if ($email != $user->get_mail($_SESSION['userId'])) {
        
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $updateerror[] = 'Bitte eine valide E-Mail Adresse eingeben.';
            
        } else {
            
            if ($user->mail_exists($email) && $email != $user->get_mail($_SESSION['userId'])) {
                $updateerror[] = 'Die E-Mail Adresse ist bereits in Verwendung.';
            }
        }
        
        }

        //if no errors have been created carry on
        if (!isset($updateerror)) {
            $updateerfolg = false;
            //hash the password
            if (!empty($password)) {
                $hashedpassword = $user->password_save($password);
                
                if ($user->change_password($hashedpassword, $_SESSION['userId'])) {
                    $updateerfolg = true;
                    
                }
            }
            
             if ($email != $user->get_mail($_SESSION['userId'])) {
	             
            if ($user->update_user($email, $_SESSION['userId'])) {
                $updateerfolg = true;
            }
           } 
            
    	}
}

//include header template
require('layout/header.php');
?>

    <div class="container-fluid">
        <div class="content settings-page">
            <?php
require('layout/nav.php');
?>

            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-12">
                    <div class="inner">
                        <h1><?php echo $title ?></h1>
                        <hr>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-2 col-md-offset-1">
                    <div class="inner">
                        <?php
if ($amountImages > 0) {
    echo '<div id="uploadavatar"><img src="' . $images . '" class="img-circle avatar" /></div>';
} else {
    echo '<div id="uploadavatar"><img src="https://www.gravatar.com/avatar/83e4eb193d866390749a946d35c0503f?s=100&d=mm&r=g" class="img-circle avatar"></div>';
}
?>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="inner">
                        <h4>Avatar bearbeiten</h4>
                        
                          <form class="avatarForm" name="avatarForm" enctype="multipart/form-data" action="" method="post">
	 <input id="avatar" name="avatar" type="file" accept="image/jpg,image/png,image/jpeg,image/gif" />
	

             
                 <div class="upload-msg"> </div>
              

                            <div class="form-group pull-left avatarbutton">
                                <input type="submit" id="avatarbutton" name="send" value="Edit Avatar" class="btn btn-default btn-sm">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="inner">
                    <div class="col-xs-12 col-sm-4 col-md-5 col-md-offset-1">
                        <form role="form" class="form-horizontal" method="post" id="editUser" action="<?php echo $_SERVER['PHP_SELF'];
?>" autocomplete="off">
                            <?php
if ($updateerfolg) {
    echo '<p class="bg-success">Daten aktualisiert.</p>';
}

if (isset($updateerror)) {
    foreach ($updateerror as $uerror) {
        echo '<p class="bg-danger">' . $uerror . '</p>';
    }
}

?>

                            <div class="form-group">
                                <label class="control-label col-sm-3 settingsform" for="username">Username:</label>

                                <div class="col-sm-9">
                                    <input type="text" name="username" id="username" readonly="readonly" class="form-control input-lg" placeholder="Username" value="<?php
if (isset($_SESSION['user'])) {
    echo $_SESSION['user'];
}
?>" tabindex="1">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3" for="email">Email:</label>

                                <div class="col-sm-9">
                                    <input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Addresse" value="<?php
echo $user->get_mail($_SESSION['userId']);
?>" tabindex="2">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3" for="password">Passwort:</label>

                                <div class="col-sm-9">
                                    <input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="3"> <span class="help-inline">Nur wenn Passwort geändert werden soll</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-3" for="passwordConfirm">Passwort (x2):</label>

                                <div class="col-sm-9">
                                    <input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Confirm Passwort" tabindex="4">
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
    </div>
<?php
//include footer template
require('layout/footer.php');
?>
