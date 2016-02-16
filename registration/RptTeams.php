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

if (isset($_REQUEST['Admin']) and $Admin == 'Y')
{
   $AdminReport = 1;
}
else
{
   $AdminReport = 0;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

   <head>
      <title>
         Participants with Events
      </title>
   </head>
   <body bgcolor="White">
   <?php

   function PrintTeams($ChurchID)
   {
      global $ChurchesTable,
             $EventsTable,
             $RegistrationTable,
             $ParticipantsTable,
             $TeamMembersTable,
             $db;
      $pageBreak='';
      $results = $db->query("select ChurchName
                              from   $ChurchesTable
                              where  ChurchID = '$ChurchID'")
                 or die ("Unable to get church name:" . sqlError());
      $row = $results->fetch(PDO::FETCH_ASSOC);
      $ChurchName = $row['ChurchName'];
      ?>
         <h1 align="center" <?php print $pageBreak;$pageBreak="style=\"page-break-before:always;\"";?>>Team Rosters</h1>
         <h1 align="center"><?php  print "$ChurchName";?></h1>
         <hr>
      <?php
      $results = $db->query("select   EventID,
                                       EventName,
                                       ConvEvent
                              from     $EventsTable
                              where    TeamEvent = 'Y'
                              order by EventName")
                 or die ("Not found:" . sqlError());
      $first = 1;
      ?>
      <table border="0" width="100%" id="table1">
      <?php
      while ($row = $results->fetch(PDO::FETCH_ASSOC))
      {
         $EventID   = $row['EventID'];
         $EventName = $row['EventName'];
         $ConvEvent = $row['ConvEvent'] == "C" ? "Convention" : "Preconvention";

         $cntResult = $db->query("select count(*) as count
                                   from   $RegistrationTable
                                   where  ChurchID = '$ChurchID'
                                   and    EventID = '$EventID'")
                      or die ("Not found:" . sqlError());
         $cntRow = $cntResult->fetch(PDO::FETCH_ASSOC);
         $numEvents = $cntRow['count'];

         if ($numEvents > 0)
         {
            if ($first == 0)
            {
            ?>
               <tr>
                  <td colspan="3">&nbsp;</td>
               </tr>
            <?php
            }
            else
            {
               $first = 0;
            }
            ?>
            <tr>
               <td bgcolor="Silver"><b><?php  print $EventName; ?></b></td>
               <td bgcolor="Silver" colspan="2"><b><?php  print $ConvEvent; ?></b></td>
            </tr>
            <?php
            $members = $db->query("SELECT p.FirstName,
                                           p.LastName,
                                           p.Phone,
                                           p.Email,
                                           p.Grade,
                                           t.TeamID
                                    FROM   $ParticipantsTable p,
                                           $RegistrationTable r,
                                           $TeamMembersTable  t
                                    WHERE  p.ChurchID = r.ChurchID
                                    AND    r.ParticipantID = t.TeamID
                                    AND    p.ParticipantID = t.ParticipantID
                                    AND    r.EventID  = $EventID
                                    AND    r.ChurchID = $ChurchID
                                    order by t.TeamID,p.LastName")
                       or die ("Not found:" . sqlError());

            $prevTeamID='';
            while ($row = $members->fetch(PDO::FETCH_ASSOC))
            {
               $Name   = $row['LastName'].", ".$row['FirstName'];
               $Email  = $row['Email'];
               $Phone  = $row['Phone'];
               $Grade  = $row['Grade'];
               $TeamID = $row['TeamID'];

               if ($TeamID != $prevTeamID)
               {
                  print "<tr>";
                  print "   <td><b><u>Team: $TeamID</u></b></td>";
                  print "</tr>";
                  $prevTeamID=$TeamID;
               }
               ?>
               <tr>
                  <td><?php  print "$Name ($Grade)"; ?></td>
                  <td><?php  print $Email; ?></td>
                  <td><?php  print $Phone; ?></td>
               </tr>
               <?php
            }
         }
      }
      ?>
      </table>
   <?php
   }
   if ($AdminReport)
   {
      $churches = $db->query("select   ChurchID
                               from     $ChurchesTable
                               order by ChurchName")
                  or die ("Not found:" . sqlError());

      while ($row = $churches->fetch(PDO::FETCH_ASSOC))
      {
         $ChurchID = $row['ChurchID'];
         $count = $db->query("select count(*) as count
                               from   $RegistrationTable r,
                                      $EventsTable       e
                               where  r.EventID = e.EventID
                               and    e.TeamEvent = 'Y'
                               and    r.ChurchID = $ChurchID")
                  or die ("Not found:" . sqlError());
         $cntRow = $count->fetch(PDO::FETCH_ASSOC);
         if ($cntRow['count'] > 0)
         {
            PrintTeams($ChurchID);
         }
      }
   }
   else
   {
      PrintTeams($ChurchID);
   }
   ?>
   </body>
</html>