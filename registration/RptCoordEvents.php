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

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <title>
          Coordinators Participants report
       </title>
    </head>

    <body bgcolor="White">
    <?php
//         print "<pre>
//                                 select   c.Name CoordName,
//                                          e.EventID,
//                                          e.EventName,
//                                          CASE e.ConvEvent
//                                             WHEN 'C' THEN 'Convention'
//                                             WHEN 'P' THEN 'Preconvention'
//                                             ELSE          'Other'
//                                          END
//                                          ConvEvent,
//                                          CASE e.TeamEvent
//                                             WHEN 'Y' THEN 'Team'
//                                             WHEN 'N' THEN 'Individual'
//                                             ELSE          'Other'
//                                          END
//                                          TeamEvent,
//                                          s.StartTime,
//                                          s.SchedID,
//                                          e.EventAttended
//                                 from     $EventsTable        e,
//                                          $EventScheduleTable s,
//                                          $EventCoordTable    c
//                                 where    e.EventID = s.EventID
//                                 and      e.CoordID = c.CoordID
//                                 order by c.Name,
//                                          e.EventName,
//                                          s.StartTime
//         </pre>";
         $results = $db->query("select   c.Name CoordName,
                                          e.EventID,
                                          e.EventName,
                                          CASE e.ConvEvent
                                             WHEN 'C' THEN 'Convention'
                                             WHEN 'P' THEN 'Preconvention'
                                             ELSE          'Other'
                                          END
                                          ConvEvent,
                                          CASE e.TeamEvent
                                             WHEN 'Y' THEN 'Team'
                                             WHEN 'N' THEN 'Individual'
                                             ELSE          'Other'
                                          END
                                          TeamEvent,
                                          s.StartTime,
                                          s.SchedID,
                                          e.EventAttended
                                 from     $EventsTable        e,
                                          $EventScheduleTable s,
                                          $EventCoordTable    c
                                 where    e.EventID = s.EventID
                                 and      e.CoordID = c.CoordID
                                 order by c.Name,
                                          e.EventName,
                                          s.StartTime")
                   or die ("Unable to get scheduled event list:" . sqlError());
         ?>
         <?php
         $PrevCoord = "";
         $pageBreak='';
         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            $EventCoord = $row['CoordName'];
            $EventID    = $row['EventID'];
            $EventName  = $row['EventName'];
            $ConvEvent  = $row['ConvEvent'];
            $TeamEvent  = $row['TeamEvent'];
            $EventTime  = TimeToStr($row['StartTime']);
            $EventAttended = ($row['EventAttended'] == 'Y');

            if ($EventAttended)
               $SchedID    = $row['SchedID'];
            else
               $SchedID    = '0000';

            if (($PrevCoord == "") or ($PrevCoord != $EventCoord))
            {
               if ($PrevCoord != "")
               {
                  print "</table>";
               }
               $PrevCoord = $EventCoord;
               ?>
               <h1 align="center" <?php print $pageBreak;$pageBreak="style=\"page-break-before:always;\"";?>>Roster For Director</h1>
               <h2 align="center"><?php print $EventCoord?></h2>
               <hr>

               <table border="0" width="100%">
               <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
               </tr>
               <?php
            }
            if ($TeamEvent == 'Team')
            {
              $sql = "SELECT count(*) as count
                      FROM   $RegistrationTable r,
                             $EventsTable       e
                      WHERE  r.SchedID   = $SchedID
                      AND    r.EventID   = $EventID
                      AND    r.EventID   = e.EventID
                      AND    e.TeamEvent = 'Y'
                      ";
            }
            else
            {
              $sql = "SELECT count(*) as count
                      FROM   $RegistrationTable r,
                             $EventsTable       e
                      WHERE  r.SchedID   = $SchedID
                      AND    r.EventID   = $EventID
                      AND    r.EventID   = e.EventID
                      AND    e.TeamEvent = 'N'
                      ";
            }

            $cntResult = $db->query($sql)
                         or die ("Unable to get Registration count for event:" . sqlError());
            $cntRow    = $cntResult->fetch(PDO::FETCH_ASSOC);
            $numEvents = $cntRow['count'];
            ?>
               <tr>
                  <td bgcolor=#CCCCCC><b><?php  print $EventName; ?></b></td>
                  <td bgcolor=#CCCCCC><b><?php  print $EventTime; ?></b></td>
                  <td bgcolor=#CCCCCC><b><?php  print $ConvEvent; ?></b></td>
                  <td bgcolor=#CCCCCC><b><?php  print $TeamEvent; ?></b></td>
               </tr>
            <?php
            if ($numEvents <= 0)
            {
               ?>
               <tr>
                  <td colspan="4"><b>No Participants Scheuled</b></td>
               </tr>
            <?php
            }
            else
            {
               if ($TeamEvent == 'Team')
               {
                  $sql = "SELECT distinct
                                p.FirstName,
                                p.LastName,
                                p.Phone,
                                p.Email,
                                p.Grade,
                                c.ChurchName,
                                t.TeamID
                          FROM  $ParticipantsTable  p,
                                $RegistrationTable  r,
                                $TeamMembersTable   t,
                                $ChurchesTable      c
                          WHERE p.ChurchID      = r.ChurchID
                          AND   r.ParticipantID = t.TeamID
                          AND   r.SchedID       = $SchedID
                          AND   p.ParticipantID = t.ParticipantID
                          AND   r.EventID       = $EventID
                          AND   c.ChurchID      = r.ChurchID
                          order by t.TeamID,p.LastName";
               }
               else
               {
                  $sql = "SELECT distinct
                                p.FirstName,
                                p.LastName,
                                p.Phone,
                                p.Email,
                                p.Grade,
                                c.ChurchName
                          FROM  $ParticipantsTable p,
                                $RegistrationTable r,
                                $ChurchesTable     c
                          WHERE  p.ChurchID      = r.ChurchID
                          AND    r.SchedID       = $SchedID
                          AND    p.ChurchID      = c.ChurchID
                          AND    p.ParticipantID = r.ParticipantID
                          AND    r.EventID       = $EventID
                          order by p.LastName";
               }

               $members = $db->query($sql) or die ("Unable to obtain member list:" . sqlError());

               $prevTeamID="";
               while ($row = $members->fetch(PDO::FETCH_ASSOC))
               {
                  $Name   = $row['LastName'].", ".$row['FirstName'];
                  $Email  = $row['Email'];
                  $Phone  = $row['Phone'];
                  $Grade  = $row['Grade'];
                  $TeamID = isset($row['TeamID']) ?  $row['TeamID'] : "";

                  $ChurchName = $row['ChurchName'];

                  if ($TeamEvent == 'Team' and $prevTeamID != $TeamID)
                  {
                     print "<tr>";
                     print "   <td bgcolor=#EAEAEA><b>Team: $TeamID</b></td>";
                     print "   <td colspan=3>&nbsp;</td>";
                     print "</tr>";
                     $prevTeamID=$TeamID;
                  }
                  ?>
                  <tr>
                     <td><?php  print "$Name ($Grade)"; ?></td>
                     <td><?php  print $Email; ?></td>
                     <td><?php  print $Phone; ?></td>
                     <td><?php  print $ChurchName; ?></td>
                  </tr>
                  <?php
               }
            }
         }
         ?>
         </table>

    </body>

</html>