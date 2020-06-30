<?php
require_once 'conn.php';

require __DIR__ . '/vendor/autoload.php';

if($_SERVER['REQUEST_METHOD']=='POST')
{
  $options = array(
    'cluster' => 'eu',
    'useTLS' => true
  );
  $pusher = new Pusher\Pusher(
    '28a4c8560a39e4074dd5',
    '0c49135460c594206db8',
    '1005980',
    $options
  );




  $firstname=$_POST['firstname'];
  $lastname=$_POST['lastname'];
  $sql = "INSERT INTO myguests2 (firstname,lastname) VALUES ('$firstname', '$lastname')";

  if ($con->query($sql) === TRUE) {
   $data['message'] =   $firstname.' '.  $lastname;
    $pusher->trigger('my-channel', 'my-event', $data);
  // echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $con->error;
  }

  $con->close();


}
 


 ?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


    <title>user Entry </title>
  </head>
  <body>
<div class="container">
   <div class="col-md-6 col-md-offset-3">
     <form action="" method="POST">
       <div class="form-group">
         <label for="firstName">First name</label>
         <input type="text" class="form-control" id="firstname" name="firstname" placeholder="enter first name">
</div>
<div class="form-group">
  <label for="lasName">last name</label>
  <input type="text" class="form-control" id="lastname" name="lastname" placeholder="enter last name">
</div>



         <button type="submit" class="btn btn-default">SUBMIT</button>
          </form>
       </div>

   </div>

  </body>
</html>
