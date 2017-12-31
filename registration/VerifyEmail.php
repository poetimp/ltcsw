<?php
//----------------------------------------------------------------------------
// This software is licensed under the MIT license. Use as you wish but give
// and take credit where due.
//
// Author: Paul Lemmons
//----------------------------------------------------------------------------
?>

<?php
include 'include/RegFunctions.php';

$message='';
if (isset($_POST['verify']))
{
      $results = $db->query("select verificationCode
                              from   $UsersTable
                              where  Userid   = '$Userid'
                             ")
              or die ("Unable to obtain verification code!". sqlError());
      $row     = $results->fetch(PDO::FETCH_ASSOC);

      if ($row['verificationCode'] == $_POST['code'])
      {
         $address = $_SESSION['newemail'];
         unset($_SESSION['newemail']);

         if ($db->query("update $UsersTable set email='$address' where Userid='$Userid'"))
         {
            $message = "Email address updated";
            $db->query("update $UsersTable set verificationCode=null where Userid='$Userid'");
         }
         else
         {
            $message="Unable to update email address: ".sqlError();
         }

      }
      else
      {
         $message="Sorry, that is not the code I am expecting";
      }

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
   <head>
      <title>Change Password</title>
      <meta http-equiv="Content-Language" content="en-us">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel=stylesheet href="include/registration.css" type="text/css" />
   </head>
   <body>
      <h1 align=center>Change Password</h1>
      <form method="post" id="main" name="main">
         <center>
            <table class='registrationTable' style='width:550px'>
               <tr>
                  <td>
                     <p style="text-align: Left">
                        Go check your email. Look for a message with the subject line of "LTCSW Verify Email Change"
                        Inside that note is a 6 digit number. Enter that number below and click the button below it.
                        If you entered the number correctly, your email address will be updated.
                     </p>
                  </td>
               </tr>
            </table>

            <table class='registrationTable' style='width: 625px; text-align: center'>
               <tr>
                  <td colspan="2"><b>Change Email:</b></td>
               <tr>
                  <td style='text-align: right'>Verification Code:&nbsp;&nbsp;</td>
                  <td style='text-align: left'><input type="text" name="code" size="40"></td>
               </tr>
               <?php
               if ($message != '')
               {
               ?>
               <tr>
                  <td colspan="2" style='text-align: center'><font color=red><b><?php print $message?></b></font></td>
               </tr>
               <?php
               }
               ?>
               <tr>
                  <td colspan="2" style='text-align: center'><input type="submit" name="verify" value="Verify code"></td>
               </tr>
               </table>
         </center>
      </form>
      <?php footer("Return to Login","login.php")?>
   </body>
</html>