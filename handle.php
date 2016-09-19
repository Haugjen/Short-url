<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <title>Url Shortener</title>
</head>
<?php
include 'model.php';

if (isset($_POST['duration'])){
    
    session_start();
    $shortcode = substr($_SESSION['hResultShort'], 14);
    $duration = $_POST['duration'];
    $class = new ShortUrl();
    $class->setDuration($duration, $shortcode);
    header("Location: result.php");
    exit;
}


if (isset($_POST['shorten'])) {
    
    $url = $_POST['shorten'];
    $class = new ShortUrl();
    $class->urlToShortCode($url);

    session_start();
    $_SESSION['hResultShort'] = "homestead.app/" . $class->urlToShortCode($url);
    $_SESSION['hResultLong'] = $_POST['shorten'];


    header("Location: result.php");
    exit;
}



    
    
//if (!empty($_GET['btn'])) {
//    ob_start();
//    ob_end_flush();
//    die();
//}

     


