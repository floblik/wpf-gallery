<?php
require('includes/config.php');
class User {

   private $db;
	
	public function __construct() {
		$this->db = new DBConnector();
	}
    
     public function password_save($password) {
	    
	    $options = array("cost" => 10, "salt" => uniqid());
	    
	    $hash = password_hash($password, PASSWORD_DEFAULT);
	  
		return $hash;
        
        }

	public function password_valid($password,$hashed) {

		if (password_verify($password, $hashed)) {
			return true;
			}
		
		return false;
	}


	private function get_user_hash($username){	

		try {
			$stmt = $this->db->prepare('SELECT password FROM users WHERE username = :username');
			$stmt->execute(array('username' => $username));
			
			$row = $stmt->fetch();
			return $row['password'];

		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	
	private function get_user_id($username){	

		try {
			$stmt = $this->db->prepare('SELECT user_id FROM users WHERE username = :username');
			$stmt->execute(array('username' => $username));
			
			$row = $stmt->fetch();
			return $row['user_id'];

		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	
	public function get_register_date($user_id){	

		try {
			$stmt = $this->db->prepare('SELECT timestamp FROM users WHERE user_id = :user_id');
			$stmt->execute(array('user_id' => $user_id));
			
			$row = $stmt->fetch();
			return $row['timestamp'];

		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}
	
	public function user_exists($username) {
			try {
			$stmt = $this->db->prepare('SELECT COUNT(username) AS num FROM users WHERE username = :username');
			$stmt->bindValue(':username',$username);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if($row['num'] > 0){
				return true;
			}
			else {
			return false;
			}

		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
		
	}
	
	public function mail_exists($email) {

			try {
			$stmt = $this->db->prepare('SELECT COUNT(email) AS num FROM users WHERE email = :email');
			$stmt->bindValue(':email',$email);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if($row['num'] > 0){
				return true;
			}
			else {
			return false;
			}

		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}

		
	}
	
		public function get_mail($user_id) {

			try {
			$stmt = $this->db->prepare('SELECT email FROM users WHERE user_id = :user_id');
			$stmt->execute(array('user_id' => $user_id));
			$row = $stmt->fetch();
			
			return $row['email'];
			
		}

		 catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}

		
	}
	
		public function getAvatar($user_id) {
			
		$stmt = $this->db->prepare('SELECT avatar FROM users WHERE user_id= :user_id');
		$stmt->execute(array('user_id' => $user_id));
		$row = $stmt->fetch();
		
		return $row['avatar'];
			
		$stmt=null;
		}
		
		
	
	public function upload_avatar() {
	
		// Access the $_FILES global variable for this specific file being uploaded
// and create local PHP variables from the $_FILES array of information
$current_img = $_FILES["image"]["name"]; // The file name
$fileTmpLoc = $_FILES["image"]["tmp_name"]; // File in the PHP tmp folder
$fileErrorMsg = $_FILES["image"]["error"]; // 0 for false... and 1 for true
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
     echo "ERROR: Your image was not .gif, .jpg, or .png";
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

$avatarImage   = "images/".$_SESSION['userId']."/". $new_image . "-avatar" . "." . $extension;

$action = move_uploaded_file($fileTmpLoc, $avatarImage);


			$stmt = $this->db->prepare("UPDATE users SET avatar = '".$avatarImage."' WHERE user_id = '".$_SESSION['userId']."'");
			$stmt->execute();
			$stmt=null;

list($orig_width, $orig_height, $type) = getimagesize($avatarImage);

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

$crop_width= 100;
$crop_height = 100;


$image_source = $image_create_func($avatarImage);
$new_image = imagecreatetruecolor($crop_width , $crop_height);

    imagecopyresampled($new_image, $image_source, 0, 0, 0, 0, $crop_width, $crop_height, $orig_width, $orig_height);
    
     $image_save_func($new_image, $avatarImage);
     
    imagedestroy($new_image);
	
}

	
	public function register_user($username,$hashedpassword,$email) {
		
		try {

			//insert into database with a prepared statement
			$stmt = $this->db->prepare('INSERT INTO users (username,password,email,timestamp) VALUES (:username, :password, :email,NOW())');
			$stmt->bindValue(':username',$username);
			$stmt->bindValue(':password',$hashedpassword);
			$stmt->bindValue(':email',$email);
			$stmt->execute(array(
				':username' => $username,
				':password' => $hashedpassword,
				':email' => $email
			));
			
				

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		   return $registererror[] = $e->getMessage();
		}
	return true;	
	}
	
	
	public function update_user($email,$user_id) {
		
		try {

			//insert into database with a prepared statement
			$stmt = $this->db->prepare('UPDATE users SET email = :email WHERE user_id = :user_id');
			$stmt->bindValue(':email',$email);
			$stmt->bindValue(':user_id',$user_id);
			$stmt->execute(array(
				':email' => $email,
				':user_id' => $user_id
			));
			
				

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		   return $registererror[] = $e->getMessage();
		}
	return true;	
	}
	
	public function change_password($hashedpassword,$user_id) {
		
		try {

			//insert into database with a prepared statement
			$stmt = $this->db->prepare('UPDATE users SET password = :password WHERE user_id = :user_id');
			$stmt->bindValue(':password',$hashedpassword);
			$stmt->bindValue(':user_id',$user_id);
			$stmt->execute(array(
				':password' => $hashedpassword,
				':user_id' => $user_id
			));
			
				

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		   return $registererror[] = $e->getMessage();
		}
	return true;	
	}



	public function login($username,$password){

		$hashed = $this->get_user_hash($username);
		
		$userid = $this->get_user_id($username);

        // User has been successfully verified, lets sessionize his user id so we can refer to later
        $_SESSION['userId'] = $userid;
        $_SESSION['user'] = $username;
    
		
		if($this->password_valid($password,$hashed) == 1){
		    
		    $_SESSION['loggedin'] = true;
		    
		    
		    return true;
		} 	
	}
		
	public function logout(){
		session_destroy();
		
	}

	public function is_logged_in(){
		if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
			return true;
		}		
	}
	
	
}


?>