<?php
require_once('includes/config.php');
class GalleryMaker {
	
	private $db;
	
	public function __construct() {
		$this->db = new DBConnector();
	}
	
	public function getImages($user_id) {
		$paths = array();
		$stmt = $this->db->prepare('SELECT * FROM gallery WHERE user_id= :user_id ORDER BY id DESC');
		$stmt->bindValue(':user_id',$user_id);
		$stmt->execute();
		

		while($row = $stmt->fetchAll(PDO::FETCH_OBJ)) {
			return $row;
		}

			$stmt=null;
		}
		
		public function searchImages($query,$user_id) {
		$paths = array();
		$stmt = $this->db->prepare('SELECT * FROM gallery
            WHERE user_id = :user_id AND ((`title` LIKE :query) OR (`description` LIKE :query)) ');
		$stmt->bindValue(':query','%'.$query.'%');
		$stmt->bindValue(':user_id',$user_id);
		$stmt->execute();
		
		
		while($row = $stmt->fetchAll(PDO::FETCH_OBJ)) {
			return $row;
		}
			$stmt=null;
		}
		
	public function upload() {
		
	
		 
		 for ($i = 0; $i < count($_FILES['image']['name']); $i++) {//loop to get individual element from the array
			 	 
				 
// Access the $_FILES global variable for this specific file being uploaded
// and create local PHP variables from the $_FILES array of information
$current_img = $_FILES["image"]["name"][$i]; // The file name
$fileTmpLoc = $_FILES["image"]["tmp_name"][$i]; // File in the PHP tmp folder
$fileSize = $_FILES["image"]["size"][$i]; // File size in bytes
$fileErrorMsg = $_FILES["image"]["error"][$i]; // 0 for false... and 1 for true
$kaboom = explode(".", $current_img); // Split file name into an array using the dot
$extension = end($kaboom); // Now target the last array element to get the file extension
// START PHP Image Upload Error Handling --------------------------------------------------

if (!$fileTmpLoc) { // if file not chosen
    echo "ERROR: Please browse for a file before clicking the upload button.";
 
    exit();
} else if($fileSize > 5242880) { // if file size is larger than 5 Megabytes
    echo "ERROR: Your file was larger than 5 Megabytes in size.";
    unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
    
    exit();
} else if (!preg_match("/.(gif|jpg|png)$/i", $current_img) ) {
     // This condition is only if you wish to allow uploading of specific file types    
     echo "ERROR: Your image was not .gif, .jpg, or .png.";
     unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
    
     exit();
} else if ($fileErrorMsg == 1) { // if file upload error key is equal to 1
    echo "ERROR: An error occured while processing the file. Try again.";
     
    exit();
}

date_default_timezone_set("Europe/Berlin");
$time = date("fYhis");
$new_image = uniqid() . $time;

if (!file_exists("images/". $_SESSION["userId"])) {
      mkdir("images/". $_SESSION["userId"]);
}

$originalImage  = "images/".$_SESSION['userId']."/". $new_image . "-orig" . "." . $extension;
$thumbImage   = "images/".$_SESSION['userId']."/". $new_image . "-thumb" . "." . $extension;

$action = move_uploaded_file($fileTmpLoc, $originalImage);

		
		if(!empty($_POST['description'][$i])) {
       $description = $_POST['description'][$i]; 
   	
		}
		else {
			$description = NULL;
		}
		
	if(!empty($_POST['title'][$i])) {
       $title = $_POST['title'][$i]; 
       }
       else {
	       $title = reset($kaboom);
       }


			$stmt = $this->db->prepare("INSERT INTO gallery (user_id, orig_path,thumb_path, description, title) VALUES ('".$_SESSION['userId']."', '".$originalImage."', '".$thumbImage."', '".$description."' , '".$title."' )");
			$stmt->execute();
			$stmt=null;

$full_height = 250;
$full_width = 250;

list($orig_width, $orig_height, $type) = getimagesize($originalImage);

switch ($type) {
    case '2':
            $image_create_func = 'imagecreatefromjpeg';
            $image_save_func = 'imagejpeg';
            break;

    case '3':
            $image_create_func = 'imagecreatefrompng';
         // $image_save_func = 'imagepng';
         // The quality is too high with "imagepng"
         // but you need it if you want to allow transparency
            $image_save_func = 'imagejpeg';
            break;

    case '1':
            $image_create_func = 'imagecreatefromgif';
            $image_save_func = 'imagegif';
            break;
}

$thumb_width = $orig_width;
$thumb_height = $orig_height;


if ($thumb_height > $full_height) {
        $thumb_width = ($full_height / $thumb_height) * $thumb_width;
        $thumb_height = $full_height;
    }

    # wider
    if ($thumb_width > $full_width) {
        $thumb_height = ($full_width / $thumb_width) * $thumb_height;
        $thumb_width = $full_width;
    }


$image_source = $image_create_func($originalImage);

$new_full_thumb = imagecreatetruecolor($thumb_width, $thumb_height);
     
     imagecopyresampled($new_full_thumb, $image_source, 0, 0, 0, 0, $thumb_width, $thumb_height, $orig_width, $orig_height);
     
     $image_save_func($new_full_thumb, $thumbImage);
     
    imagedestroy($new_full_thumb);
	
}
	
}
}

?>