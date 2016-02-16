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

$IgnoreEvents = "117,118,119"; // Hack to remove bible Bowl
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <meta http-equiv="Content-Language" content="en-us">
       <title>
          Download Scheduled Events Roster
       </title>
    </head>

    <body>
    <?php
         print "\"EventID\",";
         print "\"EventName\",";
         print "\"TeamID\",";
         print "\"ParticipantID\",";
         print "\"FirstName\",";
         print "\"LastName\",";
//         print "\"EventTime\",";
         print "\"Day\",";
         print "\"StartTime\"";
         print "<br>\n";
//         print "<pre>
//                                 select   e.EventID,
//                                          e.EventName,
//                                          e.ConvEvent,
//                                          e.TeamEvent,
//                                          s.StartTime,
//                                          s.SchedID,
//                                          Case
//                                             when e.EventAttended='N'            then '0000'
//                                             else                                s.SchedID
//                                          end
//                                          SchedID,
//                                          Case
//                                             when substring(s.StartTime,1,1) = '1' then 'Sunday'
//                                             when substring(s.StartTime,1,1) = '2' then 'Monday'
//                                             when substring(s.StartTime,1,1) = '3' then 'Tuesday'
//                                             when substring(s.StartTime,1,1) = '4' then 'Wednesday'
//                                             when substring(s.StartTime,1,1) = '5' then 'Thursday'
//                                             when substring(s.StartTime,1,1) = '6' then 'Friday'
//                                             when substring(s.StartTime,1,1) = '7' then 'Saturday'
//                                             else                                'Unknown'
//                                          End
//                                          Day
//                                 from     $EventsTable        e,
//                                          $EventScheduleTable s
//                                 where    e.EventID = s.EventID
//                                 and      e.EventID not in($IgnoreEvents)
//                                 order by e.EventName,
//                                          s.StartTime
//                                 </pre>";
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
                                          Case
                                             when substring(s.StartTime,1,1) = '1' then 'Sunday'
                                             when substring(s.StartTime,1,1) = '2' then 'Monday'
                                             when substring(s.StartTime,1,1) = '3' then 'Tuesday'
                                             when substring(s.StartTime,1,1) = '4' then 'Wednesday'
                                             when substring(s.StartTime,1,1) = '5' then 'Thursday'
                                             when substring(s.StartTime,1,1) = '6' then 'Friday'
                                             when substring(s.StartTime,1,1) = '7' then 'Saturday'
                                             else                                'Unknown'
                                          End
                                          Day,
                                          maketime(substring(s.StartTime,2,2),substring(s.StartTime,4,2),'00') EventTime
                                 from     $EventsTable        e,
                                          $EventScheduleTable s
                                 where    e.EventID = s.EventID
                                 and      e.EventID not in($IgnoreEvents)
                                 order by e.EventName,
                                          s.StartTime")
                   or die ("Unable to get scheduled event list:" . sqlError());


         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            $EventID   = $row['EventID'];
            $EventName = $row['EventName'];
            $ConvEvent = $row['ConvEvent'] == "C" ? "Convention" : "Pre-Convention";
            $TeamEvent = $row['TeamEvent'] == "Y" ? "Team"       : "Individual";
            $EventTime = TimeToStr($row['StartTime']);
            $SchedID   = $row['SchedID'];
            $Day       = $row['Day'];
            $StartTime = $row['StartTime'];
            $EventTime = $row['EventTime'];

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


            $cntResult = $db->query($sql)
                         or die ("Unable to get Registration count for event:" . sqlError());
            $cntRow    = $cntResult->fetch(PDO::FETCH_ASSOC);
            $numEvents = $cntRow['count'];

            if ($numEvents > 0)
            {

               if ($TeamEvent == 'Team')
               {
                  $sql = "SELECT distinct
                                 p.ParticipantID,
                                 p.FirstName,
                                 p.LastName,
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
                                 p.ParticipantID,
                                 p.FirstName,
                                 p.LastName,
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

               $members = $db->query($sql) or die ("Not found:" . sqlError());

               while ($row = $members->fetch(PDO::FETCH_ASSOC))
               {
                  $FirstName      = $row['FirstName'];
                  $LastName       = $row['LastName'];
                  $ParticipantID  = $row['ParticipantID'];
                  $TeamID         = isset($row['TeamID']) ?  $row['TeamID'] : "";

                 print "\"$EventID\",";
                 print "\"$EventName\",";
                 print "\"$TeamID\",";
                 print "\"$ParticipantID\",";
                 print "\"$FirstName\",";
                 print "\"$LastName\",";
//                 print "\"$EventTime\",";
                 print "\"$Day\",";
                 print "\"$EventTime\"";
                 print "<br>\n";
               }
            }
         }
         ?>
         </table>

    </body>

</html>