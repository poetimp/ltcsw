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
$StatusMessage='';
//========================================================================
// First lets collect some data into arrays that we can use
//========================================================================

//========================================================================
// First see if we are being submitted. If so write all of the data
// that was collected to the database
//========================================================================

if (isset($_POST['Submit']))
{
   $db->query("delete from $JudgeAssignmentsTable where Churchid=$ChurchID")
      or die ("Unable to clear $JudgeAssignmentsTable " . sqlError());

   $debugStr = "";
   foreach (array_keys($_POST) as $keyValue)
   {
      if (preg_match("/^judge_/",$keyValue))
      {
         //judge_$RoomID_$SchedID_$i
         list($judgeStr,
              $RoomID,
              $SchedID,
              $JudgeNumber) = explode('_',$keyValue);
         $JudgeID = $_POST[$keyValue];

         if ($JudgeID != 0)
         {
            $check = $db->query("select  RoomID
                                  from   $JudgeAssignmentsTable
                                  where  JudgeID  = $JudgeID
                                  and    SchedID  = $SchedID
                                  and    ChurchID = $ChurchID
                                 ")
            or die ("Unable to check current assignments:" . sqlError());
            $row            = $check->fetch(PDO::FETCH_ASSOC);

            if (empty($row))
            {
               $db->query("insert into $JudgeAssignmentsTable
                               (JudgeID     ,
                                JudgeNumber ,
                                SchedID     ,
                                RoomID      ,
                                ChurchID
                               )
                            values ($JudgeID     ,
                                    $JudgeNumber ,
                                    $SchedID     ,
                                    $RoomID      ,
                                    $ChurchID
                                   )
                           ")
               or die ("Unable to Add Judge to assignment: ".sqlError());
               WriteToLog("Added Judge $JudgeID scheduled at $SchedID");
            }
            else
            {
            // Get the RoomID of the room where the person already has an assignment
               $AssignedRoomID = $row['RoomID'];

            // Get the displayable text of the room where the person already has an assignment

               $conflict = $db->query("select RoomName
                                        from   $RoomsTable
                                        where  RoomID = $AssignedRoomID
                                    ")
               or die ("Unable to get existing room name:" . sqlError());
               $row               = $conflict->fetch(PDO::FETCH_ASSOC);
               $AssignedRoomName  = $row['RoomName'];

            // Get the Name of the Judge
               $conflict = $db->query("select FirstName,
                                               LastName
                                       from   $JudgesTable
                                       where  JudgeID = $JudgeID
                                    ")
               or die ("Unable to get Judge Name:" . sqlError());
               $row                     = $conflict->fetch(PDO::FETCH_ASSOC);
               $AssignedJudgeName       = $row['FirstName']." ".$row['LastName'];
               $AssignedJudgeFirstName  = $row['FirstName'];

            // Get the displayable text for the room and scheduled for the conflicted assignment
               $conflict = $db->query("select r.RoomName,
                                               s.StartTime
                                       from   $RoomsTable         r,
                                              $EventScheduleTable s
                                       where  r.RoomID   = $RoomID
                                       and    s.SchedID  = $SchedID
                                       and    r.RoomID   = s.RoomID
                                    ")
               or die ("Unable to get conflicted info:" . sqlError());
               $row               = $conflict->fetch(PDO::FETCH_ASSOC);
               $NewRoomName  = $row['RoomName'];
               $NewSchedTime = TimeToStr($row['StartTime']);


            // Build the status message
               $StatusMessage .= "Assignement deleted: ".
                                 $AssignedJudgeName     .
                                 " was not added to schedule at: ".
                                 $NewSchedTime .
                                 "<br /> in room: ".
                                 $NewRoomName.
                                 " because $AssignedJudgeFirstName is already assigned in room: ".
                                 $AssignedRoomName.
                                 "<br /><br />";
               ;
            }
         }
      }
   }
}

//========================================================================
// Load the current judge assignments
//========================================================================
$Assignments = $db->query("select JudgeID     ,
                                   JudgeNumber ,
                                   SchedID     ,
                                   RoomID      ,
                                   ChurchID
                           from    $JudgeAssignmentsTable
                           ")
or die ("Unable to load current assignments:" . sqlError());

while ($row = $Assignments->fetch(PDO::FETCH_ASSOC))
{
   $keyValue="judge_"           .
             $row['RoomID']     ."_".
             $row['SchedID']    ."_".
             $row['JudgeNumber'];
   $assigned[$keyValue]=$row['JudgeID']."|".$row['ChurchID'];
}

//========================================================================
// Collect a list of all of the distinct times that events start
//========================================================================
$TimesList = $db->query("select    SchedID,
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
   if ($hour > 12)
   {
      $hour-=12;
      $ampm='PM';
   }
   else
   {
      $hour-=0;
      $ampm='AM';
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
$RoomList = $db->query("select distinct
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
// Get a list of all judges defined by the specific congregation
//========================================================================
$JudgeList = $db->query("select    FirstName,
                                    LastName,
                                    JudgeID
                           from     $JudgesTable
                           where    ChurchID = $ChurchID
                           order by LastName, FirstName")
or die ("Unable to obtain Judges List:" . sqlError());
$judges=array();
while ($row = $JudgeList->fetch(PDO::FETCH_ASSOC))
{
   $allJudges[$row['JudgeID']]=$row['LastName'].', '.$row['FirstName'];
}


//========================================================================
// Display the table showing the schedule an give operator option to
// assign judges
//========================================================================
function constructJudgesTable($dayTimes,$day)
{
   global $EventScheduleTable;
   global $EventsTable;
   global $allTimes;
   global $allRooms;
   global $allJudges;
   global $assigned;
   global $ChurchID;
   global $db;
   $dayInitial = substr($day,0,1) == 'F' ? 6 : 7;
   ?>
   <table class='registrationTable'>
      <tr>
         <th colspan="<?php print $dayTimes?>" style='text-align: center'>
            <h3><?php print $day;?></h3>
         </th>
      </tr>
      <tr>
      <?php
         print "<th sty;e='width: 15%'>&nbsp;</th>\n";
         if (isset($allTimes))
         {
            foreach ($allTimes as $StartTime => $displayTime)
            {
               if (substr($StartTime,0,1) == $dayInitial)
               {
                  print "<th align=\"center\">\n";
                  print "<b>$displayTime</b>\n";
                  print "</th>\n";
               }
            }
         }
      ?>
      </tr>
      <?php
         if (isset($allRooms))
         {
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
                  print "<tr>\n";
                  print "<th><h3>".preg_replace('/\s*-\s*[a-zA-Z]\s*$/','',$RoomName)."</h3></th>\n";
                  foreach ($allTimes as $StartTime => $displayTime)
                  {
                     if (substr($StartTime,0,1) == $dayInitial)
                     {
                        $Event = $db->query("select  s.EventID,
                                                      s.SchedID,
                                                      e.EventName,
                                                      e.JudgesNeeded,
                                                      e.Sex,
                                                      e.JudgeTrained
                                             from     $EventScheduleTable s,
                                                      $EventsTable   e
                                             where   s.StartTime = $StartTime
                                             and     s.RoomID    = $RoomID
                                             and     s.EventID   = e.EventID
                                             ")
                        or die ("Unable to obtain Event Name:" . sqlError());
                        $row = $Event->fetch(PDO::FETCH_ASSOC);

                        if (!empty($row))
                        {
                           $EventName    = $row['EventName'];
                           $SchedID      = $row['SchedID'];
                           $JudgesNeeded = $row['JudgesNeeded'];
                           $EventSex     = $row['Sex'];
                           $JudgeTrained = $row['JudgeTrained'];

                           print "<td style='text-align: center; vertical-align: top'><b>$EventName</b><br />\n";
                           //----------------------------------------------------------
                           // If there are special notes for this event not it here
                           //----------------------------------------------------------
                           if ($EventSex == 'M')
                              print "<font size=-1><div style='text-align: center'><i>(Male Judges only)</i></div></font>";
                           if ($EventSex == 'F')
                              print "<font size=-1><div style='text-align: center'><i>(Female Judges only)</i></div></font>";
                           if ($JudgeTrained == 'Y')
                              print "<font size=-1><div style='text-align: center'><i>(Special Skill Needed)</i></div></font>";

                           if ($JudgesNeeded >0)
                           {
                              print "<table class='registrationTable' style='width:95%'>\n";
                              for ($i=0;$i<$JudgesNeeded;$i++)
                              {
                                 $selectName = "judge_" .
                                             $RoomID  ."_".
                                             $SchedID ."_".
                                             $i;
                                 if (isset($assigned[$selectName]))
                                    list($AssignedJudgeID,$AssignedJudgeChurch) = explode('|',$assigned[$selectName]);
                                 else
                                 {
                                    $AssignedJudgeID     = '0';
                                    $AssignedJudgeChurch = $ChurchID;
                                 }

                                 print "<tr>\n";
                                 print "<td>\n";
                                 print "<div style='text-align: center'>\n";
                                 if ($AssignedJudgeChurch == $ChurchID)
                                 {

                                    if ($AssignedJudgeID == '0')
                                    {
                                       print "<select size=1 name=$selectName style=\"background-color:LightPink\">\n";
                                       print "<option value=0 selected>-- Judge Needed --</option>\n";
                                    }
                                    else
                                    {
                                       print "<select size=1 name=$selectName style=\"background-color:PaleTurquoise\">\n";
                                       print "<option value=0>-- Judge Needed --</option>\n";
                                    }

                                    foreach ($allJudges as $JudgeID => $JudgeName)
                                    {
                                       if ($AssignedJudgeID == $JudgeID)
                                          print "<option selected value=$JudgeID>$JudgeName</option>\n";
                                       else
                                          print "<option value=$JudgeID>$JudgeName</option>\n";
                                    }
                                    print "</select>\n";
                                 }
                                 else
                                 {
                                    print "-- Judge Assigned --\n";
                                 }
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
      <meta http-equiv="Content-Language" content="en-us" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="stylesheet" href="include/registration.css" type="text/css" />

      <title>Assign Judges</title>
   </head>

   <body>
      <h1 align="center">Assign Judges</h1>
      <?php
         if ($StatusMessage != '')
         {
            ?>
            <p align="center">
               <font color="Red">
                  <b>
                     <?php print $StatusMessage ?>
                  </b>
               </font>
            </p>
            <?php
         }
      ?>
      <form method="post" action="AssignJudges.php">
         <?php
            constructJudgesTable($fridayTimes,'Friday');
            constructJudgesTable($saturdayTimes,'Saturday');
         ?>
         <p align="center"><input type="submit" value="Submit" name="Submit" /></p>
      </form>
      <?php footer("","")?>
   </body>

</html>