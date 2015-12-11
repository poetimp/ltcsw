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
      or die ("Unable to clear $JudgeAssignmentsTable " . sqlError($db->errorInfo()));

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
            or die ("Unable to check current assignments:" . sqlError($db->errorInfo()));
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
               or die ("Unable to Add Judge to assignment: ".sqlError($db->errorInfo()));
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
               or die ("Unable to get existing room name:" . sqlError($db->errorInfo()));
               $row               = $conflict->fetch(PDO::FETCH_ASSOC);
               $AssignedRoomName  = $row['RoomName'];

            // Get the Name of the Judge
               $conflict = $db->query("select FirstName,
                                               LastName
                                       from   $JudgesTable
                                       where  JudgeID = $JudgeID
                                    ")
               or die ("Unable to get Judge Name:" . sqlError($db->errorInfo()));
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
               or die ("Unable to get conflicted info:" . sqlError($db->errorInfo()));
               $row               = $conflict->fetch(PDO::FETCH_ASSOC);
               $NewRoomName  = $row['RoomName'];
               $NewSchedTime = TimeToStr($row['StartTime']);


            // Build the status message
               $StatusMessage .= "Assignement deleted: ".
                                 $AssignedJudgeName     .
                                 " was not added to schedule at: ".
                                 $NewSchedTime .
                                 "<br> in room: ".
                                 $NewRoomName.
                                 " because $AssignedJudgeFirstName is already assigned in room: ".
                                 $AssignedRoomName.
                                 "<br><br>";
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
or die ("Unable to load current assignments:" . sqlError($db->errorInfo()));

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
or die ("Unable to obtain Times List:" . sqlError($db->errorInfo()));

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
or die ("Unable to obtain Rooms List:" . sqlError($db->errorInfo()));

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
or die ("Unable to obtain Judges List:" . sqlError($db->errorInfo()));
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
   <TABLE width="100%" border="1">
      <TR>
         <TD colspan="<?php print $dayTimes?>" align="center" bgcolor="Black">
            <FONT color="Yellow"><B><?php print $day;?></B></FONT>
         </TD>
      </TR>
      <TR>
      <?php
         print "<TD bgcolor=\"#6699FF\" width=\"15%\">&nbsp;</TD>\n";
         if (isset($allTimes))
         {
            foreach ($allTimes as $StartTime => $displayTime)
            {
               if (substr($StartTime,0,1) == $dayInitial)
               {
                  print "<TD bgcolor=\"#6699FF\" align=\"center\">\n";
                  print "<b>$displayTime</b>\n";
                  print "</TD>\n";
               }
            }
         }
      ?>
      </TR>
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
               or die ("Unable to obtain Events in room on $day:" . sqlError($db->errorInfo()));

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
                        or die ("Unable to obtain Event Name:" . sqlError($db->errorInfo()));
                        $row = $Event->fetch(PDO::FETCH_ASSOC);

                        if (!empty($row))
                        {
                           $EventName    = $row['EventName'];
                           $SchedID      = $row['SchedID'];
                           $JudgesNeeded = $row['JudgesNeeded'];
                           $EventSex     = $row['Sex'];
                           $JudgeTrained = $row['JudgeTrained'];

                           print "<TD align=center><b>$EventName</b><br>\n";
                           //----------------------------------------------------------
                           // If there are special notes for this event not it here
                           //----------------------------------------------------------
                           if ($EventSex == 'M')
                              print "<font size=-1><center><i>(Male Judges only)</i></center></font>";
                           if ($EventSex == 'F')
                              print "<font size=-1><center><i>(Female Judges only)</i></center></font>";
                           if ($JudgeTrained == 'Y')
                              print "<font size=-1><center><i>(Special Skill Needed)</i></center></font>";

                           if ($JudgesNeeded >0)
                           {
                              print "<TABLE width=100% border=0>\n";
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

                                 print "<TR>\n";
                                 print "<TD>\n";
                                 print "<center>\n";
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
         }
      ?>
   </TABLE>
   <br>
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
   <head>
      <title>Assign Judges</title>
   </head>

   <body style="background-color: rgb(217, 217, 255);">
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
      <form method="post" action=AssignJudges.php>
         <?php
            constructJudgesTable($fridayTimes,'Friday');
            constructJudgesTable($saturdayTimes,'Saturday');
         ?>
         <p align="center"><input type="submit" value="Submit" name="Submit"></p>
      </form>
      <?php footer("","")?>
   </body>

</html>