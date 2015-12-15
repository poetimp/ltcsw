<?php
include 'include/RegFunctions.php';

//========================================================================
// Collect a list of all of the distinct times that events start
//========================================================================
$TimesList = $db->query("select distinct
                                    SchedID,
                                    StartTime
                           from     $EventScheduleTable
                           order by StartTime")
or die ("Unable to obtain Times List:" . sqlError());

$times         = array();
$fridayTimes   = 1;
$saturdayTimes = 1;
while ($row = $TimesList->fetch(PDO::FETCH_ASSOC))
{
   $day    = substr($row['StartTime'],0,1);
   $hour   = substr($row['StartTime'],1,2);
   $minute = substr($row['StartTime'],3,2);

   if ($hour >= 12)
   {
      $ampm='PM';
   }
   else
   {
      $ampm='AM';
   }

   if ($hour > 12)
   {
      $hour-=12;
   }

   if ($day == '6')
      $fridayTimes++;
   else
      $saturdayTimes++;

   $allTimes[$row['StartTime']] = $hour.':'.$minute.' '.$ampm;
}

//========================================================================
// Get a list of all of the rooms that have been defined
//========================================================================
$RoomList = $db->query("select   distinct
                                    RoomID,
                                    RoomName
                           from    $RoomsTable
                           order by RoomName")
or die ("Unable to obtain Rooms List:" . sqlError());

$rooms=array();
while ($row = $RoomList->fetch(PDO::FETCH_ASSOC))
{
   $allRooms[$row['RoomID']]=$row['RoomName'];
}

//========================================================================
// Display the table showing the schedule an give operator option to
// assign judges
//========================================================================
function constructJudgesTable($dayTimes,$day)
{
   global $EventScheduleTable;
   global $EventsTable;
   global $JudgeAssignmentsTable;
   global $JudgesTable;
   global $allTimes;
   global $allRooms;
   global $db;

   $dayInitial = substr($day,0,1) == "F" ? 6 : 7;
   ?>
   <table width="100%" border="1">
      <tr>
         <td colspan="<?php print $dayTimes?>" align="center" bgcolor="Black">
            <font color="Yellow"><b><?php print $day;?></b></font>
         </td>
      </tr>
      <tr>
      <?php
         print "<TD bgcolor=\"#6699FF\" width=\"15%\">&nbsp;</TD>\n";
         foreach ($allTimes as $StartTime => $displayTime)
         {
            if (substr($StartTime,0,1) == $dayInitial)
            {
               print "<TD bgcolor=\"#6699FF\" align=\"center\">\n";
               print "<b>$displayTime</b>\n";
               print "</TD>\n";
            }
         }
      ?>
      </tr>
      <?php
         foreach ($allRooms as $RoomID => $RoomName)
         {
            $Event = $db->query("select  count(*) as Count
                                  from    $EventScheduleTable
                                  where   StartTime like '$dayInitial%'
                                  and     RoomID    =     $RoomID
                                 ")
            or die ("Unable to obtain Events in room on $day:" . sqlError());

            $row   = $Event->fetch(PDO::FETCH_ASSOC);
            $count = $row['Count'];
            if ($count > 0)
            {
               print "<TR>\n";
               print "<TD bgcolor=\"#6699FF\"><b>".preg_replace('/\s*-\s*[a-zA-Z]\s*$/','',$RoomName)."</b></TD>\n";
               foreach ($allTimes as $StartTime => $displayTime)
               {
                  if (substr($StartTime,0,1) == $dayInitial)
                  {
//                     print "<pre>select  s.EventID,
//                                                   s.SchedID,
//                                                   e.EventName,
//                                                   e.JudgesNeeded
//                                          from     $EventScheduleTable s,
//                                                   $EventsTable   e
//                                          where   s.StartTime = $StartTime
//                                          and     s.RoomID  = $RoomID
//                                          and     s.EventID = e.EventID
//                                          </pre>";
                     $Event = $db->query("select  s.EventID,
                                                   s.SchedID,
                                                   e.EventName,
                                                   e.JudgesNeeded
                                          from     $EventScheduleTable s,
                                                   $EventsTable        e
                                          where   s.StartTime = $StartTime
                                          and     s.RoomID  = $RoomID
                                          and     s.EventID = e.EventID
                                          ")
                     or die ("Unable to obtain Event Name:" . sqlError());

                     $row = $Event->fetch(PDO::FETCH_ASSOC);
                     if (!empty($row))
                     {
                        $EventName    = $row['EventName'];
                        $JudgesNeeded = $row['JudgesNeeded'];
                        $SchedID      = $row['SchedID'];
                        print "<TD align=center><b>$EventName</b><br>\n";
                        if ($JudgesNeeded >0)
                        {
                           print "<TABLE width=100% border=0>\n";
                           for ($i=0;$i<$JudgesNeeded;$i++)
                           {
                              $result = $db->query("select  a.JudgeID,
                                                             j.FirstName,
                                                             j.LastName
                                                     from    $JudgeAssignmentsTable a,
                                                             $JudgesTable           j
                                                     where   a.SchedID     = $SchedID
                                                     and     a.RoomID      = $RoomID
                                                     and     a.JudgeNumber = $i
                                                     and     a.JudgeID     = j.JudgeID
                                          ")
                                          or die ("Unable to obtain Judge Name:" . sqlError());
                              $row       = $result->fetch(PDO::FETCH_ASSOC);
                              if (!empty($row))
                              {
                                 $JudgeName = $row['LastName'].", ".$row['FirstName'];
                              }
                              else
                                 $JudgeName = '-=-';

                              print "<TR>\n";
                              print "<TD>\n";
                              print "<center>\n";
                              print $JudgeName;
                              print "</center>\n";
                              print "</TD>\n";
                              print "</TR>\n";
                           }
                           print "</TABLE>\n";
                        }
                        print "</TD>\n";
                     }
                     else
                        print "<TD>&nbsp;</TD>\n";
                  }
               }
               print "</TR>\n";
            }
         }
      ?>
   </table>
   <br />
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
   <head>
      <title>Assigned Judges</title>
   </head>

   <body style="background-color: rgb(217, 217, 255);">
      <h1 align="center">Assigned Judges</h1>
         <?php
            constructJudgesTable($fridayTimes,'Friday');
            constructJudgesTable($saturdayTimes,'Saturday');
         ?>
      <?php footer("","")?>
   </body>

</html>