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
// JudgeID, JudgingCatagory, substr(StartTime,1,1) Day, substr(StartTime,2) StartTime, StopTime

}
   $filename = "JudgesPreferences-".date("m-d-Y").".csv";

   header("Content-disposition: attachment; filename=$filename");
   header("Content-type: application/octet-stream");

   $fp = fopen('php://output', 'w');
   $results = $db->query("select  e.JudgeID,
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
             or die ("Unable to get Judge Preference list:" . sqlError());

   $rowCount=0;
   while ($row = $results->fetch(PDO::FETCH_ASSOC))
   {
      if ($rowCount++ == 0)
      {
         foreach ($row as $key => $value)
         {
            $heading[] = $key;
         }
         fputcsv($fp, $heading);
      }
      fputcsv($fp, $row);
   }
   fclose($fp);
?>
