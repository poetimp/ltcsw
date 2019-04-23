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

$ParticipantID    = $_REQUEST['ID'];

$result = $db->query("select *
                       from   $ParticipantsTable
                       where  ParticipantID = $ParticipantID
                       and    ChurchID      = $ChurchID")
          or die ("Unable to get participant information: ".sqlError());
$row = $result->fetch(PDO::FETCH_ASSOC);

$ParticipantName  = $row['LastName'].", ".$row['FirstName'];
$ParticipantGrade = $row['Grade'];
$ParticipantSex   = $row['Gender'];

if (isset($_POST['Apply']))
{
   // Lets see what they are currently signed up for
   $eventList = $db->query("select r.EventID,
                                    r.ChurchID,
                                    r.Award
                             from   $RegistrationTable r,
                                    $EventsTable       e
                             where  r.EventID       = e.EventID
                             and    r.ParticipantID = $ParticipantID
                             and    e.TeamEvent     = 'N'
                             ")
   or die ("Unable to get event list to clear: " . sqlError());

   // Clear the slate so that they are signed up for nothing
   while ($row = $eventList->fetch(PDO::FETCH_ASSOC))
   {
      $EventID           = $row['EventID'];
      $Award[$EventID]   = isset($row['Award']) ? "'".$row['Award']."'" : 'null';
      $db->query("delete from $RegistrationTable
                   where  ParticipantID = $ParticipantID
                   and    ChurchId      = $ChurchID
                   and    EventID       = $EventID
                  ");
   }

   // Get a list of solo events that they could possibly sign up for
   $results = $db->query("select   EventID
                           from     $EventsTable
                           where    TeamEvent = 'N'
                           and      MaxGrade >= $ParticipantGrade
                           and  (   Sex       = '$ParticipantSex'
                                 or Sex       = 'E'
                                )
                           order by MinGrade,EventName")
              or die ("Unable to get event list:" . sqlError());

   //---------------------------------------
   // Check for schedule conflicts
   //---------------------------------------
   unset($conflicted);
   while ($row = $results->fetch(PDO::FETCH_ASSOC))
   {
      $EventID   = $row['EventID'];
      $controlID = 's'.$EventID;
      $AwardStr  = isset($Award[$EventID]) ? $Award[$EventID] : 'null';


      if (isset($_POST[$controlID]) and $_POST[$controlID] == 'ON') // Un scheduled event go ahead and add
      {
         $db->query("insert into $RegistrationTable
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
         or die ("Unable to insert registration record: " . sqlError());
         WriteToLog("Added $ParticipantID to solo event $EventID");
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
            $db->query("insert into $RegistrationTable
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
            or die ("Unable to insert the registration record: " . sqlError());
            WriteToLog("Aded $ParticipantID to solo event $EventID");
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
       <meta http-equiv="Content-Language" content="en-us" />
       <meta name="viewport" content="width=device-width, initial-scale=1.0" />
       <link rel="stylesheet" href="include/registration.css" type="text/css" />

    </head>
    <body>
       <h1 align="center">Update Individual Events For</h1>
       <h2 align="center"><?php  print $ParticipantName; ?></h2>
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
        <form method="post" action="AdminSoloEvents.php?ID=<?php  print $ParticipantID; ?>">
           <table class='registrationTable' id="table1">
              <tr>
                 <th style='width: 15%;'>Selected</th>
                 <th >Event Name</th>
              </tr>
              <?php
//                      print "<pre>
//                      select   EventID,
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
//                      </pre>";
            $results = $db->query("select   EventID,
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
                       or die ("Unable to obtain eligible event list:" . sqlError());

            while ($row = $results->fetch(PDO::FETCH_ASSOC))
            {
               $EventID       = $row['EventID'];
               $EventName     = $row['EventName'];
               $ConvEvent     = $row['ConvEvent'];
               $MaxWebSlots   = $row['MaxWebSlots'];
               $EventAttended = $row['EventAttended'];


               $cntResult = $db->query("select count(*) as count
                                         from   $RegistrationTable
                                         where  ChurchID      = $ChurchID
                                         and    ParticipantID = $ParticipantID
                                         and    EventID       = $EventID
                                        ")
                            or die ("Unable to determine if row selected:" . sqlError());
               $cntRow = $cntResult->fetch(PDO::FETCH_ASSOC);
               $selected = $cntRow['count'];
               ?>
               <tr>
                  <td style='width: 15%;'>
                     <?php
                     if ($EventAttended == 'Y')
                     {
                        $cntResult = $db->query("select count(*) as count
                                                  from   $EventScheduleTable
                                                  where  EventID = $EventID
                                                 ")
                                   or die ("Unable to determine if this event meets at a particular time:" . sqlError());
                        $cntRow    = $cntResult->fetch(PDO::FETCH_ASSOC);
                        $scheduled = ($cntRow['count'] > 0);
                     }
                     else
                     {
                        $scheduled = 0;
                     }

                     if ($scheduled)
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
                                                   ")
                                        or die ("Unable to Get scheduled slots for each scheduled event:" . sqlError());

                        $freeSlots = 0;
                        while ($SchedRow = $SchedResult->fetch(PDO::FETCH_ASSOC))
                        {
                           $freeSlots    = (($SchedRow['MaxWebSlots'] - slotsFilledInRoom($SchedRow['RoomName'],$SchedRow['StartTime']) > 0) or $freeSlots);
                        }

                        if ($UserStatus != 'O')
                        {
                           print "<div style='text-align: center'>";
                           if ($selected > 0)
                              print "Registered";
                           else
                              print "&nbsp;";
                           print "</div>";
                        }
                        elseif ($selected == 0 and !$freeSlots)
                        {
                           print "<div style='text-align: center'>Full</div>";
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
                              $cntResult = $db->query("select SchedID
                                                        from   $RegistrationTable
                                                        where  ChurchID      = $ChurchID
                                                        and    ParticipantID = $ParticipantID
                                                        and    EventID       = $EventID
                                                        ")
                                           or die ("Unable to get schedule ID:" . sqlError());
                              $cntRow = $cntResult->fetch(PDO::FETCH_ASSOC);
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

                           $SchedResult = $db->query($sql)
                                           or die ("Unable to Get available scheduled slots for event:" . sqlError());

                           while ($SchedRow = $SchedResult->fetch(PDO::FETCH_ASSOC))
                           {
                              if (($SchedRow['MaxWebSlots'] - slotsFilledInRoom($SchedRow['RoomName'],$SchedRow['StartTime']) > 0) or ($selected > 0 and $SchedID == $SchedRow['SchedID']))
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
                        $cntResult = $db->query("select count(*) count
                                                  from   $RegistrationTable
                                                  where  EventID       = $EventID
                                                  ")
                                           or die ("Unable to get event registration count:" . sqlError());
                        $cntRow = $cntResult->fetch(PDO::FETCH_ASSOC);
                        $regCount = $cntRow['count'];
                        if ($selected == 0 and $regCount >= $MaxWebSlots)
                        {
                           print "<div style='text-align: center'>Full</div>";
                        }
                        else
                        {
                           if ($UserStatus == 'O')
                           {
                              print "<div style='text-align: center'><input type=\"checkbox\" name=\"s$EventID\" value=\"ON\""; print $selected > 0 ? " checked" : ""; print"></div>";
                           }
                           else
                           {
                              print "<div style='text-align: center'>";print $selected > 0 ? " Registered" : "&nbsp;";print "</div>";
                           }
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
      <?php
      if ($UserStatus == 'O')
      {
         ?>
         <p align="center">
            <input type="submit" value="Apply" name="Apply"/>
         </p>
         <?php
      }
      ?>
      </form>
      <?php footer("Return to Participant List","SignupSoloEvents.php")?>
    </body>

</html>