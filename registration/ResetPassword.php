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

$message  = '';
$redirect = false;
$email    = GET('email');
$code     = GET('code');

if (empty($email))
    die('error: missing email');
if (empty($code))
    die('error: missing code');

$User = user_by_email($email);
if (!$User)
    die('error: invalid user for email');

$Reset = reset_by_code($code);

if(!$Reset)
    die('That code may have already been used.');

if ($Reset['Userid'] != $User['Userid'])
    die('error: user <-> code mismatch');

if (POST('password'))
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
      resets_delete_by_id($Reset['id']);
      $redirect = true;
   }
}
if ($redirect)
{
   header("refresh: 5; URL=login.php");
   print "<html>
             <body style=\"background-color: rgb(217, 217, 255);\">
                <center>
                   Your password has been updated.<br/>
                   Redirecting in 5 seconds.
                </center>
             </body>
          </html>";
    die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
    <head>
        <title>Reset Password</title>

    </head>
    <body style="background-color: rgb(217, 217, 255);">
        <h1 align=center>Reset Password</h1>
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
                        <td colspan="2"><b>Enter your new password:</b></td>
                    </tr>
                    <tr>
                        <td align="right">New Password:&nbsp;&nbsp;</td>
                        <td align="left"><input type="password" name="password" size="40" /></td>
                    </tr>
                    <tr>
                        <td align="right">Repeat Password:&nbsp;&nbsp;</td>
                        <td align="left"><input type="password" name="passwordr" size="40" /></td>
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