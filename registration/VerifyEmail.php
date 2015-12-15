
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
         $_SESSION['newemail'] = undef;

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
      <h1 align=center>Change Password</h1>
   </head>
   <body style="background-color: rgb(217, 217, 255);">
      <form method="post" id="main" name="main">
         <center>
            <table border="0" width="550px">
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

            <table border="1" width="625px" style="text-align: center">
               <tr>
                  <td colspan="2"><b>Change Email:</b></td>
               </tr>
               <tr>
                  <td align="right">Verification Code:&nbsp;&nbsp;</td>
                  <td align="left"><input type="text" name="code" size="40"></td>
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
                  <td colspan="2" align="center"><input type="submit" name="verify" value="Verify code"></td>
               </tr>
               </table>
         </center>
      </form>
      <?php footer("Return to Login","login.php")?>
   </body>
</html>