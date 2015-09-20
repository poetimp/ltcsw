
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
      $results = mysql_query("select Password,count(*) as Count
                              from   $UsersTable
                              where  Userid   = '$Userid'
                              and    Status  != 'L'
                             ")
              or die ("Unable to validate Userid and Password!". mysql_error());
      $row     = mysql_fetch_assoc($results);

      if ($row['Count'] != 1 or !password_verify($oldPassword,$row['Password']))
      {
         $message="Sorry, You did not enter current password correctly.";
      }
      else
      {
         $newPassword = password_hash($newPassword1,PASSWORD_DEFAULT);
         if (mysql_query("update $UsersTable set Password = '$newPassword' where Userid='$Userid'"))
            $message="Your password has been successfully updated";
         else
            $message="Unable to update Password!". mysql_error();
      }
   }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
   <head>
      <title>Change Password</title>
      <h1 align=center>Change Password</h1>
   </head>
   <body style="background-color: rgb(217, 217, 255);">
      <form method="post" id="main" name="main">
         <center>
            <table border="0" width="550px">
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

            <table border="1" width="625px" style="text-align: center">
               <tr>
                  <td colspan="2"><b>Change Password:</b></td>
               </tr>
               <tr>
                  <td align="right">Current Password:&nbsp;&nbsp;</td>
                  <td align="left"><input type="password" name="oldPwd" size="40"></td>
               </tr>
               <tr>
                  <td align="right">New Password:&nbsp;&nbsp;</td>
                  <td align="left"><input type="password" name="newPwd1" size="40"></td>
               </tr>
               <tr>
                  <td align="right">New Password Again:&nbsp;&nbsp;</td>
                  <td align="left"><input type="password" name="newPwd2" size="40"></td>
               </tr>
               <?php
               if ($message != '')
               {
               ?>
               <tr>
                  <td colspan="2" align="center"><font color=red><b><?php print $message?></b></font></td>
               </tr>
               <?php
               }
               ?>
               <tr>
                  <td colspan="2" align="center"><input type="submit" name="ChangePwd" value="Change Password"></td>
               </tr>
               </table>
         </center>
      </form>
      <?php footer("Return to Login","login.php")?>
   </body>
</html>