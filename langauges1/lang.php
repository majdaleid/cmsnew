<?php
/////////////////////////////////
////By:The Rim Media & Marketing
///link: http://www.therim.com.au
///coder: Mohamed Amin
/////////////////////////////////
//session_start();
if(isset($_GET['lang']) && $_GET['lang']=="en"){
    unset($_SESSION['lang']);
    $_SESSION['lang']=true;
    $_SESSION['lang']="en";
    header("Location:index.php");
}

if(isset($_GET['lang']) && $_GET['lang']=="de"){
    unset($_SESSION['lang']);
    $_SESSION['lang']=true;
    $_SESSION['lang']="de";
    header("Location:index.php");
}

if(!isset($_GET['lang']) && !isset($_SESSION['lang'])){
    include_once "lang/en.php";
}


if(isset($_SESSION['lang']) && $_SESSION['lang']=="de"){

    include_once "lang/de.php";
}
if(isset($_SESSION['lang']) && $_SESSION['lang']=="en"){
    include_once "lang/en.php";
}


?>