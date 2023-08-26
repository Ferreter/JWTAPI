<?php

 require "../model/users.php";
  require "../model/database.php";
 $countyId = 1458;
$arr = viewSongs();

foreach($arr as $x => $x_value) {
  echo " Id=" .  $x_value[0] . "\n";
  echo " Name=" .  $x_value[2] . "\n";
  echo " Code=" .  $x_value[1] . "\n";
  echo "<br>";
}
?>
<main>
    <h1>Sign Up</h1>

    <section>
        <?php 
       echo "Api Key: " . $_SESSION["apikey"] . ".<br>";
       ?>
        <form action="" method="post">
            <input type="hidden" name="action" value="req_service1">  

            <input type="submit" value="req_service1" name='req_service1'>
            <p> Request List Of music=<label><a href="?action=req_service2" id="req_Service"> Music</a></label></p>
        </form>
    </section>
</main>

    
