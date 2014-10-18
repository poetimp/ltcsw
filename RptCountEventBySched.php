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
    </head>

    <body bgcolor="White">
    <h1 align="center">Scheduled Event Counts</h1>
    <hr>
    <?php
         //--------------------------------------------------------------------
         // First get a list of all scheduled events
         //--------------------------------------------------------------------
         $results = mysql_query("select   e.EventID,
                                          e.EventName,
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
                                          s.SchedID
                                 from     $EventsTable        e,
                                          $EventScheduleTable s
                                 where    e.EventID = s.EventID
                                 and      e.EventAttended = 'Y'
                                 order by s.StartTime,
                                          e.EventName")
                   or die ("Unable to get scheduled event list:" . mysql_error());
         $first = 1;
         ?>
         <table border="1" width="100%">
         <?php
         //--------------------------------------------------------------------
         // No loop through the events reporting on the details
         //--------------------------------------------------------------------
         while ($row = mysql_fetch_assoc($results))
         {
            $EventID   = $row['EventID'];
            $EventName = $row['EventName'];
            $ConvEvent = $row['ConvEvent'];
            $EventType = $row['EventType'];
            $EventTime = TimeToStr($row['StartTime']);
            $SchedID   = $row['SchedID'];

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

            $cntResult = mysql_query($sql)
                         or die ("Unable to get Registration count for event:" . mysql_error());
            $cntRow    = mysql_fetch_assoc($cntResult);
            $numEvents = $cntRow['count'];

            if ($prevTime != $EventTime)
            {
               ?>
               <tr>
                  <td bgcolor="#C0C0C0" colspan=3><b><?php  print $EventTime; ?></b></td>
               </tr>
               <?php
               $prevTime = $EventTime;
            }
            ?>
            <tr>
               <td width="35%"><?php  print $EventName; ?></td>
               <td width="10%"><?php  print $numEvents; ?></td>
               <td width="55%"><?php  print $EventType; ?></td>
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
         $results = mysql_query("select   e.EventID,
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
                   or die ("Unable to get Unscheduled event list:" . mysql_error());
         $first = 1;
         ?>
         <table border="1" width="100%">
         <?php
         //--------------------------------------------------------------------
         // No loop through the events reporting on the details
         //--------------------------------------------------------------------
         while ($row = mysql_fetch_assoc($results))
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

            $cntResult = mysql_query($sql)
                         or die ("Unable to get Registration count for event:" . mysql_error());
            $cntRow    = mysql_fetch_assoc($cntResult);
            $numEvents = $cntRow['count'];

            if ($prevTime != $ConvEvent)
            {
               ?>
               <tr>
                  <td bgcolor="#C0C0C0" colspan=3><b><?php  print $ConvEvent; ?></b></td>
               </tr>
               <?php
               $prevTime = $ConvEvent;
            }
            ?>
            <tr>
               <td width="35%"><?php  print $EventName; ?></td>
               <td width="10%"><?php  print $numEvents; ?></td>
               <td width="55%"><?php  print $EventType; ?></td>
            </tr>
         <?php
         }
         ?>
         </table>


    </body>
</html>
