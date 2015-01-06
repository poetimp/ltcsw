<?php
include 'include/RegFunctions.php';
$systemDown = 0;

function LoginHTML($p)
{
   global $systemDown;
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
      <head>
         <title>LTCSW Registration Signon</title>
      </head>

      <body style="background-color: rgb(217, 217, 255);">
      <h1 align="center">LTCSW Registration</h1>
      <?php if ($systemDown)
            {?>
               <b><font size="+1" color="Red"><p align="center">Registration currently offline for maintenance</p></font></b>
      <?php }
            else
            {
               if ($systemDown)
               {?>
                  <b><font size="+1" color="Red"><p align="center">Registration is Down for Maintenance... be back soon...</p></font></B>
               <?php
               }
               else
               {?>
                  <b><font size="+1" color="Red"><p align="center">Registration is Open for 2015</p></font></b>
<!--              <b><font size="+1" color="Red"><p align="center">Registration is Open for Reports Only</p></font></b> -->
<!--              <b><font size="+1" color="Red"><p align="center">2013 Registration Reports and Awards are Ready</p></font></b> -->
<!--              <b><font size="+1" color="Red"><p align="center">See you in April of 2013!</p></font></b> -->
               <?php
               }
            }?>
         <form action='login.php' method="post">

            <input type="hidden" name="Admin" value="<?php   if (isset($_POST['Admin']) and $_POST['Admin'] == 1)
                                                         {
                                                            print "1";
                                                         }
                                                         else if (isset($_REQUEST['Admin']) and $_REQUEST['Admin'] == 1)
                                                         {
                                                            print "1";
                                                         }
                                                         else
                                                          {
                                                            print '0';
                                                         }
                                                    ?>"/>
            <input type="hidden" name="redirect" value="<?php if (isset($_POST['redirect']))
                                                         {
                                                            print $_POST['redirect'];
                                                            if (isset($_POST['Admin']) and $_POST['Admin'] == 1)
                                                            {
                                                               print "?Admin=1";
                                                            }
                                                         }
                                                         else if (isset($_REQUEST['redirect']))
                                                         {
                                                            print $_REQUEST['redirect'];
                                                            if (isset($_REQUEST['Admin']) and $_REQUEST['Admin'] == 1)
                                                            {
                                                               print "?Admin=1";
                                                            }
                                                         }
                                                         else
                                                         {
                                                            print 'Admin.php';
                                                         }
                                                    ?>"/>
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
               if ($p == 'BadPass')
               {
                  ?>
                  <p align="center"><font color="#FF0000">Invalid username and/or password</font><br/></p>
                  <?php
               }
               else
               {
                  ?>
                  <p align="center">Please log in to enter registration</p>
                  <p align="center">Forgot Password or need to Register?<br/>
                     <a href="ReqUserid.php">Click Here</a>
                  </p>
                  <?php
               }
            ?>


             <div align="center" style="position: relative; font: 13px verdana, arial, helvetica, sans-serif; width: 500px; background-color: #ffffcc; padding: 15px 15px 15px 15px; border: 1px solid #c60130; text-align: left; color: black;">
                <h2 align="center">Can't Login?</h2>
                <p>
                  There are a number of reasons that might cause you difficulty logging in. Most
                  often it is either forgotten information or your account has not been unlocked
                  from last year. In all cases, if you are having problems logging in, please
                  click the link above and fill out as much information as you know and someone
                  will correct the problem and get back to you.
                </p>
 <!--            <h2>
                <p align="center">
                   NOTICE to Church Coordinators<br>
                   Confirmation of Participant registration
                </p>
             </h2>

               <p>
               In years past you received a confirmation of the
               events your participants were registered in. In lieu of
               mailing confirmation reports, the web program has been set up
               for church coordinators to have the availability to view/print
               what events they have registered their participants in.  By doing
               this you can make any necessary changes i.e. by adding or changing
               a registered participant in an event.  You may add or update
               participants until registration closes on March 16, 2007</p>

               <p>
               It is important that you view or print the registration by clicking on either "WHO IS
               IN WHAT BY PARTICIPANT" OR "WHO IS IN WHAT BY EVENT" in the Reports Section.  Remember, a
               participant must be registered in an event to partcipate at the
               convention.  Their being registered saves the tally room a lot
               of time and it assures a judges form, or team form being printed
               before the convention.</p>

               <p>
               If you have any questions, please contact Vivia Simmons at
               <a href="mailto:viviaann@juno.com">viviaann@juno.com</a>
               or  602-371-1157.  She will be glad to assist you.</p>
               <p align="center">
               <a href="http://www.ltcsw.org/">Home</a> |
               <a href="http://www.ltcsw.org/sitemap.htm">Site Map</a>-->
            </div>
            </div>
            </form>
         </body>
      </html>
<?php
}


if (isset($_POST['submit']))
{
   $results = mysql_query("show tables like 'LTC_ALL_%'")
              or die ("Unable to get table information!". mysql_error());
   while ($row = mysql_fetch_row($results))
   {
      list($LTC,$ConvCode,$TableName) = explode("_",$row[0]);
      $_SESSION[$TableName] = $row[0];
//      print "<br>Session: ";print_r($_SESSION);
//      print "<br>row: ";print_r($row);
//      print "<br>LTC: ";print_r($LTC);
//      print "<br>ConvCode: ";print_r($ConvCode);
//      print "<br>TableName: ";print_r($TableName);
//      print "<br>"; die();
   }

   $UsersTable = GetTable('Users');

   $results = mysql_query("select count(*)
                           from   $UsersTable
                           where  Userid   = '" . $_POST['userid'] . "'
                           and    Password = '" . $_POST['pwd']    . "'
                           and    Status  != 'L'
                          ")
              or die ("Unable to validate Userid and Password!". mysql_error());
   $row     = mysql_fetch_row($results);
   $success = $row[0];

   if ($success == 1)
   {
      $results = mysql_query("select *
                              from $UsersTable
                              where Userid = '" .$_POST['userid'] ."'
                              and Password = '" .$_POST['pwd']    ."'
                             ")
                 or die ("Unable to read Users information!". mysql_error());
      $row     = mysql_fetch_assoc($results);

      $_SESSION['Admin']     = $row['Admin'];
      if (!$systemDown or $_SESSION['Admin'] == 'Y')
      {
	      $_SESSION['Userid']    = $row['Userid'];
	      $_SESSION['ChurchID']  = $row['ChurchID'];
	      $_SESSION['Name']      = $row['Name'];
	      $_SESSION['Status']    = $row['Status'];
	      $_SESSION['ConvCode']  = $row['ConvCode'];
	      $_SESSION['logged-in'] = 1;

	      $UserID                = $row['Userid'];

	      $results = mysql_query("show tables like 'LTC_".$_SESSION['ConvCode']."_%'")
	                 or die ("Unable to get table information!". mysql_error());
	      while ($row = mysql_fetch_row($results))
	      {
	         list($LTC,$ConvCode,$TableName) = explode("_",$row[0]);
	         $_SESSION[$TableName] = $row[0];
	      }
	      WriteToGlobalLog("Successful Login");

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
      WriteToGlobalLog("Login Failed");
      LoginHTML('BadPass');
   }
}
else
{
   LoginHTML('');
}
