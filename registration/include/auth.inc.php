<?php
//----------------------------------------------------------------------------
// This software is licensed under the MIT license. Use as you wish but give
// and take credit where due.
//
// Author: Paul Lemmons
//----------------------------------------------------------------------------
?>
<?php
session_start();
//-----------------------------------------------------------------------------
// Make sure the person is logged in. If not redirect to the login page and
// if the page is being call with admin turned on ensure that that information
// is also being passed to the login page.
//-----------------------------------------------------------------------------
$Userid     = isset($_SESSION['Userid'])    ? $_SESSION['Userid']    : "";
$ChurchID   = isset($_SESSION['ChurchID'])  ? $_SESSION['ChurchID']  : "";
$UserName   = isset($_SESSION['Name'])      ? $_SESSION['Name']      : "";
$Admin      = isset($_SESSION['Admin'])     ? $_SESSION['Admin']     : "N";
$UserStatus = isset($_SESSION['Status'])    ? $_SESSION['Status']    : "";
$LoggedIn   = isset($_SESSION['logged-in']) ? $_SESSION['logged-in'] : 0;
$Email      = isset($_SESSION['email'])     ? $_SESSION['email']     : '';

if (!$LoggedIn and !preg_match('/login.php$/'         ,$_SERVER['PHP_SELF'])
               and !preg_match('/ForgotPassword.php$/',$_SERVER['PHP_SELF'])
               and !preg_match('/ResetPassword.php$/' ,$_SERVER['PHP_SELF'])
   )
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