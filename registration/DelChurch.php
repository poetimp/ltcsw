<?php
//----------------------------------------------------------------------------
// This software is licensed under the MIT license. Use as you wish but give
// and take credit where due.
//
// Author: Paul Lemmons
//----------------------------------------------------------------------------
require 'include/RegFunctions.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<?php

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

if (isset($_POST['Confirm']))
{
   $ChurchID=$_REQUEST['ChurchID'];

   $db->query("delete from $ChurchesTable         where ChurchID=$ChurchID")
         or die ("Unable to delete church record: "           . sqlError());
   $db->query("delete from $UsersTable            where ChurchID=$ChurchID")
         or die ("Unable to delete user records: "           . sqlError());
   $db->query("delete from $RegistrationTable     where ChurchID=$ChurchID")
         or die ("Unable to delete registration records: "    . sqlError());
   $db->query("delete from $ParticipantsTable     where ChurchID=$ChurchID")
         or die ("Unable to delete participants records: "    . sqlError());
   $db->query("delete from $TeamsTable            where ChurchID=$ChurchID")
         or die ("Unable to delete team records: "            . sqlError());
   $db->query("delete from $TeamMembersTable      where ChurchID=$ChurchID")
         or die ("Unable to delete team members records: "    . sqlError());
   $db->query("delete from $ExtraOrdersTable      where ChurchID=$ChurchID")
         or die ("Unable to delete extra orders records: "    . sqlError());
   $db->query("delete from $NonParticipantsTable  where ChurchID=$ChurchID")
         or die ("Unable to delete Non-Participant records: " . sqlError());
   $db->query("delete from $JudgeAssignmentsTable where ChurchID=$ChurchID")
         or die ("Unable to delete Judging Assignmrnts records: " . sqlError());
   $db->query("delete from $JudgesTable           where ChurchID=$ChurchID")
         or die ("Unable to delete Judges records: " . sqlError());


   WriteToLog("Church ".$_REQUEST['ChurchID']." was deleted");
   ?>
      <head>
         <meta name="viewport" content="width=device-width, initial-scale=1.0" />
         <title>
            Church Deleted
         </title>
         <meta http-equiv="Content-Language" content="en-us" />
         <meta name="viewport" content="width=device-width, initial-scale=1.0" />
         <link rel="stylesheet" href="include/registration.css" type="text/css" />
      </head>
      <body>
         <h1 align="center">
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
       <head>
          <title>
             Delete Church
          </title>
          <meta http-equiv="Content-Language" content="en-us" />
          <meta name="viewport" content="width=device-width, initial-scale=1.0" />
          <link rel="stylesheet" href="include/registration.css" type="text/css" />
       </head>

       <body>
          <form method="post" action="DelChurch.php<?php  print "?ChurchID=".$_REQUEST['ChurchID']."&ChurchName=".urlencode($_REQUEST['ChurchName']); ?>">
             <div style="text-align: center">
                <h1>
                   Deleting Church
                </h1>
                <h2>
                   "<?php  print $_REQUEST['ChurchName']; ?>"
                </h2>
             </div>
             <p align="center">
             <input type="submit" value="Confirm Delete!" name="Confirm" />
             <font size="5"><br />
             or</font><br />
             <input type="submit" value="Cancel" name="Cancel" />
             </p>
          </form>
       </body>

   </html>
<?php
}
?>
