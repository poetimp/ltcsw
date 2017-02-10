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
include 'include/MoreFunctions.php';
require_once 'include/table/users.php';
require_once 'include/table/resets.php';

$message = '';
if (isset($_POST['reset']))
{
    $address = POST('email');

    if (!filter_var($address, FILTER_VALIDATE_EMAIL))
    {
       $message = "I am sorry, that does not look like a valid email address.";
    }
    else
    {
       $User = user_by_email($address);

       if (!$User)
       {
          $message = "I am sorry, I cannot find that email address in the database.";
       }
       else
       {
          $Userid = $User['Userid'];
          $code = reset_new($Userid);

          $url = base_url() . 'ResetPassword.php?' . http_build_query(array('email' => $User['email'],
                                                                            'code' => $code
                                                                           )
                                                                     );

          $subject = "LTC Password Reset";

          $email = "<html>";
          $email .= "   <head>\n";
          $email .= "      <title>\n";
          $email .= "         LTC Password Reset\n";
          $email .= "      </title>\n";
          $email .= "      <h1 align=center>\n";
          $email .= "         LTC Password Reset\n";
          $email .= "      </h1>\n";
          $email .= "   </head>\n";
          $email .= "   <body>\n";
          $email .= "      <p>\n";
          $email .= "         So, it appears that you have forgotten your password. No problem. This will be easy.<br>\n";
          $email .= "         Click the link below to reset your password.<br>\n";
          $email .= "         <br>\n";
          $email .= "         <a href=$url>Reset Password</a><br>\n";
          $email .= "      </p>\n";
          $email .= "   </body>\n";
          $email .= "</html>\n";

          $from = 'MIME-Version: 1.0' . "\r\n";
          $from .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
          $from .= "From: registration@ltcsw.org\r\n";

          if (@mail($address, $subject, $email, $from))
          {
             header("refresh: 5; URL=login.php");
             print "<html>
                        <body style=\"background-color: rgb(217, 217, 255);\">
                           <center>
                              Please check your email for instructions.<br>
                              (Page will refresh in 5 seconds)
                           </center>
                        </body>
                     </html>";
             die();
          }
          else
          {
             $message = "Unable to send email. You need to contact support.";
          }
       }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
    <head>
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <title>Forgot Password</title>
    </head>
    <body style="background-color: rgb(217, 217, 255);">
        <h1 align=center>Forgot Password</h1>
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
                    if ($message != '') {
                        ?>
                        <tr>
                            <td colspan="2" align="center"><font color=red><b><?php print $message ?></b></font></td>
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
        <?php footer("Return to Login", "login.php") ?>
    </body>
</html>