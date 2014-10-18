<?php
if ($_SERVER['SERVER_NAME'] == 'localhost')
{
   $host     = "localhost";
   $user     = "***REMOVED***";
   $database = "***REMOVED***";
   $password = "***REMOVED***";
}
else
{
   $host     = "***REMOVED***";
   $user     = "***REMOVED***";
   $database = "***REMOVED***";
   $password = "***REMOVED***";
}

   $db = mysql_connect($host,$user,$password) or die ("Unable to connect to database!");
   mysql_select_db($database)                 or die ("Unable to select datbase!");

   $_SESSION['db'] = $db;
?>
