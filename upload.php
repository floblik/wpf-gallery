<?php
    session_start();
    require('classes/gallery.php');
    
        include('classes/user.php');

$user = new User($db); 

//check if already logged in move to home page
if( !$user->is_logged_in() ){ header('Location: index.php'); } 

    $gallery = new GalleryMaker();
    if(isset( $_POST['send'] )) {
	
	    
        $gallery->upload();
   
	  }
	  
    
    $images = $gallery->getImages($_SESSION['userId']);
    $amountImages = sizeof($images);

 
//define page title
$title = 'Upload';

//include header template
require('layout/header.php'); 
?>

    <div class="container-fluid">
        <div class="content">
            <?php require('layout/nav.php'); ?>

            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-12">
                    <div class="inner">
                        <h2>Upload</h2>
                        <hr>
                    </div>
                </div>
            </div>
             <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-6">
                    <div class="inner">
 <?php
				//check for any errors
				if(isset($registererror)){
					foreach($registererror as $rerror){
						echo '<p class="bg-danger">'.$rerror.'</p>';
					}
				}

				?>
				</div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-4">
                    <div class="inner">
                       
                            <p class="hello">Hallo <?= $_SESSION['user'];?>
                            !</p><br>

                            <p class="clear">Du hast momentan <?=$amountImages?>
                             Bilder in deiner Galerie.</p>

                            <p class="new">Neues Bild hinzufügen:</p>

                            <form method="post" id="uploadForm" name="uploadForm" enctype="multipart/form-data">

                 
                                 <div id="filediv"><ul><li><input name="image[]" type="file" id="image" accept="image/jpg,image/png,image/jpeg,image/gif"/></li></ul></div>
                                            
          <div class="form-group pull-right margintop">
				
                    <input type="button" id="add_more" class="upload btn btn-default" value="Add More Files"/>
         
                                <input type="submit" id="upload" class="btn btn-success" name="send">
          </div>
                            </form>
                           
                    </div>
                </div>
            </div>
           
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-12">
                    <div class="inner">
	                 
            <?php
                                    if ($amountImages > 0) {
                                    ?><div id="uploadpics"><?php
                                        foreach ($images as $value) {
                                            echo '<a href="'.$value->orig_path.'" data-description="'.$value->description.'" title="'.$value->title.'" data-gallery><img src="'.$value->thumb_path.'" class="img_abstand" alt="'.$value->description.'" /></a>';
                                            
                                        }
                                        ?></div><?php
                                    }
                                    ?>
                       </div>
                </div>
            </div>
              
        </div>
    </div><?php 
    //include header template
    require('layout/footer.php'); 
    ?>