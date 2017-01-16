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

$badPass = 0;

if (isset($_POST['submit']))
{
   // First does the userid esist>

   $results = $db->query("select Password,count(*) as Count
                           from   $UsersTable
                           where  Userid   = '" . $_POST['userid'] . "'
                           and    Status  != 'L'
                          ")
              or die ("Unable to validate Userid and Password!". sqlError());
   $row     = $results->fetch(PDO::FETCH_ASSOC);

// Yep ... continue
   if ($row['Count'] == 1 and password_verify($_POST['pwd'],$row['Password']))
   {
      $results = $db->query("select *
                              from $UsersTable
                              where Userid = '" .$_POST['userid'] ."'
                             ")
                 or die ("Unable to read Users information!". sqlError());
      $row     = $results->fetch(PDO::FETCH_ASSOC);

      $_SESSION['Admin']     = $row['Admin'];
      if (!$systemDown or $_SESSION['Admin'] == 'Y')
      {
         $_SESSION['Userid']    = $row['Userid'];
         $_SESSION['ChurchID']  = $row['ChurchID'];
         $_SESSION['Name']      = $row['Name'];
         $_SESSION['Status']    = $row['Status'];
         $_SESSION['email']     = $row['email'];
         $_SESSION['logged-in'] = 1;

         $UserID                = $row['Userid'];

         WriteToLog("Successful Login");

         $results = $db->query("update $UsersTable
                                set lastLogin  = now(),
                                    loginCount = loginCount+1
                                where Userid   = '$UserID'
                                ")
                 or die ("Unable to update Users information!". sqlError());
         if (isset($_POST['redirect']))
         {
            header ("Refresh: 0; URL=" . $_POST['redirect'] . "");
         }
         else
         {
            header ("Refresh: 0; URL=Admin.php");
         }
      }
      else
      {
       $_SESSION['logged-in'] = 0;
         header ("Refresh: 0; URL=Admin.php");
      }
   }
   else
   {
      $_SESSION['Userid']    = $_POST['userid'];
      WriteToLog("Login Failed");
      $badPass = 1;
      $results = $db->query("select count(*) as Count
                              from   $UsersTable
                              where  Userid   = '" . $_POST['userid'] . "'
                             ")
                 or die ("Unable to validate Userid and Password!". sqlError());
      $row     = $results->fetch(PDO::FETCH_ASSOC);

      if ($row['Count'] == 1)
      {
         $results = $db->query("update $UsersTable
                                 set failedLoginCount = failedLoginCount+1
                                 where Userid   = '".$_POST['userid']."'
                                ")
                 or die ("Unable to update Users information!". sqlError());
      }
   }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
   <head>
      <title>LTCSW Registration Sign In</title>
   </head>

   <body style="background-color: rgb(217, 217, 255);">
      <h1 align="center">LTCSW Registration</h1>
      <?php
      if ($systemDown)
      {?>
      <b><font size="+1" color="Red"><p align="center">Registration is Down for Maintenance... be back soon...</p></font></B>
      <?php
      }
      else
      {?>
      <b><font size="+1" color="Red"><p align="center">Registration is currently down in preparation for for 2017 convention</p></font></b>
<!--  <b><font size="+1" color="Red"><p align="center">There will be a delay in opening registration</p></font></b> -->
<!--  <b><font size="+1" color="Red"><p align="center">check <a href="https://www.facebook.com/groups/ltcsw/">Facebook page</a> for updates</p></font></b> -->
<!--  <b><font size="+1" color="Red"><p align="center">2016 Registration Reports and Awards are Ready</p></font></b> -->
<!--  <b><font size="+1" color="Red"><p align="center">See you in April of 2017!</p></font></b> -->

      <form action='login.php' method="post">

         <?php
         if ((isset($_POST['Admin'])    and $_POST['Admin']    == 1)
          or (isset($_REQUEST['Admin']) and $_REQUEST['Admin'] == 1)
            )
         {
            $admin = 1;
         }
         else
         {
            $admin = 0;
         }

         if (isset($_REQUEST['redirect']))  $redirect = $_REQUEST['redirect'];
         elseif (isset($_POST['redirect'])) $redirect = $_POST['redirect'];
         else                               $redirect =  'Admin.php';

         if ($admin)                        $redirect .= "?Admin=1";
         ?>
         <input type="hidden" name="Admin"    value="<?php print $admin ?>"/>
         <input type="hidden" name="redirect" value="<?php print $redirect ?>"/>
         <div align="center">
            <table border="1" width="39%" id="table">
               <tr>
                  <td width="11%">Userid: </td>
                     <td width="27%"> <input type="text" name="userid" size="28"/></td>
               </tr>
               <tr>
                  <td width="11%">Password: </td>
                  <td width="27%"> <input type="password" name="pwd" size="28"/></td>
               </tr>
               <tr>
                  <td colspan="2" align="center">
                  <input type="submit" name="submit" value="Login"/></td>
               </tr>
            </table>
         <?php
            if ($badPass)
            {
               ?>
               <p align="center"><font color="#FF0000">Invalid username and/or password</font><br/></p>
               <?php
            }
            else
            {
               ?>
               <p align="center">Please log in to enter registration</p>
               <p align="center">Forgot Password<br/>
                  <a href="ForgotPassword.php">Click Here</a>
               </p>
               <?php
            }
         ?>


            <div align="center" style="position: relative; font: 13px verdana, arial, helvetica, sans-serif; width: 500px; background-color: #ffffcc; padding: 15px 15px 15px 15px; border: 1px solid #c60130; text-align: left; color: black;">
               <h2 align="center">Can't Login?</h2>
               <p>
                  <b>IMPORTANT:</b> In order to improve security, a number of changes have been implemented in regards
                  to passwords and password recovery. You may now manage your own password, changing it to something
                  that is memorable to you. You can also reset your password should you ever forget it. To do that, though
                  you will have to add your email to your profile. You are strongly encouraged to do that.
               </p>
               <p>
                  Password complexity has also been improved. If you have a simple password, please take a moment and change it.
                  Consider the information here that you are protecting with your password and choose a password wisely.
               </p>
            </div>
         </div>
      </form>
   </body>
</html>
<?php
      }?>
