<?php
//include_once('../includes/config.php');
//if it's image
if(isset($_POST['sub_img']) ){
    $img_name=$_FILES['upimg']['name'];
    $img_type=$_FILES['upimg']['type'];
    $img_size=$_FILES['upimg']['size'];
    $img_tmp=$_FILES['upimg']['tmp_name'];
    $img_error=$_FILES['upimg']['error'];
    //$esc_name=$db->real_escape_string(strip_tags($img_name));
  $esc_name=$img_name;
    if(empty($img_tmp)){
        echo "<div id='error_msg'>Please Choose A Photo</div>";
        ?>
        <script>
            setTimeout(function () {
                window.location.href= 'upload.php'; // the redirect goes here

            },3000);
        </script>
        <?php
        exit;
    }

    $target_path="images/".$esc_name;
    list($width,$height)=@getimagesize($img_tmp);

    if($width==false || $height==false  ){
        echo "<div id='error_msg'>You Should Choose A valid Image Type</div>";
        ?>
        <script>
            setTimeout(function () {
                window.location.href= 'upload.php';

            },3000);
        </script>
        <?php
        exit;
    }


    $allowed_types=array("image/jpg","image/jpeg","image/png","image/gif");

    if(!in_array($img_type,$allowed_types)){
        echo "<div id='error_msg'>Error:The only Allowed Image Extensions Are jpeg,jpg,png,gif</div>";
        $ok=0;
        ?>
        <script>
            setTimeout(function () {
                window.location.href= 'upload.php'; // the redirect goes here

            },3000);
        </script>
        <?php
    }

    if($img_size >900000){
        echo "<div id='error_msg'>Error: Maximum Image Size is 900 KB Only</div>";
        $ok=0;
        ?>
        <script>
            setTimeout(function () {
                window.location.href= 'upload.php'; // the redirect goes here

            },3000);
        </script>
        <?php

    }


    if(file_exists($target_path)){
        echo "<div id='error_msg'>The Image Name Already Exist,Please Change To Another Name</div>";
        $ok=0;

        ?>
        <script>
            setTimeout(function () {
                window.location.href= 'upload.php'; // the redirect goes here

            },3000);
        </script>
        <?php
    }

    if(isset($ok)){
        echo "<div id='error_msg'>Error:Image Uploading Failed</div>";


    }


    else {


        if(move_uploaded_file($img_tmp,$target_path)){
            list($width,$height)=@getimagesize($target_path);

            if($width==false || $height==false  ){
                echo "<div id='error_msg'>Error:Image Type Is Invalid</div>";
                ?>
                <script>
                    setTimeout(function () {
                        window.location.href= 'upload.php';

                    },3000);
                </script>
                <?php
                exit;
            }



die("yessss");
           // $db->query("insert into gallery(img_name,approved) values('$esc_name','yes')");




            echo "<div>Image Uploaded Successfully</div>";
            ?>
            <script>
                setTimeout(function () {
                    window.location.href= 'upload.php'; // the redirect goes here

                },3000);
            </script>
            <?php
            exit;
        }



    }	//end if ok


}   //end if it's image




?>






<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="../css/style.css" rel="stylesheet" media="screen">
    <title>Image Uploader</title>
</head>
<body>


<form action="" method="post" enctype="multipart/form-data">

    <div id="upload">
        <input type="file" name="upimg">
    </div>

    <div>
        <input type="submit" name="sub_img" value="Upload">
    </div>

</form>


</body>
</html>