<?php 
    //define page title
$title = 'Suche';
  
  include('classes/user.php');

$user = new User($db); 


require('classes/gallery.php');

  $gallery = new GalleryMaker();
  
   $query = $_GET['query']; 
 
    // gets value sent over search form
     
    $min_length = 3;
    // you can set minimum length of the query if you want
     
        
require('layout/header.php'); 
?>

    <div class="container-fluid">
        <div class="content">
            <?php require('layout/nav.php'); ?>

            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-12">
                    <div class="inner">
                        <h1>Suchergebnisse <?php echo 'f&uuml;r ' . '"'.$query. '"'; ?></h1>
                        <hr>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-10">
                    <div class="inner">
	                    <?php
   if(strlen($query) >= $min_length){ // if query length is more or equal minimum length then
         
        $query = htmlspecialchars($query); 
        // changes characters used in html to their equivalents, for example: < to &gt;
         
     
        // makes sure nobody uses SQL injection
        
        
        $images = $gallery->searchImages($query,$_SESSION['userId']);
        
        $amountImages = sizeof($images);
        
                                    if ($amountImages > 0) {
                                    ?><div id="uploadpics"><?php
                                        foreach ($images as $value) {
                                            echo '<a href="'.$value->orig_path.'" data-description="'.$value->description.'" title="'.$value->title.'" data-gallery><img src="'.$value->thumb_path.'" class="img_abstand" alt="'.$value->description.'" /></a>';
                                            
                                        }
                                        ?></div><?php
                                    }
                                    else {
	                                    echo 'No results';
                                    }
                                    
   
            
            } 
        
        else{ // if there is no matching rows do following
            echo "No results";
        }
?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php 
//include header template
require('layout/footer.php'); 
?>