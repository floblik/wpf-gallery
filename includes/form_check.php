<?php

//process login form if submitted
if (isset($_POST['login-form'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if ($user->login($username, $password)) {
        $_SESSION['username'] = $username;
        header('Location: index.php');
        exit;
        
    } else {
        $loginerror[] = 'Wrong username or password.';
    }
    
} //end if submit

if (isset($_POST['register-form'])) {
    $show_modal = false;
    $username   = $_POST['username'];
    $password   = $_POST['password'];
    $email      = $_POST['email'];
    
    if (strlen($_POST['username']) < 3) {
        $registererror[] = 'Der Username ist zu kurz. Mindestens 3 Zeichen.';
        $show_modal      = true;
    } else {
        if ($user->user_exists($username)) {
            
            
            $registererror[] = 'Der Username wird schon verwendet.';
            $show_modal      = true;
        }
    }
    
    if (strlen($_POST['password']) < 3) {
        $registererror[] = 'Das Passwort ist zu kurz. Mindestens 3 Zeichen.';
        $show_modal      = true;
    }
    
    if (strlen($_POST['passwordConfirm']) < 3) {
        $registererror[] = 'Das Confirm Passwort ist zu kurz. Mindestens 3 Zeichen.';
        $show_modal      = true;
    }
    
    if ($_POST['password'] != $_POST['passwordConfirm']) {
        $registererror[] = 'Die Passwörter stimmen nicht überein.';
        $show_modal      = true;
    }
    
    //email validation
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $registererror[] = 'Bitte eine g&uuml;ltige E-Mail eingeben.';
        $show_modal      = true;
    } else {
        
        if ($user->mail_exists($_POST['email'])) {
            $registererror[] = 'Die eingegebene E-Mail wird schon verwendet.';
            $show_modal      = true;
        }
    }
    
    
    
    //if no errors have been created carry on
    if (!isset($registererror)) {
        $erfolg         = false;
        //hash the password
        $hashedpassword = $user->password_save($_POST['password']);
        
        if ($user->register_user($username, $hashedpassword, $email)) {
            $erfolg = true;
        };
        
    }
    
}

?>