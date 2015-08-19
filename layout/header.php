<?php 
    header("Content-Type: text/html; charset=utf-8");
    require("includes/form_check.php");
    ?>
    
<!DOCTYPE html>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title><?php if(isset($title)){ echo $title; }?></title>
    
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="style/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="style/bootstrap-theme.min.css" type="text/css">
    <link rel="stylesheet" href="style/blueimp-gallery.min.css" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="style/main.css" type="text/css">
    <script src="js/script.js" type="text/javascript"></script>
</head>

<body>