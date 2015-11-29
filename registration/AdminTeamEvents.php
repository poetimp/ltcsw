<?php
include 'include/RegFunctions.php';

if ($Admin == 'Y' and isset($_REQUEST['ChurchID']))
   $ChurchID    = $_REQUEST['ChurchID'];

$EventID     = $_REQUEST['EventID'];
$TeamComment = isset($_POST['TeamComment']) ? $_POST['TeamComment'] : "";

//   print "<pre>
//                  select EventName,
//                                   EventID,
//                                   MinGrade,
//                                   MaxGrade,
//                                   (MaxWebSlots * MaxRooms) MaxWebSlots,
//                                   TeenCoord
//                              from $EventsTable
//                             where EventID = $EventID
//                           </pre>";
//=======================================================================
// Get the event details
//=======================================================================
// print "<pre>";print_r($_POST);"</pre>";
// print "<pre>";print_r($_REQUEST);"</pre>";

$EventInfo   = $db->query("select EventName,
                                   EventID,
                                   MinGrade,
                                   MaxGrade,
                                   MaxSize,
                                   (MaxWebSlots * MaxRooms) MaxWebSlots,
                                   TeenCoord
                              from $EventsTable
                             where EventID = $EventID")
               or die ("Unable to get event info:" . sqlError($db->errorInfo()));
$Row         = $EventInfo->fetch(PDO::FETCH_ASSOC);

$EventName   = $Row['EventName'];
$MinGrade    = $Row['MinGrade'];
$MaxGrade    = $Row['MaxGrade'];
$MaxSize     = $Row['MaxSize'];
$MaxWebSlots = $Row['MaxWebSlots'];
$TeenCoord   = $Row['TeenCoord'];

if (isset($_REQUEST['Action']))
{
   $Action = $_REQUEST['Action'];
}
else
{
   $Action = "";
}


//=======================================================================
// They have selected all of their team members and have pressed "Add"
//=======================================================================
if (isset($_REQUEST['Add']))
{
   //=======================================================================
   // We do different things whether the even is attended (i.e. drama) or
   // not (i.e. art)
   //=======================================================================
   $EventResult = $db->query("select EventAttended
                               from   $EventsTable
                               where  EventID = $EventID
                              ")
                 or die ("Unable to see if event is attended:" . sqlError($db->errorInfo()));
   $row         = $EventResult->fetch(PDO::FETCH_ASSOC);

   //=======================================================================
   // If it not attended simply add the team. No check are needed for
   // conflicts
   //=======================================================================
   if ($row['EventAttended'] != 'Y')
   {
      $db->query("insert into $TeamsTable
                        (ChurchID, EventID, Comment)
                         values($ChurchID,$EventID,'$TeamComment')")
      or die ("Unable to create team: " . sqlError($db->errorInfo()));
      $TeamID = $db->lastInsertId();
   }
   else
   {
   //=======================================================================
   // Event is attended so we have to do a tone of conflict checking
   //=======================================================================
//      print "<pre>";print_r($_POST);"</pre>";
//      print "<pre>";print_r($_REQUEST);"</pre>";
//      print "<pre>";print_r($_SESSION);"</pre>";
//      print "<pre>
//                              select   s.SchedID,
//                                       count(*) RegCount
//                              from     $EventScheduleTable s,
//                                       $RegistrationTable  r,
//                                       $EventsTable        e
//                              where    s.EventID = $EventID
//                              and      r.EventID = s.EventID
//                              and      e.EventID = s.EventID
//                              and      s.SchedID = r.SchedID
//                              group by s.SchedID
//             </pre>";
   //=======================================================================
   // First loop through all of the possible time slots and see if any of
   // have any openings. If they do we create a team with no members and
   // unscheduled. Otherwise we say we can't because there are no time splts
   // available
   //=======================================================================
      $RegInfo   = $db->query("select distinct
                                       s.StartTime,
                                       IF (RoomName REGEXP '-[a-z]$',
                                           SUBSTR(RoomName,1,LENGTH(RoomName)-2),
                                           RoomName)
                                       as RoomName
                                from   $EventScheduleTable  s,
                                       $EventsTable         e,
                                       $RoomsTable          r
                                where  s.EventID = $EventID
                                and    e.EventID = s.EventID
                                and    s.RoomID  = r.RoomID
                             ")
      or die ("Unable to get start times:" . sqlError($db->errorInfo()));

      $allFull=1; // Guilty until proven innocent
      while (($rowCount = 0 or $allFull == 1) or ($allFull and $Row = $RegInfo)->fetch(PDO::FETCH_ASSOC))
      {
         $StartTime    = $SchedRow['StartTime'];
         $RoomName     = $SchedRow['RoomName'];
         $RegCount     = slotsFilledInRoom($RoomName,$StartTime);

         if ($RegCount < $MaxWebSlots)
         {
            $db->query("insert into $TeamsTable
                                (ChurchID, EventID, Comment)
                         values($ChurchID,$EventID,'$TeamComment')")
            or die ("Unable to create team: " . sqlError($db->errorInfo()));
            $TeamID = $db->lastInsertId();
            $allFull=0; // Cleared of all charged
         }
      }

      if ($allFull)
      {
         $message = "Sorry, there are no time slots available for this event.";
         $TeamID = -1;
      }
   }
}
else
{
   $TeamID      = $_REQUEST['TeamID'];
   if (!isset($_POST['Apply']))
   {
      $TeamInfo    = $db->query("select Comment
                                  from   $TeamsTable
                                  where  TeamID = $TeamID")
                    or die ("Unable to obtain team comments:" . sqlError($db->errorInfo()));
      $Row         = $TeamInfo->fetch(PDO::FETCH_ASSOC);
      $TeamComment = $Row['Comment'];
   }
}
//=======================================================================
// They have selected members and time. So we need to check conflicts and
// possibly schedule the event.
//=======================================================================
if (isset($_POST['Apply']))
{
   $EventResult = $db->query("select EventAttended
                               from   $EventsTable
                               where  EventID = $EventID
                               ")
                  or die ("Unable to see if event is attended:" . sqlError($db->errorInfo()));
   $row           = $EventResult->fetch(PDO::FETCH_ASSOC);
   $EventAttended = $row['EventAttended'];
   $SchedID = isset($_POST['SchedID']) ? $_POST['SchedID'] : "0000";
   //print "EventAttended: $EventAttended, SchedID: $SchedID<br>";
   if ($EventAttended == 'Y' and $SchedID == 0)
   {
      $message = "Not Applied: You must select a time";
   }
   else if ($SchedID == 'Full')
   {
      $message = "Sorry, there are no more time slots available for this event at this time";
   }
   else
   {
      //=======================================================================
      // Before we loose it in the deletes below, capture any awards that may
      // have been assigned to any of these people or the team
      //=======================================================================
      $results = $db->query("select ParticipantID,
                                     Award
                              from   $TeamMembersTable
                              where  TeamID = $TeamID
                             ")
                 or die ("Unable to retireve current member List:" . sqlError($db->errorInfo()));

      while ($row = $results->fetch(PDO::FETCH_ASSOC))
      {
         $ParticipantID           = $row['ParticipantID'];
         $Award[$ParticipantID]   = isset($row['Award']) ? "'".$row['Award']."'" : "null";
      }

      $results = $db->query("select Award
                              from   $RegistrationTable
                              where  ParticipantID = $TeamID
                             ")
                 or die ("Unable to retireve current team award:" . sqlError($db->errorInfo()));

      $row = $results->fetch(PDO::FETCH_ASSOC);
      $Award['Team'] = isset($row['Award']) ? "'".$row['Award']."'" : "null";

      //=======================================================================
      // Remove all members from the team
      //=======================================================================
      $db->query("delete from $TeamMembersTable
                   where  TeamID=$TeamID")
      or die ("Unable to delete Team Members: ". sqlError($db->errorInfo()));

      //=======================================================================
      // Remove the team from registration so that we start with a clean slate
      //=======================================================================
      $db->query("delete from  $RegistrationTable
                          where ParticipantID=$TeamID
                          and   EventID=$EventID
                          and   SchedID=$SchedID")
      or die ("Unable to delete Team Registration: " . sqlError($db->errorInfo()));

      //=======================================================================
      // Update a possibly changed Team Comment
      //=======================================================================
      $db->query("update $TeamsTable
                   set    Comment='$TeamComment'
                   where  TeamID=$TeamID")
      or die ("Unable to update team comment: " .sqlError($db->errorInfo()));

      //=======================================================================
      // Re insert the registration of the team in the registration table
      //=======================================================================
      $db->query("replace into $RegistrationTable
                                 ( ChurchID , ParticipantID ,  EventID , SchedID,  Award)
                          values ($ChurchID , $TeamID       , $EventID ,$SchedID,".$Award['Team'].")")
                  or die ("Unable to add registration information: ".sqlError($db->errorInfo()));

      //=======================================================================
      // Get list of possible participants for this team event
      //=======================================================================
      if ($TeenCoord == 'Y')
      {
         $select = "select   ParticipantID,
                             FirstName,
                             LastName,
                             Grade
                    from     $ParticipantsTable
                    where    ChurchID = $ChurchID
                    order by LastName,
                             FirstName";
      }
      else
      {
         $select = "select   ParticipantID,
                             FirstName,
                             LastName,
                             Grade
                    from     $ParticipantsTable
                    where    ChurchID = $ChurchID
                    and      Grade   <= $MaxGrade
                    order by LastName,
                             FirstName";
      }
      $results = $db->query($select) or die ("Unable to retrieve Participant List:" . sqlError($db->errorInfo()));

      $teamSize=0;
      while ($row = $results->fetch(PDO::FETCH_ASSOC))
      {
         $ParticipantID   = $row['ParticipantID'];
         $grade           = $row['Grade'];
         $controlID       = 's'.$ParticipantID;

         if (isset($_POST[$controlID]) and $_POST[$controlID] == 'ON')
         {
            if ($grade > $MaxGrade and $TeenCoord=='Y')
            {
               $conflict = FALSE;
            }
            else
            {
               //-----------------------------------------------------------------------
               // Make sure this participant does not have a conflict with a solo event
               //-----------------------------------------------------------------------
               if ($SchedID != '0000')
               {
                  if (($conflictingEvent = ScheduleGetEventName($SchedID,$ParticipantID)) != "")
                  {
                     $message = "One or more Team Members de-selected";
                     $conflicted[$ParticipantID] = "<font color=red><b>&nbsp;&nbsp;".
                                                   "<= De-selected due to schedule conflict with: ".
                                                   $conflictingEvent.
                                                   "</b></font>";
                  }
               }
            }

            if (! isset($conflicted[$ParticipantID]))
            {
               $teamSize++;
               if ($teamSize <= $MaxSize)
               {
                  //print "<br>Award: "; print_r($Award); print "<br>";
                  $AwardStr = isset($Award[$ParticipantID]) ? $Award[$ParticipantID] : "null";
                  //print "<br>ParticipantID: $ParticipantID, AwardStr: $AwardStr<br><hr>";
                  $db->query("insert into $TeamMembersTable
                                      ( TeamID , ParticipantID,  ChurchID,  Award)
                               values ($TeamID ,$ParticipantID, $ChurchID, $AwardStr)");
               }
               else
               {
                  $message = "One or more Team Members de-selected";
                  $conflicted[$ParticipantID] = "<font color=red><b>&nbsp;&nbsp;".
                                                "<= De-selected. Max team size: ".
                                                $MaxSize.
                                                "</b></font>";
               }
            }
         }
      }
   }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <title>
          Manage Team Events
       </title>
       <h1 align=center>Manage Team Members For</h1>
       <h2 align=center><?php  print $EventName; ?><br>
           <?php  if ($TeamID > 0)
              {
                 print "Team # $TeamID";
              }
           ?>
       </h2>
       <?php
       if (isset($_POST['Apply']))
       {
          if (!isset($message) or $message == "")
          {
             ?>
             <h3><font color="#FF0000">Applied</font></h3>
             <?php
          }
       }

       if (isset($message) and $message != "")
       {
          print "<h3><font color=\"#FF0000\">$message</font></h3>\n";
       }
       ?>
    </head>
    <body style="background-color: rgb(217, 217, 255);">
    <?php
    if ($TeamID > 0)
    {
    ?>
        <form method="post" action="AdminTeamEvents.php?EventID=<?php  print $EventID; ?>&TeamID=<?php  print $TeamID; ?><?php  if ($Admin == 'Y' and isset($_REQUEST['ChurchID'])) print "&ChurchID=$ChurchID"; if (isset($_REQUEST['Return'])) print '&Return='.$_REQUEST['Return']?>">
          <table border="1" width="100%">
          <?php
         $EventResult = $db->query("select EventAttended
                                     from   $EventsTable
                                     where  EventID = $EventID
                                     ")
                        or die ("Unable to see if event is attended:" . sqlError($db->errorInfo()));
         $row           = $EventResult->fetch(PDO::FETCH_ASSOC);
         $EventAttended = $row['EventAttended'];

         if ($EventAttended == 'Y')
         {
            $cntResult = $db->query("select count(*) as count
                                      from   $EventScheduleTable
                                      where  EventID = $EventID
                                      ")
                         or die ("Not found:" . sqlError($db->errorInfo()));
            $cntRow    = $cntResult->fetch(PDO::FETCH_ASSOC);
            $scheduled = ($count = $cntRow['count']);
         }
         else
         {
            $scheduled = 0;
         }

         if ($scheduled)
         {
            ?>
            <tr>
               <td width="10%" bgcolor="#000000">
               <?php
               if ($Action == "View")
               {
                  $result = $db->query("select s.StartTime
                                         from   $RegistrationTable r,
                                                $EventScheduleTable     s
                                         where  r.ChurchID      = $ChurchID
                                         and    r.EventID       = $EventID
                                         and    r.ParticipantID = $TeamID
                                         and    s.SchedID       = r.SchedID
                                         and    s.EventID       = r.EventID")
                           or die("Unable to determing event time: ".sqlError($db->errorInfo()));
                  $row    = $result->fetch(PDO::FETCH_ASSOC);
                  print "<font color=#FFFF00>".TimeToStr($row['StartTime'])."</font>";
               }
               else
               {
               ?>
                  <select size="1" <?php  print "name=\"SchedID\"";?>>
                  <?php
                     $select = "select count(*) as count
                                from   $RegistrationTable
                                where  ChurchID      = $ChurchID
                                and    EventID       = $EventID
                                and    ParticipantID = $TeamID";
                     $cntResult = $db->query($select) or die ("Unable to determine if event is selected:" . sqlError($db->errorInfo()));
                     $cntRow    = $cntResult->fetch(PDO::FETCH_ASSOC);
                     $selected  = $cntRow['count'];

                     $sel = $selected == 0 ? "selected" : "";
                  ?>
                     <option value = "0" <?php  print $sel; ?>>Not Selected</option>
                  <?php
                  if ($selected > 0)
                  {
                     $select = "select distinct SchedID
                                from   $RegistrationTable
                                where  ChurchID      = $ChurchID
                                and    EventID       = $EventID
                                and    ParticipantID = $TeamID";
                     $cntResult = $db->query($select) or die ("Not found:" . sqlError($db->errorInfo()));
                     $cntRow    = $cntResult->fetch(PDO::FETCH_ASSOC);
                     $SchedID   = $cntRow['SchedID'];

                  }
                  $SchedResult = $db->query("select distinct
                                                     s.SchedID,
                                                     s.StartTime,
                                                     (e.MaxWebSlots * e.MaxRooms) MaxWebSlots,
                                                     IF (RoomName REGEXP '-[a-z]$',
                                                         SUBSTR(RoomName,1,LENGTH(RoomName)-2),
                                                         RoomName)
                                                     as RoomName
                                              from   $EventScheduleTable  s,
                                                     $EventsTable         e,
                                                     $RoomsTable          r
                                              where  s.EventID = $EventID
                                              and    e.EventID = s.EventID
                                              and    s.RoomID  = r.RoomID
                                             ")
                                  or die ("Unable to Get scheduled slots for each scheduled event:" . sqlError($db->errorInfo()));

                  $freeSlots = 0;
                  while ($SchedRow = $SchedResult->fetch(PDO::FETCH_ASSOC))
                  {
                     $freeSlots    = (($SchedRow['MaxWebSlots'] - slotsFilledInRoom($SchedRow['RoomName'],$SchedRow['StartTime']) > 0) or $freeSlots);
                  }

                  if (!$freeSlots)
                  {
                     print "<option value=\"Full\" selected>No time slots available</option>\n";
                  }
                  else
                  {

                     $SchedResult = $db->query("select distinct
                                                        s.SchedID,
                                                        s.StartTime,
                                                        (e.MaxWebSlots * e.MaxRooms) MaxWebSlots,
                                                        IF (RoomName REGEXP '-[a-z]$',
                                                            SUBSTR(RoomName,1,LENGTH(RoomName)-2),
                                                            RoomName)
                                                        as RoomName
                                                 from   $EventScheduleTable  s,
                                                        $EventsTable         e,
                                                        $RoomsTable          r
                                                 where  s.EventID = $EventID
                                                 and    e.EventID = s.EventID
                                                 and    s.RoomID  = r.RoomID
                                                 order by s.StartTime
                                                ")
                                     or die ("Unable to Get scheduled slots for each scheduled event:" . sqlError($db->errorInfo()));

                     $freeSlots = 0;
                     while ($SchedRow = $SchedResult->fetch(PDO::FETCH_ASSOC))
                     {
                        if (($SchedRow['MaxWebSlots'] - slotsFilledInRoom($SchedRow['RoomName'],$SchedRow['StartTime'])) > 0 or ($SchedID == $SchedRow['SchedID']))
                        {
                           $sel =  ($selected > 0 and $SchedID == $SchedRow['SchedID']) ? "selected" : "";
                           print "<option value=\"".$SchedRow['SchedID']."\" $sel>".TimeToStr($SchedRow['StartTime'])."</option>\n";
//                           print "<option value=\"".$SchedRow['SchedID']."\" $sel>".TimeToStr($SchedRow['StartTime'])." Slots:".$SchedRow['MaxWebSlots']." SchedID: ".$SchedRow['SchedID']." EventID: $EventID</option>\n";
                        }
                     }
                  }
               ?>
                  </select>
               <?php
               }
               ?>
               </td>
               <td colspan=2 bgcolor="#000000">
                  <font color="#FFFF00">
                     <?php
                     if ($Action == "View")
                     {
                        print "&lt;--- Time this event meets.";
                     }
                     else
                     {
                     ?>
                        &lt;--- Please select the time this event meets.
                     <?php
                     }
                     ?>
                  </font>
               </td>
            </tr>
            <?php
         }
         ?>
         <tr>
            <td bgcolor = "#000000" colspan=3><center><font color="#FFFF00">
               Comments</font></center></td>
         </tr>
         <tr>
            <td colspan=3>
               <?php
               if ($Action == "View")
               {
                  print str_replace("\n","<br>",$TeamComment)."&nbsp;";
               }
               else
               {
               ?>
                  <p align="center"><textarea rows="4" name="TeamComment" cols="71"><?php print $TeamComment;?></textarea></td>
               <?php
               }
               ?>
         </tr>
         <tr>
            <?php
            if ($Action == "View")
            {
               print "<td bgcolor=#000000 colspan=2><font color=#FFFF00><center>Participant Name</center></font></td>";
            }
            else
            {
            ?>
               <td width="10%" bgcolor="#000000"><font color="#FFFF00">Selected</font></td>
               <td width="10%" bgcolor="#000000"><font color="#FFFF00">Grade</font></td>
               <td width=80% bgcolor="#000000"><font color="#FFFF00">Participant Name</font></td>
            <?php
            }
            ?>
         </tr>
         <?php
            if ($TeenCoord == 'Y')
            {
               $select = "select   ParticipantID,
                                   FirstName,
                                   LastName,
                                   Grade
                          from     $ParticipantsTable
                          where    ChurchID = $ChurchID
                          order by LastName,
                                   FirstName";
            }
             else
            {
               $select = "select   ParticipantID,
                                   FirstName,
                                   LastName,
                                   Grade
                          from     $ParticipantsTable
                          where    ChurchID = $ChurchID
                          and      Grade   <= $MaxGrade
                          order by LastName,
                                   FirstName";
            }
            $results = $db->query($select) or die ("Unable to obtain participant list:" . sqlError($db->errorInfo()));

            while ($row = $results->fetch(PDO::FETCH_ASSOC))
            {
               $ParticipantID    = $row['ParticipantID'];
               $ParticipantName  = $row['LastName'].", ".$row['FirstName'];
               $ParticipantGrade = $row['Grade'];

               $cntResult = $db->query("select count(*) as count
                                         from   $TeamMembersTable
                                         where  TeamID        = $TeamID
                                         and    ParticipantID = $ParticipantID")
                            or die ("Unable to determine team membership:" . sqlError($db->errorInfo()));
               $cntRow = $cntResult->fetch(PDO::FETCH_ASSOC);
               $selected = $cntRow['count'];
               ?>
                <?php
                if ($Action == "View")
                {
                   if ($selected > 0)
                   {
                      print "<tr>";
                      print "<td colspan=2>";
                      print "   $ParticipantName ";
                      if ($ParticipantGrade > $MaxGrade)
                      {
                         print "<font color=#FF0000> (Teen Coordinator)</font>";
                      }
                      print "</td>";
                      print "</tr>";
                   }
                }
                else
                {
                ?>
                <tr>
                  <td width="10%">
                     <center>
                        <input type="checkbox" name="s<?php  print $ParticipantID; ?>" value="ON" <?php  print $selected == 1 ? 'checked' : '' ?>/>
                     </center>
                  </td>
                  <td width="10%">
                     <div style="text-align:center">
                  <?php
                     print $ParticipantGrade;
                  ?>
                     </div>
                  </td>
                  <td><?php   print $selected == 1 ? '<b>' : '';
                          print $ParticipantName;
                          if ($ParticipantGrade > $MaxGrade)
                          {
                             print "<font color=#FF0000> (As a Teen Coordinator)</font>";
                          }
                          print $selected == 1 ? '</b>' : '';
                          if (isset($conflicted[$ParticipantID]))
                          {
                             print $conflicted[$ParticipantID];
                          }
                      ?>
                  </td>
               </tr>
               <?php
               }
               ?>
           <?php
         }

         ?>
      </table>
      <?php
      if ($Action != "View")
      {
      ?>
        <p align="center">
        <input type="submit" value="Apply" name="Apply"/>
        </p>
      <?php
      }
      ?>
      </form>
   <?php
   }
   ?>
        <?php
          if ($Admin == 'Y' and isset($_REQUEST['Return']))
          {
             $Return = str_replace('^','&',$_REQUEST['Return']);
             $Return = str_replace('!','?',$Return);
             footer("Return to Team Awards List",$Return);
          }
          else
          {
            footer("Return to Team List","SignupTeamEvents.php");
          }
          ?>
    </body>

</html>
