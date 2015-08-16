<?php
	
require_once('includes/config.php');

class GalleryMaker
{
    
    private $db;
    
    public function __construct()
    {
        $this->db = new DBConnector();
    }
    
    
    public function getImages($user_id)
    {
        $paths = array();
        $stmt  = $this->db->prepare('SELECT * FROM gallery WHERE user_id= :user_id ORDER BY id DESC');
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();
        
        
        while ($row = $stmt->fetchAll(PDO::FETCH_OBJ)) {
            return $row;
        }
        
        $stmt = null;
    }
    
    public function searchImages($query, $user_id)
    {
        $paths = array();
        $stmt  = $this->db->prepare('SELECT * FROM gallery
            WHERE user_id = :user_id AND ((`title` LIKE :query) OR (`description` LIKE :query)) ');
        $stmt->bindValue(':query', '%' . $query . '%');
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();
        
        
        while ($row = $stmt->fetchAll(PDO::FETCH_OBJ)) {
            return $row;
        }
        $stmt = null;
    }
    
    public function upload($data, $userid, $description, $title)
    {
        
        
        // Access the $_FILES global variable for this specific file being uploaded
        // and create local PHP variables from the $_FILES array of information
        $current_img  = $data["name"]; // The file name
        $fileTmpLoc   = $data["tmp_name"]; // File in the PHP tmp folder
        $fileSize     = $data["size"]; // File size in bytes
        $fileErrorMsg = $data["error"]; 
        $kaboom       = explode(".", $current_img); // Split file name into an array using the dot
        $extension    = end($kaboom); // Now target the last array element to get the file extension
        
        if ($fileSize > 5242880) { // if file size is larger than 5 Megabytes
            echo "<span id='error' class='bg-danger'>Error: Die Datei war gr&ouml;sser als 5 MB.</span>";
            unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
            
            exit();
        } else if (!preg_match("/.(gif|jpg|png)$/i", $current_img)) {    
            echo "<span id='error' class='bg-danger'>Error: Es sind nur die Formate .jpeg, .jpg, .gif und .png erlaubt.</span>";
            unlink($fileTmpLoc); 
            
            exit();
        } else if ($fileErrorMsg == 1) { // if file upload error key is equal to 1
            echo "<span id='error' class='bg-danger'>Error: Es gab einen Fehler beim Upload. Bitte noch einmal versuchen.</span>";
            
            exit();
        }
        
        date_default_timezone_set("Europe/Berlin");
        $time      = date("fYhis");
        $new_image = uniqid() . $time;
        
        if (!file_exists("images/" . $userid)) {
            mkdir("images/" . $userid);
        }
        
        $originalImage = "images/" . $userid . "/" . $new_image . "-orig" . "." . $extension;
        $thumbImage    = "images/" . $userid . "/" . $new_image . "-thumb" . "." . $extension;
        
        $action = move_uploaded_file($fileTmpLoc, $originalImage);
        
        
        if (!empty($description)) {
            $description = $description;
            
        } else {
            $description = NULL;
        }
        
        if (!empty($title)) {
            $title = $title;
        } else {
            $title = reset($kaboom);
        }
        
        
        $stmt = $this->db->prepare("INSERT INTO gallery (user_id, orig_path,thumb_path, description, title) VALUES ('" . $userid . "', '" . $originalImage . "', '" . $thumbImage . "', '" . $description . "' , '" . $title . "' )");
        $stmt->execute();
        $stmt = null;
        
        $full_height = 250;
        $full_width  = 250;
        
        list($orig_width, $orig_height, $type) = getimagesize($originalImage);
        
        switch ($type) {
            case '2':
                $image_create_func = 'imagecreatefromjpeg';
                $image_save_func   = 'imagejpeg';
                break;
            
            case '3':
                $image_create_func = 'imagecreatefrompng';
                $image_save_func   = 'imagejpeg';
                break;
            
            case '1':
                $image_create_func = 'imagecreatefromgif';
                $image_save_func   = 'imagegif';
                break;
        }
        
        $thumb_width  = $orig_width;
        $thumb_height = $orig_height;
        
        
        if ($thumb_height > $full_height) {
            $thumb_width  = ($full_height / $thumb_height) * $thumb_width;
            $thumb_height = $full_height;
        }
        
        # wider
        if ($thumb_width > $full_width) {
            $thumb_height = ($full_width / $thumb_width) * $thumb_height;
            $thumb_width  = $full_width;
        }
        
        
        $image_source = $image_create_func($originalImage);
        
        $new_full_thumb = imagecreatetruecolor($thumb_width, $thumb_height);
        
        imagecopyresampled($new_full_thumb, $image_source, 0, 0, 0, 0, $thumb_width, $thumb_height, $orig_width, $orig_height);
        
        $image_save_func($new_full_thumb, $thumbImage);
        
        imagedestroy($new_full_thumb);
        
        echo '<a href="' . $originalImage . '" data-description="' . $description . '" title="' . $title . '" data-gallery="blueimp-gallery-uploadpics"><img src="' . $thumbImage . '" class="img_abstand" alt="' . $title . '"></a>';
        
        
    }
    
    
    
}

?>