<?php
session_start();
//define page title
$title = 'Startseite';
	 require('classes/gallery.php');
	include('classes/user.php');

$user = new User($db); 

  $gallery = new GalleryMaker();

    $images = $gallery->getImages($_SESSION['userId']);
    $amountImages = sizeof($images);

//include header template
require('layout/header.php'); 
?>

<div class="container-fluid">
	
	<div class="content">
	
<?php require('layout/nav.php'); ?>

<?php
				//check for any errors
				if(isset($loginerror)){
					foreach($loginerror as $lerror){
						echo '<div class="row">
  <div class="col-md-8"><p class="bg-danger">'.$lerror.'</p></div>';
					}
					?>
					 <script>  $("#logintoggle").click(); </script>
		<?php	} ?>  
		
		 <?php if($show_modal):?>
  <script> $(document).ready(function() { $('#register-modal').modal('show'); });</script>
<?php endif;?>


<?php if($erfolg){ 
					echo '<div class="row">
  <div class="col-md-8"><p class="bg-success">Registration successful. Now Login please.</p></div>';
					?>
	  <script>  $("#logintoggle").click(); </script>
		<?php } ?>	
		
		<div class="row">

	    <div class="col-xs-12 col-sm-8 col-md-12">
			<div class="inner">
				<?php if ($user->is_logged_in())  { ?><h2>Dashboard</h2><?php } else { ?>
				<h2>Startseite</h2><? } ?>
				<hr>
			</div>
		</div>
	</div>
	<?php if ($user->is_logged_in())  { ?>
		<div class="row">

	    <div class="col-md-4">
		    
		    <div class="inner">
			
				<h3>Meine Bilder</h3>
		    </div>
		</div>
	</div>
	
	<div class="row">
                <div class="col-xs-12 col-sm-8 col-md-12">
                    <div class="inner">
            <?php
                                    if ($amountImages > 0) {
                                    ?><div id="index_pics"><?php
                                        foreach ($images as $value) {
                                            echo '<a href="'.$value->orig_path.'" title="'.$value->title.'" data-description="'.$value->description.'" data-gallery="#blueimp-gallery-ownpics"><img src="'.$value->full_thumb.'" class="img_abstand" alt="'.$value->description.'" /></a>';
                                            
                                        }
                                        ?></div><?php
                                    }
                                    else { ?>
	                                   <p><a href="upload.php">Lade jetzt dein erstes Bild hoch</a></p> 
                                   <?php }
                                    ?>
                       </div>
                </div>
            </div>
	<?php  } ?>
	<div class="row">

	    <div class="col-md-4">
		    
		    <div class="inner">
			
				<h3>Populäre Bilder von Flickr</h3>
		    </div>
		</div>
	</div>
	
	<div class="row">
		
		   <div class="col-md-12">
		
		
    <!-- The container for the list of example images -->
    <div id="links" class="col-md-12"></div>
    <br>
</div>

	</div>

	</div>
	
	</div>
</div>

<?php 
//include header template
require('layout/footer.php'); 
?>
