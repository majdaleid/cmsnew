<?php
include_once ('includes/config.php');

function register(){
    global $db;
  if(isset($_POST['sub_user'])){

      $g_recpatcha=$_POST['g-recaptcha-response'];

      if(empty($g_recpatcha)){
          echo "Enter Recaptcha code";
      }else{

          $google_url="https://www.google.com/recaptcha/api/siteverify";
          include_once ('includes/getcurl.php');
          $secret="6LdzxuYUAAAAADaMC0VvDzZYRzaVtATAnugRAkaz";
          $ip=$_SERVER['REMOTE_ADDR'];
          $fullurl=$google_url."?secret=".$secret."&response=".$g_recpatcha."&remoteip=".$ip;
          $res=getCurlData($fullurl);
          $res=json_decode($res,true);

          if($res['success']){
			  ?>
          <script>window.location.href = "www.hotmail.com";</script>
<?php
          }else{
			  ?>
              <script>window.location.href = "www.hotmail2.com";</script>
			  <?php
          }



      }


  }

}


 
