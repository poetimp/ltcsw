<?php
include 'include/RegFunctions.php';

$message='';
if (isset($_POST['ChangeEmail']))
{
   $address = isset($_POST['email']) ? $_POST['email'] : '';

   if (!filter_var($address, FILTER_VALIDATE_EMAIL))
   {
      $message="I am sorry, that does not look like a valid email address.";
   }
   else
   {
      $code = (rand(100000,999999));

      $todayis = date("l, F j, Y, g:i a [T]") ;

      $subject  = "LTCSW Verify Email Change";

      $email   = "<html>";
      $email  .= "   <head>\n";
      $email  .= "      <title>\n";
      $email  .= "         Verify Email Change\n";
      $email  .= "      </title>\n";
      $email  .= "      <h1 align=center>\n";
      $email  .= "         Verify Email Change\n";
      $email  .= "      </h1>\n";
      $email  .= "   </head>\n";
      $email  .= "   <body>\n";
      $email  .= "      <p>\n";
      $email  .= "         You have requested to change your email address on the LTC Registration site.<br>\n";
      $email  .= "         On your browser the page should be asking you to enter a 6 digit code\n<br>";
      $email  .= "         <br>\n";
      $email  .= "         The code is: <b>$code</b><br>\n";
      $email  .= "         <br>\n";
      $email  .= "         <br>\n";
      $email  .= "      </p>\n";
      $email  .= "   </body>\n";
      $email  .= "</html>\n";

      $from    = 'MIME-Version: 1.0' . "\r\n";
      $from   .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      $from   .= "From: registration@ltcsw.org\r\n";

      if (mysql_query("update $UsersTable set verificationCode=$code where Userid='$Userid'"))
      {
         if (mail($address, $subject, $email, $from))
         {
            $_SESSION['newemail'] = $address;
            header("refresh: 0; URL=VerifyEmail.php");
         }
         else
         {
            $message = "Unable to send email";
         }
      }
      else
      {
         $message="Unable to generate verification code: ".mysql_error();
      }
   }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
   <head>
      <title>Change Email Address</title>
      <h1 align=center>Change Email Address</h1>
   </head>
   <body style="background-color: rgb(217, 217, 255);">
      <form method="post" id="main" name="main">
         <center>
            <table border="0" width="550px">
               <tr>
                  <td>
                     <p style="text-align: Left">
                        To change your email address there are a number of hoops you need to jump through.
                        This is because the email address you enter here can be used to reset your password
                        should you ever forget it. And since we require strong passwords this is easy to do.
                     </p>
                     <p style="text-align: Left">
                        Enter your new email address below and submit it. An email will be sent to it. It will
                        contain a 6 digit number. You will also be sent to a page where you can enter that number.
                        If you enter it correctly your email address will be updated.
                     </p>
                  </td>
               </tr>
            </table>

            <table border="1" width="625px" style="text-align: center">
               <tr>
                  <td colspan="2"><b>Change Email:</b></td>
               </tr>
               <tr>
                  <td align="right">New Email Address:&nbsp;&nbsp;</td>
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
                  <td colspan="2" align="center"><input type="submit" name="ChangeEmail" value="Change Email"></td>
               </tr>
               </table>
         </center>
      </form>
      <?php footer("Return to Login","login.php")?>
   </body>
</html>