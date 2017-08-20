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

$prevTime = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <meta http-equiv="Content-Language" content="en-us">
       <title>
          Event Participation
       </title>
       <meta http-equiv="Content-Language" content="en-us">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link rel=stylesheet href="include/registration.css" type="text/css" />
    </head>

    <body>
    <h1 align="center">Scheduled Event Counts</h1>
    <hr>
    <?php
         //--------------------------------------------------------------------
         // First get a list of all scheduled events
         //--------------------------------------------------------------------
         $results = $db->query("select   e.EventID,
                                          e.EventName,
                                          (e.MaxWebSlots * e.MaxRooms) MaxWebSlots,
                                          CASE e.ConvEvent
                                             WHEN 'C' THEN 'Convention'
                                             WHEN 'P' THEN 'Pre-Convention'
                                             ELSE          'Other'
                                          END
                                          ConvEvent,
                                          CASE e.TeamEvent
                                             WHEN 'Y' THEN 'Team'
                                             WHEN 'N' THEN 'Individual'
                                             ELSE          'Other'
                                          END
                                          EventType,
                                          s.StartTime,
                                          s.SchedID,
                                          r.RoomName
                                 from     $EventsTable        e,
                                          $EventScheduleTable s,
                                          $RoomsTable         r
                                 where    e.EventID       = s.EventID
                                 and      e.EventAttended = 'Y'
                                 and      s.RoomID        = r.RoomID
                                 order by s.StartTime,
                                          e.EventName,
                                          r.RoomName")
                   or die ("Unable to get scheduled event list:" . sqlError());
         $first = 1;
         ?>
         <table class='registrationTable'>
         <?php
         //--------------------------------------------------------------------
         // No loop through the events reporting on the details
         //--------------------------------------------------------------------
         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            $EventID     = $row['EventID'];
            $EventName   = $row['EventName'];
            $ConvEvent   = $row['ConvEvent'];
            $EventType   = $row['EventType'];
            $EventTime   = TimeToStr($row['StartTime']);
            $SchedID     = $row['SchedID'];
            $MaxWebSlots = $row['MaxWebSlots'];
            $RoomName    = $row['RoomName'];

            if ($EventType == 'Team')
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

            if ($prevTime != $EventTime)
            {
               ?>
               <tr>
                  <th colspan=4><b><?php  print $EventTime; ?></b></th>
               </tr>
               <?php
               $prevTime = $EventTime;
            }
            ?>
            <tr>
               <td style='width: 25%'><?php  print $EventName; ?></td>
               <td style='width: 25%'><?php  print preg_replace('/\s*-\s*[a-zA-Z]\s*$/','',$RoomName) ?></td>
               <td style='width: 10%'><?php  if ($numEvents > $MaxWebSlots){print "<font color=\"red\">";} print "$numEvents of $MaxWebSlots"; if ($numEvents > $MaxWebSlots){print "</font>";}?></td>
               <td style='width: 40%'><?php  print $EventType; ?></td>
            </tr>
         <?php
         }
         ?>
         </table>

    <h1 align="center">Unscheduled Event Counts</h1>
    <hr>
    <?php
         //--------------------------------------------------------------------
         // First get a list of all Unscheduled events
         //--------------------------------------------------------------------
         $results = $db->query("select   e.EventID,
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
                                          EventType
                                 from     $EventsTable   e
                                 where    e.EventAttended = 'N'
                                 order by e.ConvEvent,
                                          e.EventName")
                   or die ("Unable to get Unscheduled event list:" . sqlError());
         $first = 1;
         ?>
         <table class='registrationTable'>
         <?php
         //--------------------------------------------------------------------
         // No loop through the events reporting on the details
         //--------------------------------------------------------------------
         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            $EventID   = $row['EventID'];
            $EventName = $row['EventName'];
            $ConvEvent = $row['ConvEvent'];
            $EventType = $row['EventType'];

            if ($EventType == 'Team')
            {
              $sql = "SELECT count(*) as count
                      FROM   $RegistrationTable r,
                             $EventsTable       e
                      WHERE  r.EventID   = $EventID
                      AND    r.EventID   = e.EventID
                      AND    e.TeamEvent = 'Y'
                      ";
            }
            else
            {
              $sql = "SELECT count(*) as count
                      FROM   $RegistrationTable r,
                             $EventsTable       e
                      WHERE  r.EventID   = $EventID
                      AND    r.EventID   = e.EventID
                      AND    e.TeamEvent = 'N'
                      ";
            }

            $cntResult = $db->query($sql)
                         or die ("Unable to get Registration count for event:" . sqlError());
            $cntRow    = $cntResult->fetch(PDO::FETCH_ASSOC);
            $numEvents = $cntRow['count'];

            if ($prevTime != $ConvEvent)
            {
               ?>
               <tr>
                  <th colspan=3><b><?php  print $ConvEvent; ?></b></th>
               </tr>
               <?php
               $prevTime = $ConvEvent;
            }
            ?>
            <tr>
               <td style='width: 35%'><?php  print $EventName; ?></td>
               <td style='width: 10%'><?php  print $numEvents; ?></td>
               <td style='width: 55%'><?php  print $EventType; ?></td>
            </tr>
         <?php
         }
         ?>
         </table>


    </body>
</html>
