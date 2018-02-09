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
   $ParticipantID = $_REQUEST['ParticipantID'];

   $db->query("delete from $ParticipantsTable where ParticipantID=$ParticipantID") or die ("Unable to delete participant record: "     . sqlError());
   $db->query("delete from $TeamMembersTable  where ParticipantID=$ParticipantID") or die ("Unable to delete team membership record: " . sqlError());

   $RegList = $db->query("select r.EventID
                           from   $RegistrationTable r,
                                  $EventsTable       e
                           where  r.EventID       = e.EventID
                           and    r.ParticipantID = $ParticipantID
                           and    e.TeamEvent     = 'N'
                          ")
              or die ("Unable to get Registration List".sqlError());
   while ($Row = $RegList->fetch(PDO::FETCH_ASSOC))
   {
      $EventID=$Row['EventID'];
      $db->query("delete
                   from   $RegistrationTable
                   where  ParticipantID = $ParticipantID
                   and    EventID       = $EventID
                  ")
      or die ("Unable to delete registration record: " . sqlError());
   }
   ?>
      <head>
         <meta name="viewport" content="width=device-width, initial-scale=1.0" />
         <title>
            Participant Deleted
         </title>
         <meta http-equiv="Content-Language" content="en-us" />
         <meta name="viewport" content="width=device-width, initial-scale=1.0" />
         <link rel="stylesheet" href="include/registration.css" type="text/css" />
      </head>
      <body>
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

       <head>
          <title>
             Delete Participant
          </title>
          <meta http-equiv="Content-Language" content="en-us" />
          <meta name="viewport" content="width=device-width, initial-scale=1.0" />
          <link rel="stylesheet" href="include/registration.css" type="text/css" />
       </head>

       <body>
          <form method="post" action="DelParticipant.php<?php  print "?ParticipantID=".$_REQUEST['ParticipantID']."&Name=".urlencode($_REQUEST['Name']); ?>">
             <div style="text-align: center">
                <h1>
                   Deleting Participant
                </h1>
                <h2>
                   "<?php  print $_REQUEST['Name']; ?>"
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