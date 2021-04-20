<?php 
  session_start();
  echo "Welcome <b> " . $_SESSION['email'] . "</b>";
 ?>
 <a href="logout.php">Logout</a>