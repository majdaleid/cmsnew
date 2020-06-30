<?php
include_once('includes/config.php');

$get_all=$db->query("select * from tmp_topics order by tmp_id desc");


if(isset($_POST['sub_approve'])){

    if(isset($_POST['chs_name'])){
        $id=$_POST['chs_name'];
        if($db->query("insert into perm_topics(t_title,t_body,t_views) select t_title,t_body,t_views from tmp_topics where tmp_id='$id'")){
$db->query("delete from tmp_topics where tmp_id='$id'");

            echo "<div>Topic Approved Successfully</div>";
        }
    }

}

?>




<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approve Topics</title>
</head>
<body>

<form action="" method="post">
<table border="1" cellspacing="0" cellpadding="0">

    <tr>
        <th>#</th>
        <th>Title</th>
    </tr>
    <?php
    while($row=$get_all->fetch_assoc()) {
        ?>

        <tr>
            <td><input type="checkbox" name="chs_name" value="<?php echo $row['tmp_id']; ?>" </td>
            <td><?php echo $row['t_title']; ?></td>
        </tr>
        <?php
    }
    ?>

    <tr>
        <td colspan="2"><input type="submit" name="sub_approve" value="Approve"> </td>
    </tr>
</table>
</form>

</body>
</html>