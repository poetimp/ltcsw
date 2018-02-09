<?php
//----------------------------------------------------------------------------
// This software is licensed under the MIT license. Use as you wish but give
// and take credit where due.
//
// Author: Paul Lemmons
//----------------------------------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<?php
include 'include/RegFunctions.php';

if (isset($_POST['Confirm']))
{
   $db->query("delete from $EventsTable        where EventID=".$_REQUEST['EventID']) or die ("Unable to delete event record: "        . sqlError());
   $db->query("delete from $RegistrationTable  where EventID=".$_REQUEST['EventID']) or die ("Unable to delete registration record: " . sqlError());
   $db->query("delete from $EventScheduleTable where EventID=".$_REQUEST['EventID']) or die ("Unable to delete schedule record: "     . sqlError());

   $TeamList = $db->query("select TeamID from $TeamsTable where EventID=".$_REQUEST['EventID']) or die ("Unable to delete Team records: "        . sqlError());
   while ($row = $TeamList->fetch(PDO::FETCH_ASSOC))
   {
      $db->query("delete from $TeamMembersTable where TeamID=".$row['TeamID']) or die ("Unable to delete Team Member record: "     . sqlError());
   }
   $db->query("delete from $TeamsTable        where EventID=".$_REQUEST['EventID']) or die ("Unable to delete Team records: "        . sqlError());

   WriteToLog("Event ".$_REQUEST['EventID']." was deleted");
   ?>
      <head>
         <meta name="viewport" content="width=device-width, initial-scale=1.0" />
         <title>
            Event Deleted
         </title>
         <meta http-equiv="Content-Language" content="en-us" />
         <meta name="viewport" content="width=device-width, initial-scale=1.0" />
         <link rel="stylesheet" href="include/registration.css" type="text/css" />
      </head>
      <body>
         <h1 align=center>
            Event <?php  print $_REQUEST['EventName']; ?> Deleted!
         </h1>
         <?php footer("Return to Event List","Events.php")?>
      </body>
   </html>
<?php
}
else if (isset($_POST['Cancel']))
{
   header("refresh: 0; url=Events.php");
}
else
{
?>
       <head>
          <title>
             Delete Event
          </title>
          <meta http-equiv="Content-Language" content="en-us" />
          <meta name="viewport" content="width=device-width, initial-scale=1.0" />
          <link rel="stylesheet" href="include/registration.css" type="text/css" />
       </head>

       <body>
          <form method="post" action="DelEvent.php<?php  print "?EventID=".$_REQUEST['EventID']."&EventName=".urlencode($_REQUEST['EventName']); ?>">
             <div style="text-align: center">
                <h1>
                   Deleting Event
                </h1>
                <h2>
                   "<?php  print $_REQUEST['EventName']; ?>"
                </h2>
             </div>
             <p align="center">
             <input type="submit" value="Confirm Delete!" name="Confirm">
             <font size="5"><br />
             or</font><br />
             <input type="submit" value="Cancel" name="Cancel">
             </p>
          </form>
       </body>

   </html>
<?php
}
?>