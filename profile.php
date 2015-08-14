<?php
    session_start();
    require('classes/gallery.php');
//define page title
$title = 'Profil';

    include('classes/user.php');

$user = new User($db); 

//check if already logged in move to home page
if( !$user->is_logged_in() ){ header('Location: index.php'); } 

    $images = $user->getAvatar($_SESSION['userId']);
    $amountImages = sizeof($images);

//include header template
require('layout/header.php'); 
?>

    <div class="container-fluid">
        <div class="content profile-page">
            <?php require('layout/nav.php'); ?>

            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-12">
                    <div class="inner">
                        <h1>Profil</h1>
                        <hr>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-2">
                    <?php
                                    if ($amountImages > 0) {
                                    echo '<img src="'.$images.'" class="img-circle avatar" />';
                                }
                                else {
                                    echo '<img src="https://www.gravatar.com/avatar/83e4eb193d866390749a946d35c0503f?s=100&d=mm&r=g" class="img-circle avatar">';
                                }
                    ?>
                </div>

                <div class="col-xs-6 col-md-4">
                    <h2><?php echo $_SESSION['username']?></h2>

                    <p>Kontakt: <a href="mailto:<?php echo $user->get_mail($_SESSION['userId'])?>"><?php echo $user->get_mail($_SESSION['userId'])?></a></p>

                    <p>Mitglied seit: <?php $originalDate = $user->get_register_date($_SESSION['userId']); $newDate = date("d.m.Y", strtotime($originalDate)); echo $newDate; ?></p>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-2">
                    <div class="inner">
                        <p class="avatartext"><a href="settings.php">Avatar bearbeiten?</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php 
            //include header template
            require('layout/footer.php'); 
            ?>
