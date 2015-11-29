<?php
include 'include/RegFunctions.php';
if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
// JudgeID, JudgingCatagory, substr(StartTime,1,1) Day, substr(StartTime,2) StartTime, StopTime

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <meta http-equiv="Content-Language" content="en-us">
       <title>
          Judges Preferences Data
       </title>
    </head>

    <body>
    <?php
         print "\"JudgeID\",";
         print "\"JudgingCatagory\",";
         print "\"Day\",";
         print "\"StartTime\",";
         print "\"StopTime\"";
         print "<br>\n";
         $results = $db->query("select   e.JudgeID,
                                          e.JudgingCatagory,
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
                                          maketime(substring(s.StartTime,2,2),substring(s.StartTime,4,2),'00') EventStartTime,
                                          maketime(substring(s.EndTime,2,2)  ,substring(s.EndTime,4,2)  ,'00') EventStopTime
                                 from     $JudgeEventsTable   e,
                                          $JudgeTimesTable    t,
                                          $EventScheduleTable s
                                 where    t.JudgeID = e.JudgeID
                                 and      s.SchedID = t.SchedID
                                 order by JudgeID")
                   or die ("Unable to get Judge Preference list:" . sqlError($db->errorInfo()));


         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            $JudgeID   = $row['JudgeID'];
            $Catagory  = $row['JudgingCatagory'];
            $Day       = $row['Day'];
            $StartTime = $row['EventStartTime'];
            $StopTime  = $row['EventStopTime'];


            print "\"$JudgeID\",";
            print "\"$Catagory\",";
            print "\"$Day\",";
            print "\"$StartTime\",";
            print "\"$StopTime\"";
            print "<br>\n";
         }
         ?>
         </table>
    </body>
</html>