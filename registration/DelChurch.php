<?php
require 'include/RegFunctions.php';

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

if (isset($_POST['Confirm']))
{
   $ChurchID=$_REQUEST['ChurchID'];

   mysql_query("delete from $ChurchesTable         where ChurchID=$ChurchID")
         or die ("Unable to delete church record: "           . mysql_error());
   mysql_query("delete from $UsersTable            where ChurchID=$ChurchID")
         or die ("Unable to delete user records: "           . mysql_error());
   mysql_query("delete from $RegistrationTable     where ChurchID=$ChurchID")
         or die ("Unable to delete registration records: "    . mysql_error());
   mysql_query("delete from $ParticipantsTable     where ChurchID=$ChurchID")
         or die ("Unable to delete participants records: "    . mysql_error());
   mysql_query("delete from $TeamsTable            where ChurchID=$ChurchID")
         or die ("Unable to delete team records: "            . mysql_error());
   mysql_query("delete from $TeamMembersTable      where ChurchID=$ChurchID")
         or die ("Unable to delete team members records: "    . mysql_error());
   mysql_query("delete from $ExtraOrdersTable      where ChurchID=$ChurchID")
         or die ("Unable to delete extra orders records: "    . mysql_error());
   mysql_query("delete from $NonParticipantsTable  where ChurchID=$ChurchID")
         or die ("Unable to delete Non-Participant records: " . mysql_error());
   mysql_query("delete from $JudgeAssignmentsTable where ChurchID=$ChurchID")
         or die ("Unable to delete Judging Assignmrnts records: " . mysql_error());
   mysql_query("delete from $JudgesTable           where ChurchID=$ChurchID")
         or die ("Unable to delete Judges records: " . mysql_error());


   WriteToLog("Church ".$_REQUEST['ChurchID']." was deleted");
   ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
      <head>
         <title>
            Church Deleted
         </title>
      </head>
      <body style="background-color: rgb(217, 217, 255);">
         <h1 align=center>
            Church <?php  print $_REQUEST['ChurchName']; ?> Deleted!
         </h1>
         <?php footer("Return to Church List","Churches.php")?>
      </body>
   </html>
<?php
}
else if (isset($_POST['Cancel']))
{
   header("refresh: 0; url=Churches.php");
}
else
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

       <head>
          <title>
             Delete Church
          </title>
       </head>

       <body style="background-color: rgb(217, 217, 255);">
          <form method="post" action="DelChurch.php<?php  print "?ChurchID=".$_REQUEST['ChurchID']."&ChurchName=".urlencode($_REQUEST['ChurchName']); ?>">
             <center>
                <h1>
                   Deleting Church
                </h1>
                <h2>
                   "<?php  print $_REQUEST['ChurchName']; ?>"
                </h2>
             </center>
             <p align="center">
             <input type="submit" value="Confirm Delete!" name="Confirm">
             <font size="5"><br>
             or</font><br>
             <input type="submit" value="Cancel" name="Cancel">
             </p>
          </form>
       </body>

   </html>
<?php
}
?>
