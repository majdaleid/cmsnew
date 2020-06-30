<?php
require_once 'conn.php';

$sql ="SELECT * FROM myguests2";
$result= $con->query($sql);

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