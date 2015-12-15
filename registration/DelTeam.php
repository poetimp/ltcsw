<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<?php
include 'include/RegFunctions.php';

if (isset($_POST['Confirm']))
{
   $TeamID = $_REQUEST['TeamID'];
   $db->query("delete from $TeamsTable        where TeamID=$TeamID") or die ("Unable to delete Team record: "         . sqlError());
   $db->query("delete from $TeamMembersTable  where TeamID=$TeamID") or die ("Unable to delete Members: "             . sqlError());

   $RegList = $db->query("select r.EventID
                           from   $RegistrationTable r,
                                  $EventsTable       e
                           where  r.EventID       = e.EventID
                           and    r.ParticipantID = $TeamID
                           and    e.TeamEvent     = 'Y'
                          ")
              or die ("Unable to get Registration List".sqlError());
   while ($Row = $RegList->fetch(PDO::FETCH_ASSOC))
   {
      $EventID=$Row['EventID'];
      $db->query("delete
                   from   $RegistrationTable
                   where  ParticipantID = $TeamID
                   and    EventID       = $EventID
                  ")
      or die ("Unable to delete registration record: " . sqlError());
   }
   ?>
      <head>
         <title>
            Team Deleted
         </title>
      </head>
      <body style="background-color: rgb(217, 217, 255);">
         <h1 align=center>
            Team Deleted!
         </h1>
         <?php footer("Return to Team List","SignupTeamEvents.php")?>

      </body>
   </html>
<?php
}
else if (isset($_POST['Cancel']))
{
   header("refresh: 0; url=SignupTeamEvents.php");
}
else
{
?>


       <head>
          <title>
             Delete Team
          </title>
       </head>

       <body style="background-color: rgb(217, 217, 255);">
          <form method="post" action="DelTeam.php<?php  print "?TeamID=".$_REQUEST['TeamID']; ?>">
             <center>
                <h1>
                   Deleting Team
                </h1>
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