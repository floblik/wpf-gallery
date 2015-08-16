<?php
	
require_once('includes/config.php');

class User
{
    
    private $db;
    
    public function __construct()
    {
        $this->db = new DBConnector();
    }
    
    public function password_save($password)
    {
        
        $options = array(
            "cost" => 10,
            "salt" => uniqid()
        );
        
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        return $hash;
        
    }
    
    public function password_valid($password, $hashed)
    {
        
        if (password_verify($password, $hashed)) {
            return true;
        }
        
        return false;
    }
    
    
    private function get_user_hash($username)
    {
        
        try {
            $stmt = $this->db->prepare('SELECT password FROM users WHERE username = :username');
            $stmt->execute(array(
                'username' => $username
            ));
            
            $row = $stmt->fetch();
            return $row['password'];
            
            $stmt = null;
            
        }
        catch (PDOException $e) {
            echo '<p class="bg-danger">' . $e->getMessage() . '</p>';
        }
    }
    
    private function get_user_id($username)
    {
        
        try {
            $stmt = $this->db->prepare('SELECT user_id FROM users WHERE username = :username');
            $stmt->execute(array(
                'username' => $username
            ));
            
            $row = $stmt->fetch();
            return $row['user_id'];
            
             $stmt = null;
            
        }
        catch (PDOException $e) {
            echo '<p class="bg-danger">' . $e->getMessage() . '</p>';
        }
    }
    
    public function get_register_date($user_id)
    {
        
        try {
            $stmt = $this->db->prepare('SELECT timestamp FROM users WHERE user_id = :user_id');
            $stmt->execute(array(
                'user_id' => $user_id
            ));
            
            $row = $stmt->fetch();
            return $row['timestamp'];
            
             $stmt = null;
            
        }
        catch (PDOException $e) {
            echo '<p class="bg-danger">' . $e->getMessage() . '</p>';
        }
    }
    
    public function user_exists($username)
    {
        try {
            $stmt = $this->db->prepare('SELECT COUNT(username) AS num FROM users WHERE username = :username');
            $stmt->bindValue(':username', $username);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row['num'] > 0) {
                return true;
            } else {
                return false;
            }
            
            $stmt = null;
        }
        catch (PDOException $e) {
            echo '<p class="bg-danger">' . $e->getMessage() . '</p>';
        }
        
    }
    
    public function mail_exists($email)
    {
        
        try {
            $stmt = $this->db->prepare('SELECT COUNT(email) AS num FROM users WHERE email = :email');
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row['num'] > 0) {
                return true;
            } else {
                return false;
            }
            $stmt = null;
        }
        catch (PDOException $e) {
            echo '<p class="bg-danger">' . $e->getMessage() . '</p>';
        }
        
        
    }
    
    public function get_mail($user_id)
    {
        
        try {
            $stmt = $this->db->prepare('SELECT email FROM users WHERE user_id = :user_id');
            $stmt->execute(array(
                'user_id' => $user_id
            ));
            $row = $stmt->fetch();
            
            return $row['email'];
            $stmt = null;
        }
        
        catch (PDOException $e) {
            echo '<p class="bg-danger">' . $e->getMessage() . '</p>';
        }
        
        
    }
    
    public function getAvatar($user_id)
    {
        
        $stmt = $this->db->prepare('SELECT avatar FROM users WHERE user_id= :user_id');
        $stmt->execute(array(
            'user_id' => $user_id
        ));
        $row = $stmt->fetch();
        
        return $row['avatar'];
        
        $stmt = null;
    }
    
    
    
    public function upload_avatar($data, $userid)
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
        
        $avatarImage = "images/" . $userid . "/" . $new_image . "-avatar" . "." . $extension;
        
        $action = move_uploaded_file($fileTmpLoc, $avatarImage);
        
        
        $stmt = $this->db->prepare("UPDATE users SET avatar = '" . $avatarImage . "' WHERE user_id = '" . $userid . "'");
        $stmt->execute();
        $stmt = null;
        
        list($orig_width, $orig_height, $type) = getimagesize($avatarImage);
        
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
        
        $crop_width  = 200;
        $crop_height = 200;
        
        // calculating the part of the image to use for thumbnail
if ($orig_width > $orig_height) {
  $y = 0;
  $x = ($orig_width - $orig_height) / 2;
  $smallestSide = $orig_height;
} else {
  $x = 0;
  $y = ($orig_height - $orig_width) / 2;
  $smallestSide = $orig_width;
}
        
        
        $image_source = $image_create_func($avatarImage);
        $new_image    = imagecreatetruecolor($crop_width, $crop_height);
        
        imagecopyresampled($new_image, $image_source, 0, 0, $x, $y, $crop_width, $crop_height, $smallestSide, $smallestSide);
        
        $image_save_func($new_image, $avatarImage);
        
        imagedestroy($new_image);
        
        echo '<img src="' . $avatarImage . '" class="img-circle avatar" /> ';
        
        
    }
    
    
    public function register_user($username, $hashedpassword, $email)
    {
        
        try {
            
            //insert into database with a prepared statement
            $stmt = $this->db->prepare('INSERT INTO users (username,password,email,timestamp) VALUES (:username, :password, :email, NOW())');
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':password', $hashedpassword);
            $stmt->bindValue(':email', $email);
            $stmt->execute(array(
                ':username' => $username,
                ':password' => $hashedpassword,
                ':email' => $email
            ));
            
            $stmt = null;
        }
        catch (PDOException $e) {
            return $registererror[] = $e->getMessage();
        }
        return true;
    }
    
    
    public function update_user($email, $user_id)
    {
        
        try {
            
            //insert into database with a prepared statement
            $stmt = $this->db->prepare('UPDATE users SET email = :email WHERE user_id = :user_id');
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->execute(array(
                ':email' => $email,
                ':user_id' => $user_id
            ));
            
            $stmt = null;
			
        }
        catch (PDOException $e) {
            return $registererror[] = $e->getMessage();
        }
        return true;
    }
    
    public function change_password($hashedpassword, $user_id)
    {
        
        try {
            
            //insert into database with a prepared statement
            $stmt = $this->db->prepare('UPDATE users SET password = :password WHERE user_id = :user_id');
            $stmt->bindValue(':password', $hashedpassword);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->execute(array(
                ':password' => $hashedpassword,
                ':user_id' => $user_id
            ));
            
			$stmt = null;
        }
        catch (PDOException $e) {
            return $registererror[] = $e->getMessage();
        }
        return true;
    }
    
    
    
    public function login($username, $password)
    {
        
        $hashed = $this->get_user_hash($username);
        
        $userid = $this->get_user_id($username);
        
        // User has been successfully verified, lets sessionize his user id so we can refer to later
        $_SESSION['userId'] = $userid;
        $_SESSION['user']   = $username;
        
        
        if ($this->password_valid($password, $hashed) == 1) {
            
            $_SESSION['loggedin'] = true;
            
            
            return true;
        }
    }
    
    public function logout()
    {
        session_destroy();
        
    }
    
    public function is_logged_in()
    {
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            return true;
        }
    }
    
    
}


?>