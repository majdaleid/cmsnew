<?php
require_once 'conn.php';


$sql ="SELECT * FROM myguests2";
$result= $con->query($sql);

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


    <title>user list </title>
  </head>
  <body>
    <div class="container">
<div class="row">
 <div class="col-md-6 col-md-offset-3">
 <div id="div1"></div>
   <table class="table table">
     <thread>
     <tr>
       <th>First Name</th>
       <th>Last Name</th>
     
     </tr>
   </thread>
   <tbody id="result">
     <?php
      if($result->num_rows > 0){
        while($row =$result->fetch_assoc()){
          ?>
          <tr>
            <td><?php echo $row['firstname']; ?></td>
            <td><?php echo $row['lastname']; ?></td>   
          </tr>
          <?php
        }
      } else {
      ?>
      <tr>
        <td>no users found</td>
      </tr>
      <?php
      }
       ?>

   </tbody>
   </table>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://js.pusher.com/6.0/pusher.min.js"></script>
<script>

   // Enable pusher logging - don't include this in production
   Pusher.logToConsole = true;

   var pusher = new Pusher('28a4c8560a39e4074dd5', {
     cluster: 'eu'
   });

   var channel = pusher.subscribe('my-channel');
   channel.bind('my-event', function(data) {
     //alert(JSON.stringify(data));
 
  $.ajax({url: "users.php", success: function(result){
    $("#result").html(result);
  }});
});


    
 </script>
  </body>
</html>
