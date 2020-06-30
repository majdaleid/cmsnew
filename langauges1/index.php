<?php
session_start();
include_once ('lang.php');

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="../css/style.css" rel="stylesheet" media="screen">
    <title>Multi language website</title>
</head>
<body>

<ul>
    <li><a href="/"><?php echo $home; ?></a> </li>
   <li><a href="blog.php"><?php echo $blog; ?></a> </li>
   <li><a href="news.php"><?php echo $news; ?></a> </li>
    <li><a href="contact.php"><?php echo $contact; ?></a> </li>

</ul>

<div id="welcome">

    <h1><?php echo $welcome; ?></h1>
</div>

<div>change language:
<a href="?lang=en">English</a> | <a href="?lang=de">Deutch</a>
</div>
</body>
</html>