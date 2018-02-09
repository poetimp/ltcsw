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

$forEvent = (isset($_REQUEST['ID']) and !preg_match("/[^0-9]/",$_REQUEST['ID']))
      ? "EventID = ".$_REQUEST['ID']
      : 'EventID > 0';

//========================================================================
// Collect a list of all of the distinct times that events start
//========================================================================
$TimesList = $db->query("select distinct
                                    SchedID,
                                    StartTime
                           from     $EventScheduleTable
                           where    $forEvent
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
   global $forEvent;

   $dayInitial = substr($day,0,1) == "F" ? 6 : 7;
   ?>
   <table class='registrationTable' border="1">
      <tr>
         <th colspan="<?php print $dayTimes?>" style='text-align: center'>
            <h3><?php print $day;?></h3>
         </th>
      </tr>
      <tr>
      <?php
         print "<th sty;e='width: 15%'>&nbsp;</th>\n";
         foreach ($allTimes as $StartTime => $displayTime)
         {
            if (substr($StartTime,0,1) == $dayInitial)
            {
               print "<th style='text-align: center'>\n";
               print "<b>$displayTime</b>\n";
               print "</th>\n";
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
                                  and     $forEvent
                                 ")
            or die ("Unable to obtain Events in room on $day:" . sqlError());

            $row   = $Event->fetch(PDO::FETCH_ASSOC);
            $count = $row['Count'];
            if ($count > 0)
            {
               print "<tr>\n";
               print "<th ><b>".preg_replace('/\s*-\s*[a-zA-Z]\s*$/','',$RoomName)."</b></th>\n";
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
                        print "<td style='text-align: center; vertical-align: top'><b>$EventName</b><br />\n";
                        if ($JudgesNeeded >0)
                        {
                           print "<table class='registrationTable'\n";
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

                              print "<tr>\n";
                              print "<td>\n";
                              print "<div style='text-align: center'>\n";
                              print $JudgeName;
                              print "</div>\n";
                              print "</td>\n";
                              print "</tr>\n";
                           }
                           print "</table>\n";
                        }
                        print "</td>\n";
                     }
                     else
                        print "<td>&nbsp;</td>\n";
                  }
               }
               print "</tr>\n";
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
      <meta http-equiv="Content-Language" content="en-us" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="stylesheet" href="include/registration.css" type="text/css" />
   </head>

   <body>
      <?php
      if (isset($_REQUEST['ID']) and !preg_match("/[^0-9]/",$_REQUEST['ID']))
      {
         $result = $db->query("select  EventName
                                from    $EventsTable
                                where   EventID     = ".$_REQUEST['ID'])
                     or die ("Unable to obtain Event Name:" . sqlError());
         $row       = $result->fetch(PDO::FETCH_ASSOC);
         if (!empty($row))
         {
            print "<h1 align='center'>Assigned Judges</h1>";
            print "<h2 align='center'>for event ".$row['EventName']."</h2>";
            constructJudgesTable($fridayTimes,'Friday');
            constructJudgesTable($saturdayTimes,'Saturday');
         }
      }
      elseif (isset($_REQUEST['ID']) and preg_match("/^byEvent/i",$_REQUEST['ID']))
      {
         $result = $db->query("select   EventID,
                                        EventName,
                                        JudgingCatagory
                                from    $EventsTable
                                order by JudgingCatagory
                              ")
                     or die ("Unable to obtain Event List:" . sqlError());
         $first=1;
         $judgingCatagory='';
         while($row = $result->fetch(PDO::FETCH_ASSOC))
         {
            if ($first)
            {
               print "<h1 align='center'>Assigned Judges</h1>";
               $first=0;
            }
            elseif ($row['JudgingCatagory'] != $judgingCatagory)
            {
               print "<h1 align='center' style='page-break-before: always;'>Assigned Judges</h1>";
            }
            $judgingCatagory=$row['JudgingCatagory'];

            print "<h2 align='center'>for event ".$row['EventName']."</h2>";

            $forEvent = "EventID = ".$row['EventID'];
            constructJudgesTable($fridayTimes,'Friday');
            constructJudgesTable($saturdayTimes,'Saturday');
         }
      }
      else
      {
         print "<h1 align='center'>Assigned Judges</h1>";
         constructJudgesTable($fridayTimes,'Friday');
         constructJudgesTable($saturdayTimes,'Saturday');
      }
      ?>
      <?php footer("","")?>
   </body>

</html>