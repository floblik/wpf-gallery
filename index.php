<?php

session_start();

//define page title
$title = 'Home';

// get the classes
include('classes/gallery.php');
include('classes/user.php');

// init the classes
$user    = new User();
$gallery = new GalleryMaker();

// sql query for images and count return array
if ($user->is_logged_in()) {
	$images       = $gallery->getImages($_SESSION['userId']);
	$amountImages = sizeof($images);
}
//include header template
require('layout/header.php');

?>
    <div class="container-fluid">
        <div class="content">
            <?php
            require('layout/nav.php');
            ?>
            
       

            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-12">
                    <div class="inner">
                        <h2><?php echo $title; ?></h2>
                        <hr>
                    </div>
                </div>
            </div>
            
            <?php if ($user->is_logged_in()) { ?>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="inner">
                        <h3>Meine Bilder</h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-11">
                    <div class="inner">
                        <?php
                            if ($amountImages > 0) {
                        ?>

                        <div class="grid">
                            <?php
                                    foreach ($images as $value) {
                                        echo '<a href="' . $value->orig_path . '" title="' . $value->title . '" data-description="' . $value->description . '" data-gallery="#blueimp-gallery-indexpics"><img src="' . $value->thumb_path . '" class="img_abstand" alt="' . $value->title . '" /></a>';
                                        
                                    }
                            ?>
                        </div>
                        
                        <?php
                            } else { ?>

                        <p><a href="manage.php">Lade jetzt dein erstes Bild hoch.</a></p><?php
                            }
                        ?>
                    </div>
                </div>
            </div>
            <?php } // /loggedin ?> 

            <div class="row">
                <div class="col-md-4">
                    <div class="inner">
                        <h3>Populäre Bilder von Flickr</h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div id="links" class="col-md-12"><div class="loading"></div></div><br>
                </div>
            </div>
        </div>
    </div><?php
    //include footer template
    require('layout/footer.php');
    ?>
</body>
</html>
