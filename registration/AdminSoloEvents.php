<?php
include 'include/RegFunctions.php';

$ParticipantID    = $_REQUEST['ID'];

$result = mysql_query("select *
                       from   $ParticipantsTable
                       where  ParticipantID = $ParticipantID
                       and    ChurchID      = $ChurchID")
          or die ("Unable to get participant information: ".mysql_error());
$row = mysql_fetch_assoc($result);

$ParticipantName  = $row['LastName'].", ".$row['FirstName'];
$ParticipantGrade = $row['Grade'];
$ParticipantSex   = $row['Gender'];

if (isset($_POST['Apply']))
{
   // Lets see what they are currently signed up for
   $eventList = mysql_query("select r.EventID,
                                    r.ChurchID,
                                    r.Award
                             from   $RegistrationTable r,
                                    $EventsTable       e
                             where  r.EventID       = e.EventID
                             and    r.ParticipantID = $ParticipantID
                             and    e.TeamEvent     = 'N'
                             ")
   or die ("Unable to get event list to clear: " . mysql_error());

   // Clear the slate so that they are signed up for nothing
   while ($row = mysql_fetch_assoc($eventList))
   {
      $EventID           = $row['EventID'];
      $Award[$EventID]   = isset($row['Award']) ? "'".$row['Award']."'" : 'null';
      mysql_query("delete from $RegistrationTable
                   where  ParticipantID = $ParticipantID
                   and    ChurchId      = $ChurchID
                   and    EventID       = $EventID
                  ");
   }

   // Get a list of solo events that they could possibly sign up for
   $results = mysql_query("select   EventID
                           from     $EventsTable
                           where    TeamEvent = 'N'
                           and      MaxGrade >= $ParticipantGrade
                           and  (   Sex       = '$ParticipantSex'
                                 or Sex       = 'E'
                                )
                           order by MinGrade,EventName")
              or die ("Unable to get event list:" . mysql_error());

   //---------------------------------------
   // Check for schedule conflicts
   //---------------------------------------
   unset($conflicted);
   while ($row = mysql_fetch_assoc($results))
   {
      $EventID   = $row['EventID'];
      $controlID = 's'.$EventID;
      $AwardStr  = isset($Award[$EventID]) ? $Award[$EventID] : 'null';


      if (isset($_POST[$controlID]) and $_POST[$controlID] == 'ON') // Un scheduled event go ahead and add
      {
         mysql_query("insert into $RegistrationTable
                        (ChurchID     ,
                         EventID      ,
                         ParticipantID,
                         SchedID,
                         Award)
                 values ($ChurchID,
                         $EventID,
                         $ParticipantID,
                         0,
                         $AwardStr
                         )"
                     )
         or die ("Unable to insert registration record: " . mysql_error());
      }
      else if (isset($_POST[$controlID]) and $_POST[$controlID] > 0) // Scheduled so must check
      {
         // $SchedID is the id of the event they want to schedule
         // See if any other event they have already signed up for overlaps
         // this event in any way.
         $SchedID = $_POST[$controlID];

         if (($conflictingEvent = ScheduleGetEventName($SchedID,$ParticipantID)) != "")
         {
            $message = "One or more events de-selected due to time conflicts";
            $conflicted[$EventID] = "<font color=red><b>&nbsp;&nbsp;".
                                    "<= De-selected due to schedule conflict with: ".
                                    $conflictingEvent.
                                    "</b></font>";
         }
         else
         {
            mysql_query("insert into $RegistrationTable
                          (ChurchID     ,
                           EventID      ,
                           ParticipantID,
                           SchedID,
                           Award)
                  values ($ChurchID,
                           $EventID,
                           $ParticipantID,
                           $SchedID,
                           $AwardStr
                           )"
                        )
            or die ("Unable to insert the registration record: " . mysql_error());
         }
      }
   }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <title>
          Manage Individual Events
       </title>
       <h1 align=center>Update Individual Events For</h1>
       <h2 align=center><?php  print $ParticipantName; ?></h2>
       <?php
       if (isset($_POST['Apply']))
       {
       ?>
          <h3><font color="#FF0000">Applied</font></h3>
          <?php
             if (isset($message) and $message != "")
             {
                print "<h3><font color=\"#FF0000\">$message</font></h3>\n";
             }
          ?>
       <?php
       }
       ?>
    </head>
    <body style="background-color: rgb(217, 217, 255);">
        <form method="post" action="AdminSoloEvents.php?ID=<?php  print $ParticipantID; ?>">
    	<table border="1" width="100%" id="table1">
			<tr>
				<td width="78" bgcolor="#000000"><font color="#FFFF00">Selected</font></td>
				<td bgcolor="#000000"><font color="#FFFF00">Event Name</font></td>
			</tr>
			<?php
//			print "<pre>
//			select   EventID,
//                                             EventName,
//                                             Case ConvEvent
//                                                When 'C' then 'Convention'
//                                                When 'P' then 'Preconvention'
//                                                Else          'Unknown'
//                                             End
//                                             ConvEvent,
//                                             (MaxWebSlots * MaxRooms) MaxWebSlots,
//                                             EventAttended
//                                    from     $EventsTable
//                                    where    TeamEvent = 'N'
//                                    and      MaxGrade >= $ParticipantGrade
//                                    and  (   Sex       = '$ParticipantSex'
//                                          or Sex       = 'E'
//                                         )
//                                    order by EventName
//			</pre>";
            $results = mysql_query("select   EventID,
                                             EventName,
                                             Case ConvEvent
                                                When 'C' then 'Convention'
                                                When 'P' then 'Preconvention'
                                                Else          'Unknown'
                                             End
                                             ConvEvent,
                                             (MaxWebSlots * MaxRooms) MaxWebSlots,
                                             EventAttended
                                    from     $EventsTable
                                    where    TeamEvent = 'N'
                                    and      MaxGrade >= $ParticipantGrade
                                    and  (   Sex       = '$ParticipantSex'
                                          or Sex       = 'E'
                                         )
                                    order by EventName
                                   ")
                       or die ("Unable to obtain eligible event list:" . mysql_error());

            while ($row = mysql_fetch_assoc($results))
            {
               $EventID       = $row['EventID'];
               $EventName     = $row['EventName'];
               $ConvEvent     = $row['ConvEvent'];
               $MaxWebSlots   = $row['MaxWebSlots'];
               $EventAttended = $row['EventAttended'];


               $cntResult = mysql_query("select count(*) as count
                                         from   $RegistrationTable
                                         where  ChurchID      = $ChurchID
                                         and    ParticipantID = $ParticipantID
                                         and    EventID       = $EventID
                                        ")
                            or die ("Unable to determine if row selected:" . mysql_error());
               $cntRow = mysql_fetch_assoc($cntResult);
               $selected = $cntRow['count'];
               ?>
		       <tr>
                  <td width="78">
                     <?php
                     if ($EventAttended == 'Y')
                     {
                        $cntResult = mysql_query("select count(*) as count
                                                  from   $EventScheduleTable
                                                  where  EventID = $EventID
                                                 ")
                                   or die ("Unable to determine if this event meets at a particular time:" . mysql_error());
                        $cntRow    = mysql_fetch_assoc($cntResult);
                        $scheduled = ($cntRow['count'] > 0);
                     }
                     else
                     {
                        $scheduled = 0;
                     }

                     if ($scheduled)
                     {
                        $SchedResult = mysql_query("select distinct
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
                                        or die ("Unable to Get scheduled slots for each scheduled event:" . mysql_error());

                        $freeSlots = 0;
                        while ($SchedRow = mysql_fetch_assoc($SchedResult))
                        {
                           $SchedID      = $SchedRow['SchedID'];
                           $StartTime    = $SchedRow['StartTime'];
                           $RoomName     = $SchedRow['RoomName'];
                           $MaxWebSlots  = $SchedRow['MaxWebSlots'];
                           $freeSlots    = (($MaxWebSlots - slotsFilledInRoom($RoomName,$StartTime) > 0) or $freeSlots);
                        }

                        if ($selected == 0 and !$freeSlots)
                        {
                           print "<center>Full</center>";
                        }
                        else
                        {
                        ?>
                           <select size="1"<?php  print "name=\"s$EventID\"";?>>
                           <?php
                           $sel = $selected == 0 ? "selected" : "";
                           ?>
                              <option value = "0" <?php  print $sel; ?>>Not Selected</option>
                           <?php
                           if ($selected > 0)
                           {
                              $cntResult = mysql_query("select SchedID
                                                        from   $RegistrationTable
                                                        where  ChurchID      = $ChurchID
                                                        and    ParticipantID = $ParticipantID
                                                        and    EventID       = $EventID
                                                        ")
                                           or die ("Unable to get schedule ID:" . mysql_error());
                              $cntRow = mysql_fetch_assoc($cntResult);
                              $SchedID = $cntRow['SchedID'];
                           }
                           else
                           {
                              $SchedID = "";
                           }
                           $sql="select distinct
                                                              s.SchedID,
                                                              s.StartTime,
                                                              (e.MaxWebSlots * e.MaxRooms) MaxWebSlots,
                                                              IF (RoomName REGEXP '-[a-z]$',
                                                                  SUBSTR(RoomName,1,LENGTH(RoomName)-2), 
                                                                  RoomName)
                                                              as RoomName
                                                       from   $EventScheduleTable s,
                                                              $EventsTable        e,
                                                              $RoomsTable         r
                                                       where  s.EventID = $EventID
                                                       and    e.EventID = s.EventID
                                                       and    s.RoomID  = r.RoomID
                                                       order  by StartTime
                                                      ";
                           //print "<pre>$sql</$pre>";
                           
                           $SchedResult = mysql_query($sql)
                                           or die ("Unable to Get available scheduled slots for event:" . mysql_error());

                           while ($SchedRow = mysql_fetch_assoc($SchedResult))
                           {
                              $StartTime   = $SchedRow['StartTime'];
                              $RoomName    = $SchedRow['RoomName'];
                              if (($SchedRow['MaxWebSlots'] - slotsFilledInRoom($RoomName,$StartTime) > 0) or $selected > 0)
                              {
                                 $sel =  ($selected > 0 and $SchedID == $SchedRow['SchedID']) ? "selected" : "";
                                 print "<option value=\"".$SchedRow['SchedID']."\" $sel>".TimeToStr($SchedRow['StartTime'])."</option>\n";
                              }
                           }
                           ?>
                           </select>
                           <?php
                       }
                     }
                     else
                     {
                        $cntResult = mysql_query("select count(*) count
                                                  from   $RegistrationTable
                                                  where  EventID       = $EventID
                                                  ")
                                           or die ("Unable to get event registration count:" . mysql_error());
                        $cntRow = mysql_fetch_assoc($cntResult);
                        $regCount = $cntRow['count'];
                        if ($selected == 0 and $regCount >= $MaxWebSlots)
                        {
                           print "<center>Full</center>";
                        }
                        else
                        {
                           print "<center><input type=\"checkbox\" name=\"s$EventID\" value=\"ON\""; print $selected > 0 ? " checked" : ""; print"></center>";
                        }
                     }
                     ?>
                  </td>
                  <td>
                     <?php
                     print $selected == 1 ? '<b>' : '';
                     print $EventName;
                     print $selected == 1 ? '</b>' : '';
                     if (isset($conflicted[$EventID]))
                        print $conflicted[$EventID];
                     ?>
                  </td>
               </tr>
           <?php
         }

         ?>
      </table>
        <p align="center">
        <input type="submit" value="Apply" name="Apply">
      </p>
      </form>
      <?php footer("Return to Participant List","SignupSoloEvents.php")?>
    </body>

</html>