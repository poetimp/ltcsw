<?php
include 'include/RegFunctions.php';

if (isset($_POST['Confirm']))
{
   $ParticipantID = $_REQUEST['ParticipantID'];

   mysql_query("delete from $ParticipantsTable where ParticipantID=$ParticipantID") or die ("Unable to delete participant record: "     . mysql_error());
   mysql_query("delete from $TeamMembersTable  where ParticipantID=$ParticipantID") or die ("Unable to delete team membership record: " . mysql_error());

   $RegList = mysql_query("select r.EventID
                           from   $RegistrationTable r,
                                  $EventsTable       e
                           where  r.EventID       = e.EventID
                           and    r.ParticipantID = $ParticipantID
                           and    e.TeamEvent     = 'N'
                          ")
              or die ("Unable to get Registration List".mysql_error());
   while ($Row = mysql_fetch_assoc($RegList))
   {
      $EventID=$Row['EventID'];
      mysql_query("delete
                   from   $RegistrationTable
                   where  ParticipantID = $ParticipantID
                   and    EventID       = $EventID
                  ")
      or die ("Unable to delete registration record: " . mysql_error());
   }
   ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
      <head>
         <title>
            Participant Deleted
         </title>
      </head>
      <body style="background-color: rgb(217, 217, 255);">
         <h1 align=center>
            Participant: <?php  print $_REQUEST['Name']; ?> Deleted!
         </h1>
         <?php footer("Return to Participant List","Participants.php")?>
      </body>
   </html>
<?php
}
else if (isset($_POST['Cancel']))
{
   header("refresh: 0; url=Participants.php");
}
else
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

       <head>
          <title>
             Delete Participant
          </title>
       </head>

       <body style="background-color: rgb(217, 217, 255);">
          <form method="post" action="DelParticipant.php<?php  print "?ParticipantID=".$_REQUEST['ParticipantID']."&Name=".urlencode($_REQUEST['Name']); ?>">
             <center>
                <h1>
                   Deleting Participant
                </h1>
                <h2>
                   "<?php  print $_REQUEST['Name']; ?>"
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