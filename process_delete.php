<?php
session_start();

include('classes/user.php');

$user = new User();

include('classes/gallery.php');

$gallery = new GalleryMaker();

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["imageId"])) {
    $userid      = $_SESSION['userId'];
    $imageId       = $_POST['imageId'];
    
    $gallery->delete($imageId, $userid);
    
}
