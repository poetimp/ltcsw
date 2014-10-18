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
   mysql_query("delete from $JudgeAssignmentsTable where Churchid=$ChurchID")
      or die ("Unable to clear $JudgeAssignmentsTable " . mysql_error());

   $debugStr = "";
   foreach (array_keys($_POST) as $keyValue)
   {
      if (ereg("^judge_",$keyValue))
      {
         //judge_$RoomID_$SchedID_$i
         list($judgeStr,
              $RoomID,
              $SchedID,
              $JudgeNumber) = explode('_',$keyValue);
         $JudgeID = $_POST[$keyValue];

         if ($JudgeID != 0)
         {
            $check = mysql_query("select RoomID
                                  from   $JudgeAssignmentsTable
                                  where  JudgeID  = $JudgeID
                                  and    SchedID  = $SchedID
                                  and    ChurchID = $ChurchID
                                 ")
            or die ("Unable to check current assignments:" . mysql_error());

            if (mysql_num_rows($check) == 0)
            {
               mysql_query("insert into $JudgeAssignmentsTable
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
               or die ("Unable to Add Judge to assignment: ".mysql_error());
            }
            else
            {
            // Get the RoomID of the room where the person already has an assignment
               $row            = mysql_fetch_assoc($check);
               $AssignedRoomID = $row['RoomID'];

            // Get the displayable text of the room where the person already has an assignment

               $conflict = mysql_query("select RoomName
                                        from   $RoomsTable
                                        where  RoomID = $AssignedRoomID
                                    ")
               or die ("Unable to get existing room name:" . mysql_error());
               $row               = mysql_fetch_assoc($conflict);
               $AssignedRoomName  = $row['RoomName'];

            // Get the Name of the Judge
               $conflict = mysql_query("select FirstName,
                                               LastName
                                       from   $JudgesTable
                                       where  JudgeID = $JudgeID
                                    ")
               or die ("Unable to get Judge Name:" . mysql_error());
               $row                     = mysql_fetch_assoc($conflict);
               $AssignedJudgeName       = $row['FirstName']." ".$row['LastName'];
               $AssignedJudgeFirstName  = $row['FirstName'];

            // Get the displayable text for the room and scheduled for the conflicted assignment
               $conflict = mysql_query("select r.RoomName,
                                               s.StartTime
                                       from   $RoomsTable         r,
                                              $EventScheduleTable s
                                       where  r.RoomID   = $RoomID
                                       and    s.SchedID  = $SchedID
                                       and    r.RoomID   = s.RoomID
                                    ")
               or die ("Unable to get conflicted info:" . mysql_error());
               $row               = mysql_fetch_assoc($conflict);
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
$Assignments = mysql_query("select JudgeID     ,
                                   JudgeNumber ,
                                   SchedID     ,
                                   RoomID      ,
                                   ChurchID
                           from    $JudgeAssignmentsTable
                           ")
or die ("Unable to load current assignments:" . mysql_error());

while ($row = mysql_fetch_assoc($Assignments))
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
$TimesList = mysql_query("select    SchedID,
                                    StartTime
                           from     $EventScheduleTable
                           order by StartTime")
or die ("Unable to obtain Times List:" . mysql_error());

$times         = array();
$fridayTimes   = 1;
$saturdayTimes = 1;
while ($row = mysql_fetch_assoc($TimesList))
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
$RoomList = mysql_query("select distinct
                                    RoomID,
                                    RoomName
                           from    $RoomsTable
                           order by RoomName")
or die ("Unable to obtain Rooms List:" . mysql_error());

$rooms=array();
while ($row = mysql_fetch_assoc($RoomList))
{
   $allRooms[$row['RoomID']]=$row['RoomName'];
}

//========================================================================
// Get a list of all judges defined by the specific congregation
//========================================================================
$JudgeList = mysql_query("select    FirstName,
                                    LastName,
                                    JudgeID
                           from     $JudgesTable
                           where    ChurchID = $ChurchID
                           order by LastName, FirstName")
or die ("Unable to obtain Judges List:" . mysql_error());
$judges=array();
while ($row = mysql_fetch_assoc($JudgeList))
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
               $Event = mysql_query("select  count(*) as Count
                                    from    $EventScheduleTable
                                    where   StartTime like '$dayInitial%'
                                    and     RoomID    =     $RoomID
                                    ")
               or die ("Unable to obtain Events in room on $day:" . mysql_error());

               $row   = mysql_fetch_assoc($Event);
               $count = $row['Count'];
               if ($count > 0)
               {
                  print "<TR>\n";
                  print "<TD bgcolor=\"#6699FF\"><b>".preg_replace('/\s*-\s*[a-zA-Z]\s*$/','',$RoomName)."</b></TD>\n";
                  foreach ($allTimes as $StartTime => $displayTime)
                  {
                     if (substr($StartTime,0,1) == $dayInitial)
                     {
                        $Event = mysql_query("select  s.EventID,
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
                        or die ("Unable to obtain Event Name:" . mysql_error());

                        if (mysql_num_rows($Event) > 0)
                        {
                           $row = mysql_fetch_assoc($Event);
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