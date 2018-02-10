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

$message         = '';
$redirectMessage = '';
$redirect        = false;
$email           = GET('email');
$code            = GET('code');

if (empty($email))
    $redirectMessage .= '<b><font color="red">error: Incorrect call to program: missing email</font></b><br />';

if (empty($code))
    $redirectMessage .= '<b><font color="red">error: Incorrect call to program: missing code</font></b><br />';

$User = user_by_email($email);
if (!$User)
    $redirectMessage .= '<b><font color="red">error: Incorrect call to program: bad email</font></b><br />';

$Reset = reset_by_code($code);

if(!$Reset)
    $redirectMessage .= '<b><font color="red">I am sorry, it appears that the link you used has expired. Please try again.</font></b><br />';

if ($Reset['Userid'] != $User['Userid'])
    $redirectMessage .= '<b><font color="red">error: Incorrect call to program: mismatched parameters</font></b><br />';

if (POST('password') and $redirectMessage == '')
{
   if (POST('password') != POST('passwordr'))
   {
      $message = 'Password Mismatch';
   }
   else if (!verifyPasswordFormat(POST('password')))
   {
      $message = "Sorry, chosen password is too easily hacked. Read note above";
   }
   else
   {
      user_update_password($User['Userid'], POST('password'));
      resets_delete_by_id($Reset['Code']);
      $redirectMessage = '<b><font color="blue">Congratulations! Your password has been updated.</font></b>';
   }
}
if ($redirectMessage != '')
{
   header("refresh: 5; URL=login.php");
   print "<html>
             <body style=\"background-color: rgb(217, 217, 255);\">
                <div style='text-align: center'>
                   $redirectMessage<br/>
                   Redirecting in 5 seconds.
                </div>
             </body>
          </html>";
    die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
    <head>
        <title>Reset Password</title>
        <meta http-equiv="Content-Language" content="en-us" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="include/registration.css" type="text/css" />
    </head>
    <body>
        <h1 align="center">Reset Password</h1>
        <form method="post" id="main" name="main">
            <div style="text-align: center">
                <table class='registrationTable' border="0" style='width: 550px'>
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

                <table class='registrationTable' style='width: 625px; text-align: center'>
                    <tr>
                        <td colspan="2"><b>Enter your new password:</b></td>
                    </tr>
                    <tr>
                        <td style='text-align: right'>New Password:&nbsp;&nbsp;</td>
                        <td style='text-align: left'><input type="password" name="password" size="40" /></td>
                    </tr>
                    <tr>
                        <td style='text-align: right'>Repeat Password:&nbsp;&nbsp;</td>
                        <td style='text-align: left'><input type="password" name="passwordr" size="40" /></td>
                    </tr>
                    <?php
                    if ($message != '') {
                        ?>
                        <tr>
                            <td colspan="2" style='text-align: center'><font color=red><b><?php print $message ?></b></font></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td colspan="2" style='text-align: center'><input type="submit" name="reset" value="Reset Password"></td>
                    </tr>
                </table>
            </div>
        </form>
        <?php footer("Return to Login", "login.php") ?>
    </body>
</html>