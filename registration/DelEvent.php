<?php
include 'include/RegFunctions.php';

if (isset($_POST['Confirm']))
{
   mysql_query("delete from $EventsTable        where EventID=".$_REQUEST['EventID']) or die ("Unable to delete event record: "        . mysql_error());
   mysql_query("delete from $RegistrationTable  where EventID=".$_REQUEST['EventID']) or die ("Unable to delete registration record: " . mysql_error());
   mysql_query("delete from $EventScheduleTable where EventID=".$_REQUEST['EventID']) or die ("Unable to delete schedule record: "     . mysql_error());

   $TeamList = mysql_query("select TeamID from $TeamsTable where EventID=".$_REQUEST['EventID']) or die ("Unable to delete Team records: "        . mysql_error());
   while ($row = mysql_fetch_assoc($TeamList))
   {
      mysql_query("delete from $TeamMembersTable where TeamID=".$row['TeamID']) or die ("Unable to delete Team Member record: "     . mysql_error());
   }
   mysql_query("delete from $TeamsTable        where EventID=".$_REQUEST['EventID']) or die ("Unable to delete Team records: "        . mysql_error());

   WriteToLog("Event ".$_REQUEST['EventID']." was deleted");
   ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
      <head>
         <title>
            Event Deleted
         </title>
      </head>
      <body style="background-color: rgb(217, 217, 255);">
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

       <head>
          <title>
             Delete Event
          </title>
       </head>

       <body style="background-color: rgb(217, 217, 255);">
          <form method="post" action="DelEvent.php<?php  print "?EventID=".$_REQUEST['EventID']."&EventName=".urlencode($_REQUEST['EventName']); ?>">
             <center>
                <h1>
                   Deleting Event
                </h1>
                <h2>
                   "<?php  print $_REQUEST['EventName']; ?>"
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