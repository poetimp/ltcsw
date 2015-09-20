<?php
   $db = mysql_connect($db_host,$db_user,$db_password) or die ("Unable to connect to database!");
   mysql_select_db($db_database)                       or die ("Unable to select database!");

   $_SESSION['db'] = $db;
?>
