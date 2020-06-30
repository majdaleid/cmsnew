<?php
include_once('functions.php');
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title> 
    <script src="https://www.google.com/recaptcha/api.js"></script>
</head>
<body>
<?php register(); ?>
<form action="" method="post">

    <div>
        Register new member
    </div>

    <div>
        username: <input type="text" name="username">
    </div>
  <br>
    <div>
        password: <input type="password" name="password">
    </div>
    <br>
    <div>
        email: &nbsp;&nbsp;&nbsp;&nbsp;   <input type="email" name="email">
    </div>
    <br>

    <div class="g-recaptcha" data-sitekey="6LdzxuYUAAAAAPMIV6VxB2DV5SeC0NCDZJt_QMRE"></div>




    <br>


    <div>
     <input type="submit" name="sub_user" value="Register">
    </div>

</form>

</body>
</html>