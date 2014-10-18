<?php 
session_start();
if ($_SESSION['logged-in'] != 1)
{
   if (isset($_REQUEST['Admin']) and $_REQUEST['Admin'] == 1)
   {
      $admin = "?Admin=1";
   }
   else
   {
      $admin = "";
   }
   $redirect=$_SERVER['PHP_SELF'];
   header("refresh: 0; URL=login.php?redirect=$redirect$admin");
   die();
}
?>