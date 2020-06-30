<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" media="screen" href="css/style.css">

</head>
<body>
<?php
include_once('includes/config.php');

if(isset($_GET['sub_search'])){
    $search=$db->real_escape_string($_GET['search']);
    $q=$db->query("select * from topics where title like '%$search%'");


    ?>
    <div>Results:</div>
    <?php
    while($row=$q->fetch_assoc()){

        echo"<div><a href='#'>".$row['title']."</a></div><br>";
    }


}


?>

<form action="" method="get">
    <input type="text" placeholder="Search" name="search"> &nbsp; <input type="submit" name="sub_search" value="Search">
</form>

<h2>Welcome in our website</h2>


</body>
</html>