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
if (isset($_POST['ChangePwd']))
{
   $oldPassword  = isset($_POST['oldPwd'])  ? $_POST['oldPwd']  : '';
   $newPassword1 = isset($_POST['newPwd1']) ? $_POST['newPwd1'] : '';
   $newPassword2 = isset($_POST['newPwd2']) ? $_POST['newPwd2'] : '';

   if ($newPassword1 != $newPassword2)
   {
      $message="New Passwords do not match.";
   }
   elseif (!verifyPasswordFormat($newPassword1))
   {
      $message="Sorry, chosen password is too easily hacked. Read note above";
   }
   else
   {
      $results = $db->query("select Password,count(*) as Count
                              from   $UsersTable
                              where  Userid   = '$Userid'
                              and    Status  != 'L'
                             ")
              or die ("Unable to validate Userid and Password!". sqlError());
      $row     = $results->fetch(PDO::FETCH_ASSOC);

      if ($row['Count'] != 1 or !password_verify($oldPassword,$row['Password']))
      {
         $message="Sorry, You did not enter current password correctly.";
      }
      else
      {
         $newPassword = password_hash($newPassword1,PASSWORD_DEFAULT);
         if ($db->query("update $UsersTable set Password = '$newPassword' where Userid='$Userid'"))
            $message="Your password has been successfully updated";
         else
            $message="Unable to update Password!". sqlError();
      }
   }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
   <head>
      <meta http-equiv="Content-Language" content="en-us">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel=stylesheet href="include/registration.css" type="text/css" />

      <title>Change Password</title>
      <h1 align=center>Change Password</h1>
   </head>
   <body>
      <form method="post" id="main" name="main">
         <center>
            <table class='registrationTable' style="width: 550px">
               <tr>
                  <td>
                     <p style="text-align: Left">
                        When choosing your password please do not use the same password that you do for your banking
                        and other personal and private accounts. All attempts have been made to secure this system
                        against hacking but nothing is foolproof because fools are so ingenious.
                     </p>
                     <p style="text-align: Left">
                        Do, however, use a good password. Your password will have to be at least 7 characters long.
                        It will also have to have at least one number, at least one special character and both
                        upper and lower case characters. I know: <i>"What a pain! I Hate that!"</i>.
                        I do to. It really is for everybody's good though. The responsibility for protecting the data
                        that is being entered into this system belongs to all of us.
                     </p>
                  </td>
               </tr>
            </table>

            <table class='registrationTable' border="1"  style="text-align: center; width: 625px">
               <tr>
                  <td style='text-align: center' colspan="2"><b>Change Password:</b></td>
               </tr>
               <tr>
                  <th style='text-align: right'>Current Password:&nbsp;&nbsp;</th>
                  <td style='text-align: left'><input type="password" name="oldPwd" size="40"></td>
               </tr>
               <tr>
                  <th style='text-align: right'>New Password:&nbsp;&nbsp;</th>
                  <td style='text-align: left'><input type="password" name="newPwd1" size="40"></td>
               </tr>
               <tr>
                  <th style='text-align: right'>New Password Again:&nbsp;&nbsp;</th>
                  <td style='text-align: left'><input type="password" name="newPwd2" size="40"></td>
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
                  <td colspan="2" style='text-align: center'><input type="submit" name="ChangePwd" value="Change Password"></td>
               </tr>
               </table>
         </center>
      </form>
      <?php footer("Return to Login","login.php")?>
   </body>
</html>