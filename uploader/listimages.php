<?php
include_once('../includes/config.php');


?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gallery</title>
</head>
<body>
<?php

$path='images/*.png';
$images=glob($path);
foreach($images as $img){
    echo "<img src='$img' width='180' height='180'>&nbsp;&nbsp;";
}
?>
</body>
</html>