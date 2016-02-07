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
WriteToLog("Entered Tally System");

$prevTime = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
   <script language="JavaScript" src="include/jscriptFunctions.js"></script>

    <head>
       <title>
          Assign Awards
       </title>
    </head>

   <body style="background-color: rgb(217, 217, 255);">
    <h1 align="center">Select Event to Assign Awards</h1>
    <hr>
    <?php
         //--------------------------------------------------------------------
         // First get a list of all scheduled events
         //--------------------------------------------------------------------
         $results = $db->query("select    e.EventID,
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
                                          EventType,
                                          s.StartTime,
                                          s.SchedID,
                                          e.IndividualAwards
                                 from     $EventsTable   e,
                                          $EventScheduleTable s
                                 where    e.EventID = s.EventID
                                 and      e.EventAttended = 'Y'
                                 order by s.StartTime,
                                          e.EventName")
                   or die ("Unable to get scheduled event list:" . sqlError());
         $first = 1;
         ?>
         <table border="1"
                width="100%"
                onmouseover="javascript:trackTableHighlight(event, '#8888FF');"
                onmouseout="javascript:highlightTableRow(0);"
         >
         <?php
         //--------------------------------------------------------------------
         // No loop through the events reporting on the details
         //--------------------------------------------------------------------
         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            $EventID          = $row['EventID'];
            $EventName        = $row['EventName'];
            $ConvEvent        = $row['ConvEvent'];
            $EventType        = $row['EventType'];
            $EventTime        = TimeToStr($row['StartTime']);
            $SchedID          = $row['SchedID'];
            $IndividualAwards = ($row['IndividualAwards'] == 'Y');

            if ($EventType == 'Team')
            {
               $cntResult = $db->query("SELECT  count(distinct r.ParticipantID,
                                                               r.EventID)
                                         FROM   $RegistrationTable r,
                                                $EventsTable       e,
                                                $TeamMembersTable  m
                                         WHERE  r.SchedID   = $SchedID
                                         AND    r.EventID   = $EventID
                                         AND    r.EventID   = e.EventID
                                         AND    e.TeamEvent = 'Y'
                                         AND    m.TeamID    = r.ParticipantID
                                        ")
                            or die ("Unable to get Registration count for event:" . sqlError());;
               $numEvents = $cntResult->fetchColumn();

               if ($IndividualAwards)
               {
                  $cntResult = $db->query("SELECT  count(distinct m.ParticipantID)
                                             FROM  $TeamMembersTable m,
                                                   $TeamsTable       t
                                             WHERE m.TeamID  = t.TeamID
                                             and   t.EventID = $EventID
                                            ")
                                 or die ("Unable to get Registration count for teams members:" . sqlError());;
                  $numEvents += $cntResult->fetchColumn();
               }
            }
            else
            {
              $cntResult = $db->query("SELECT  count(distinct r.ParticipantID,
                                                              r.EventID)
                                        FROM   $RegistrationTable r,
                                               $EventsTable       e
                                        WHERE  r.SchedID   = $SchedID
                                        AND    r.EventID   = $EventID
                                        AND    r.EventID   = e.EventID
                                        AND    e.TeamEvent = 'N'
                                       ")
                           or die ("Unable to get Registration count for event:" . sqlError());;
               $numEvents    = $cntResult->fetchColumn();
            }

            if ($numEvents == 0)
               $numAwards = 0;
            else
            {
               if ($EventType == 'Team')
               {
                 $cntResult = $db->query("SELECT  count(distinct r.ParticipantID,
                                                                 r.EventID)
                                           FROM   $RegistrationTable r,
                                                  $EventsTable       e,
                                                  $TeamMembersTable  m
                                           WHERE  r.SchedID   = $SchedID
                                           AND    r.EventID   = $EventID
                                           AND    r.EventID   = e.EventID
                                           AND    e.TeamEvent = 'Y'
                                           AND    m.TeamID    = r.ParticipantID
                                           AND    r.Award Is Not Null
                                         ")
                             or die ("Unable to get Registration count for event:" . sqlError());;
                  $numAwards = $cntResult->fetchColumn();

                  if ($IndividualAwards)
                  {
                     $cntResult = $db->query("SELECT count(distinct m.ParticipantID)
                                                FROM  $TeamMembersTable m,
                                                      $TeamsTable       t
                                                WHERE m.TeamID  = t.TeamID
                                                and   t.EventID = $EventID
                                                and   m.Award is not null
                                             ")
                                    or die ("Unable to get Registration count for teams members:" . sqlError());;
                     $numAwards += $cntResult->fetchColumn();
                  }
               }
               else
               {
                 $cntResult = $db->query("SELECT  count(distinct r.ParticipantID,
                                                                 r.EventID)
                                           FROM   $RegistrationTable r,
                                                  $EventsTable       e
                                           WHERE  r.SchedID   = $SchedID
                                           AND    r.EventID   = $EventID
                                           AND    r.EventID   = e.EventID
                                           AND    e.TeamEvent = 'N'
                                           AND    r.Award Is Not Null
                                         ")
                             or die ("Unable to get Registration count for event:" . sqlError());;
                  $numAwards = $cntResult->fetchColumn();
               }
            }

            if ($numAwards == $numEvents)
               $StatusColor = 'Green';
            else if ($numAwards == 0)
               $StatusColor = 'Red';
            else
               $StatusColor = 'Yellow';

            if ($prevTime != $EventTime)
            {
               ?>
               <tr id="header">
                  <td bgcolor="#C0C0C0" colspan=6><b><?php  print $EventTime; ?></b></td>
               </tr>
               <?php
               $prevTime = $EventTime;
            }

            if ($numEvents > 0)
            {
               if ($numAwards != $numEvents)
                  $ReportWarn = '&Warn=1';
               else
                  $ReportWarn = '';
            ?>
            <tr>
               <td width="2%" id="preserve" bgcolor="<?php  print $StatusColor; ?>">&nbsp;</td>
               <td width="38%"><?php  print $EventName; ?></td>
               <td width="10%"><?php  print "$numAwards/$numEvents"; ?></td>
               <td width="20%"><?php  print $EventType; ?></td>
               <td width="15%"><?php  print "<a href=TallyAssignAwards.php?EventID=$EventID&SchedID=$SchedID>Assign Awards</a>"; ?></td>
               <td width="15%"><?php  print "<a href=TallyPrintAwards.php?EventID=$EventID&SchedID=$SchedID$ReportWarn target=_blank>Print Report</a>"; ?></td>
            </tr>
         <?php
            }
         }
         ?>
         </table>
         <?php footer("","")?>

    <h1 align="center">Unscheduled Events</h1>
    <hr>
    <?php
         //--------------------------------------------------------------------
         // First get a list of all Unscheduled events
         //--------------------------------------------------------------------
         $results = $db->query("select    e.EventID,
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
         <table border="1"
                width="100%"
                onmouseover="javascript:trackTableHighlight(event, '#8888FF');"
                onmouseout="javascript:highlightTableRow(0);"
         >
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
              $cntResult = $db->query("SELECT  count(distinct
                                               r.ParticipantID,
                                               r.EventID)
                                        FROM   $RegistrationTable r,
                                               $EventsTable       e,
                                               $TeamMembersTable  m
                                        WHERE  r.SchedID   = '0000'
                                        AND    r.EventID   = $EventID
                                        AND    r.EventID   = e.EventID
                                        AND    e.TeamEvent = 'Y'
                                        AND    m.TeamID    = r.ParticipantID
                                       ")
                           or die ("Unable to get Registration count for event:" . sqlError());;
            }
            else
            {
              $cntResult = $db->query("SELECT  count(distinct r.ParticipantID,
                                                              r.EventID)
                                        FROM   $RegistrationTable r,
                                               $EventsTable       e
                                        WHERE  r.SchedID   = '0000'
                                        AND    r.EventID   = $EventID
                                        AND    r.EventID   = e.EventID
                                        AND    e.TeamEvent = 'N'
                                       ")
                           or die ("Unable to get Registration count for event:" . sqlError());;
            }
            $numEvents = $cntResult->fetchColumn();

            if ($numEvents == 0)
               $numAwards = 0;
            else
            {
               if ($EventType == 'Team')
               {
                 $cntResult = $db->query("SELECT  count(distinct
                                                  r.ParticipantID,
                                                  r.EventID)
                                           FROM   $RegistrationTable r,
                                                  $EventsTable       e,
                                                  $TeamMembersTable  m
                                           WHERE  r.SchedID   = '0000'
                                           AND    r.EventID   = $EventID
                                           AND    r.EventID   = e.EventID
                                           AND    e.TeamEvent = 'Y'
                                           AND    m.TeamID    = r.ParticipantID
                                           AND    r.Award Is Not Null
                                         ")
                             or die ("Unable to get Registration count for event:" . sqlError());;
               }
               else
               {
                 $cntResult = $db->query("SELECT  count(distinct r.ParticipantID,
                                                                 r.EventID)
                                           FROM   $RegistrationTable r,
                                                  $EventsTable       e
                                           WHERE  r.SchedID   = '0000'
                                           AND    r.EventID   = $EventID
                                           AND    r.EventID   = e.EventID
                                           AND    e.TeamEvent = 'N'
                                           AND    r.Award Is Not Null
                                         ")
                             or die ("Unable to get Registration count for event:" . sqlError());;
               }
               $numAwards = $cntResult->fetchColumn();
            }

            if ($numAwards == $numEvents)
               $StatusColor = 'Green';
            else if ($numAwards == 0)
               $StatusColor = 'Red';
            else
               $StatusColor = 'Yellow';

            if ($prevTime != $ConvEvent)
            {
               ?>
               <tr>
                  <td id="header" bgcolor="#C0C0C0" colspan=6><b><?php  print $ConvEvent; ?></b></td>
               </tr>
               <?php
               $prevTime = $ConvEvent;
            }
            if ($numEvents > 0)
            {
               if ($numAwards != $numEvents)
                  $ReportWarn = '&Warn=1';
               else
                  $ReportWarn = '';
            ?>
            <tr>
               <td width="2%" id="preserve" bgcolor="<?php  print $StatusColor; ?>">&nbsp;</td>
               <td width="38%"><?php  print $EventName; ?></td>
               <td width="10%"><?php  print "$numAwards/$numEvents"; ?></td>
               <td width="20%"><?php  print $EventType; ?></td>
               <td width="15%"><?php  print "<a href=TallyAssignAwards.php?EventID=$EventID&SchedID=0000>Assign Awards</a>"; ?></td>
               <td width="15%"><?php  print "<a href=TallyPrintAwards.php?EventID=$EventID&SchedID=0000$ReportWarn target=_blank>Print Report</a>"; ?></td>
            </tr>
            <?php
            }
         }
         ?>
         </table>
         <?php footer("","")?>
    </body>
</html>