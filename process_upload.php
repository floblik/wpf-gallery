<?php
session_start();

include('classes/user.php');

$user = new User();

include('classes/gallery.php');

$gallery = new GalleryMaker();

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_FILES["image"]["type"])) {
    
    $data        = $_FILES["image"];
    $userid      = $_SESSION['userId'];
    $description = $_POST['description'];
    $title       = $_POST['title'];
    
    $gallery->upload($data, $userid, $description, $title);
    
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_FILES["avatar"]["type"])) {
    
    $data   = $_FILES["avatar"];
    $userid = $_SESSION['userId'];
    
    $user->upload_avatar($data, $userid);
}
?>