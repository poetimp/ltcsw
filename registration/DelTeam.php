<?php
//----------------------------------------------------------------------------
// This software is licensed under the MIT license. Use as you wish but give
// and take credit where due.
//
// Author: Paul Lemmons
//----------------------------------------------------------------------------
include 'include/RegFunctions.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<?php

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
         <meta name="viewport" content="width=device-width, initial-scale=1.0" />
         <title>
            Team Deleted
         </title>
         <meta http-equiv="Content-Language" content="en-us" />
         <meta name="viewport" content="width=device-width, initial-scale=1.0" />
         <link rel="stylesheet" href="include/registration.css" type="text/css" />
      </head>
      <body>
         <h1 align="center">
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
          <meta http-equiv="Content-Language" content="en-us" />
          <meta name="viewport" content="width=device-width, initial-scale=1.0" />
          <link rel="stylesheet" href="include/registration.css" type="text/css" />
       </head>

       <body>
          <form method="post" action="DelTeam.php<?php  print "?TeamID=".$_REQUEST['TeamID']; ?>">
             <div style="text-align: center">
                <h1>
                   Deleting Team
                </h1>
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