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

$byTime = isset($_REQUEST['byTime']);
//-----------------------------------------------------------------------------
// Retrieve a list of all avaliable rooms
//-----------------------------------------------------------------------------
$roomList  = getRoomList();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <meta http-equiv="Content-Language" content="en-us">
       <title>
          Scheduled Events Roster
       </title>
    </head>

    <body bgcolor="White">
    <h1 align="center">Schedule Roster</h1>
    <hr>
    <?php
         if ($byTime)
            $sortBy="s.StartTime,e.EventName";
         else
            $sortBy="e.EventName,s.StartTime";

         $results = $db->query("select   e.EventID,
                                          e.EventName,
                                          e.ConvEvent,
                                          e.TeamEvent,
                                          s.StartTime,
                                          s.SchedID,
                                          Case
                                             when e.EventAttended='N'            then '0000'
                                             else                                s.SchedID
                                          end
                                          SchedID,
                                          s.RoomID
                                 from     $EventsTable   e,
                                          $EventScheduleTable s
                                 where    e.EventID = s.EventID
                                 order by $sortBy")
                   or die ("Unable to get scheduled event list:" . sqlError());
         $first = 1;
         ?>
         <table class='registrationTable' border="0" width="100%" id="table1">
         <?php
         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            $EventID   = $row['EventID'];
            $EventName = $row['EventName'];
            $ConvEvent = $row['ConvEvent'] == "C" ? "Convention" : "Preconvention";
            $TeamEvent = $row['TeamEvent'] == "Y" ? "Team"       : "Individual";
            $EventTime = TimeToStr($row['StartTime']);
            $SchedID   = $row['SchedID'];
            $RoomIDList= explode(',',$row['RoomID']);

            $RoomNameList = '';
            foreach ($roomList as $RoomNum => $RoomName)
            {
               if (in_array($RoomNum,$RoomIDList))
               {
                  if ($RoomNameList == '')
                     $RoomNameList = $RoomName;
                  else
                     $RoomNameList .= ' or '.$RoomName;
               }
            }
            if ($AdminReport)
            {
               if ($TeamEvent == 'Team')
               {
                  $sql = "SELECT distinct
                                 count(*) as count
                          FROM   $ParticipantsTable p,
                                 $RegistrationTable r,
                                 $TeamMembersTable  t,
                                 $ChurchesTable     c
                          WHERE  p.ChurchID      = r.ChurchID
                          AND    r.ParticipantID = t.TeamID
                          AND    r.SchedID       = $SchedID
                          AND    p.ParticipantID = t.ParticipantID
                          AND    r.EventID       = $EventID
                          AND    c.ChurchID      = r.ChurchID
                          ";
               }
               else
               {
                  $sql = "SELECT distinct
                                 count(*) as count
                          FROM   $ParticipantsTable p,
                                 $RegistrationTable r,
                                 $ChurchesTable     c
                          WHERE  p.ChurchID      = r.ChurchID
                          AND    r.SchedID       = $SchedID
                          AND    p.ParticipantID = r.ParticipantID
                          AND    r.EventID       = $EventID
                          ";
               }
            }
            else
            {
               if ($TeamEvent == 'Team')
               {
                  $sql = "SELECT distinct
                                 count(*) as count
                          FROM   $ParticipantsTable p,
                                 $RegistrationTable r,
                                 $TeamMembersTable  t
                          WHERE  p.ChurchID      = r.ChurchID
                          AND    r.SchedID       = $SchedID
                          AND    r.ParticipantID = t.TeamID
                          AND    p.ParticipantID = t.ParticipantID
                          AND    r.EventID       = $EventID
                          AND    r.ChurchID      = $ChurchID
                          ";
               }
               else
               {
                  $sql = "SELECT distinct
                                 count(*) as count
                          FROM   $ParticipantsTable p,
                                 $RegistrationTable r
                          WHERE  p.ChurchID      = r.ChurchID
                          AND    r.SchedID       = $SchedID
                          AND    p.ParticipantID = r.ParticipantID
                          AND    r.EventID       = $EventID
                          AND    r.ChurchID      = $ChurchID
                          ";
               }
            }

            $cntResult = $db->query($sql)
                         or die ("Unable to get Registration count for event:" . sqlError());
            $cntRow    = $cntResult->fetch(PDO::FETCH_ASSOC);
            $numEvents = $cntRow['count'];

            if ($numEvents > 0)
            {
               if ($first == 0)
               {
                  ?>
                  <tr>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                  </tr>
                  <?php
               }
               else
               {
                  $first = 0;
               }
               ?>
               <tr>
                  <?php
                  if ($byTime)
                  {?>
                     <td bgcolor=#CCCCCC><b><?php  print "$EventTime ($RoomNameList)"; ?></b></td>
                     <td bgcolor=#CCCCCC><b><?php  print $EventName; ?></b></td>
                  <?php
                  }
                  else
                  {?>
                     <td bgcolor=#CCCCCC><b><?php  print $EventName; ?></b></td>
                     <td bgcolor=#CCCCCC><b><?php  print "$EventTime ($RoomNameList)"; ?></b></td>
                  <?php
                  }
                  ?>
                  <td bgcolor=#CCCCCC><b><?php  print $ConvEvent; ?></b></td>
                  <td bgcolor=#CCCCCC><b><?php  print $TeamEvent; ?></b></td>
               </tr>
               <?php
               if ($AdminReport)
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
                             FROM   $ParticipantsTable p,
                                    $RegistrationTable r,
                                    $TeamMembersTable  t,
                                    $ChurchesTable     c
                             WHERE  p.ChurchID      = r.ChurchID
                             AND    r.ParticipantID = t.TeamID
                             AND    r.SchedID       = $SchedID
                             AND    p.ParticipantID = t.ParticipantID
                             AND    r.EventID       = $EventID
                             AND    c.ChurchID      = r.ChurchID
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
                             FROM   $ParticipantsTable p,
                                    $RegistrationTable r,
                                    $ChurchesTable     c
                             WHERE  p.ChurchID      = r.ChurchID
                             AND    r.SchedID       = $SchedID
                             AND    p.ChurchID      = c.ChurchID
                             AND    p.ParticipantID = r.ParticipantID
                             AND    r.EventID       = $EventID
                             order by p.LastName";
                  }
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
                                    t.TeamID
                             FROM   $ParticipantsTable p,
                                    $RegistrationTable r,
                                    $TeamMembersTable  t
                             WHERE  p.ChurchID      = r.ChurchID
                             AND    r.SchedID       = $SchedID
                             AND    r.ParticipantID = t.TeamID
                             AND    p.ParticipantID = t.ParticipantID
                             AND    r.EventID       = $EventID
                             AND    r.ChurchID      = $ChurchID
                             order by t.TeamID,p.LastName";
                  }
                  else
                  {
                     $sql = "SELECT distinct
                                    p.FirstName,
                                    p.LastName,
                                    p.Phone,
                                    p.Email,
                                    p.Grade
                             FROM   $ParticipantsTable p,
                                    $RegistrationTable r
                             WHERE  p.ChurchID      = r.ChurchID
                             AND    r.SchedID       = $SchedID
                             AND    p.ParticipantID = r.ParticipantID
                             AND    r.EventID       = $EventID
                             AND    r.ChurchID      = $ChurchID
                             order by p.LastName";
                  }
               }
               $members = $db->query($sql) or die ("Not found:" . sqlError());

               $prevTeamID="";
               while ($row = $members->fetch(PDO::FETCH_ASSOC))
               {
                  $Name   = $row['LastName'].", ".$row['FirstName'];
                  $Email  = $row['Email'];
                  $Phone  = $row['Phone'];
                  $Grade  = $row['Grade'];
                  $TeamID = isset($row['TeamID']) ?  $row['TeamID'] : "";

                  if ($AdminReport)
                  {
                     $ChurchName = $row['ChurchName'];
                  }
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
                     <td><?php  print $AdminReport ? $ChurchName : "&nbsp;"; ?></td>
                  </tr>
                  <?php
               }
            }
         }
         ?>
         </table>

    </body>

</html>