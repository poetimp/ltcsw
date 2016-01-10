<?php
include 'include/RegFunctions.php';

$message='';
if (isset($_POST['reset']))
{
   $address = isset($_POST['email']) ? $_POST['email'] : '';

   if (!filter_var($address, FILTER_VALIDATE_EMAIL))
   {
      $message="I am sorry, that does not look like a valid email address.";
   }
   else
   {
      $result     = $db->query("select Userid
                                from   $UsersTable
                                where  email = '$address'")
                    or die ("Unable to get user information: " . sqlError());
      $row        = $result->fetch(PDO::FETCH_ASSOC);
      $Userid = isset($row['Userid']) ? $row['Userid'] : '';

      if ($Userid == '')
      {
         $message="I am sorry, I cannot find that email address in the database.";
      }
      else
      {
         $password = generatePassword(8);
         $newPass  = password_hash($password,PASSWORD_DEFAULT);

         $subject  = "LTC Password Reset";

         $email   = "<html>";
         $email  .= "   <head>\n";
         $email  .= "      <title>\n";
         $email  .= "         LTC Password Reset\n";
         $email  .= "      </title>\n";
         $email  .= "      <h1 align=center>\n";
         $email  .= "         LTC Password Reset\n";
         $email  .= "      </h1>\n";
         $email  .= "   </head>\n";
         $email  .= "   <body>\n";
         $email  .= "      <p>\n";
         $email  .= "         So, it appears that you have forgotten your password. No problem. I have reset it for you.<br>\n";
         $email  .= "         I am not so smart, though, so the password I chose for it is probably pretty ugly. You will want to\n<br>";
         $email  .= "         to change it as soon as you get logged in.<br>\n";
         $email  .= "         <br>\n";
         $email  .= "         Your new password is: <b>$password</b><br>\n";
         $email  .= "         <br>\n";
         $email  .= "         Like I said, pretty gnarly. I strongly suggest you copy/paste it into the password field to avoid the frustration of<br>\n";
         $email  .= "         trying to get all of that typed in correctly.<br>\n";
         $email  .= "      </p>\n";
         $email  .= "   </body>\n";
         $email  .= "</html>\n";

         $from      = 'MIME-Version: 1.0' . "\r\n";
         $from     .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
         $from     .= "From: registration@ltcsw.org\r\n";

         if ($db->query("update $UsersTable set Password='$newPass' where Userid='$Userid'"))
         {
            if (mail($address, $subject, $email, $from))
            {
               header("refresh: 5; URL=login.php");
               print "<html>
                        <body style=\"background-color: rgb(217, 217, 255);\">
                           <center>
                              Please check your email for your new password<br>
                              (Page will refresh in 5 seconds)
                           </center>
                        </body>
                     </html>";
               die();
            }
            else
            {
               $message = "Unable to send email. That stinks because I know your new password and you do not. You need to contact support";
            }
         }
         else
         {
            $message="Unable to set your new password: ".sqlError();
         }
      }
   }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
   <head>
      <title>Forgot Password</title>
      <h1 align=center>Forgot Password</h1>
   </head>
   <body style="background-color: rgb(217, 217, 255);">
      <form method="post" id="main" name="main">
         <center>
            <table border="0" width="550px">
               <tr>
                  <td>
                     <p style="text-align: Left">
                        This will only work if you have already updated your account information
                        to include your email address. If it does not recognize your email address
                        more than likely you have not done that yet and will need assistance with getting
                        your password changed.

                        An Email will be sent to the email address you supplied below.
                        Go check your email and look for a subject line of "LTC Password Reset",
                        it will have further instructions.
                    </p>
                  </td>
               </tr>
            </table>

            <table border="1" width="625px" style="text-align: center">
               <tr>
                  <td colspan="2"><b>Enter your Email address:</b></td>
               </tr>
               <tr>
                  <td align="right">Email Address:&nbsp;&nbsp;</td>
                  <td align="left"><input type="text" name="email" size="40"></td>
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
                  <td colspan="2" align="center"><input type="submit" name="reset" value="Reset Password"></td>
               </tr>
               </table>
         </center>
      </form>
      <?php footer("Return to Login","login.php")?>
   </body>
</html>