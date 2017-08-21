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

$errorID='';
$error='';
$moveSuccessful=0;

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

//-----------------------------------------------------------------------------
// Delete an existing schedule entry
//-----------------------------------------------------------------------------
if (isset($_POST['DelEventID']))
{
   $EventID    = isset($_POST['DelEventID']) ? $_POST['DelEventID'] : "";
   $StartTime  = isset($_POST['StartTime'])  ? $_POST['StartTime']  : "";
   $RoomID     = isset($_POST['RoomID'])     ? $_POST['RoomID']     : "";

   if ($EventID != "" and $StartTime != "" and $RoomID != "")
   {
      ScheduleEventDel($EventID,$StartTime,$RoomID);
   }
}
//-----------------------------------------------------------------------------
// Save new schedule entry
//-----------------------------------------------------------------------------
if (isset($_POST['Save']))
{
   $EventID    = $_POST['EventID'];
   $StartDay   = isset($_POST['daySelect'])  ? $_POST['daySelect']  : "";
   $StartHour  = isset($_POST['hourSelect']) ? $_POST['hourSelect'] : "";
   $StartMin   = isset($_POST['minSelect'])  ? $_POST['minSelect']  : "";
   $StartAmPm  = isset($_POST['ampmSelect']) ? $_POST['ampmSelect'] : "";
   $RoomID     = isset($_POST['RoomID'])     ? $_POST['RoomID']     : "";
   $SchedID    = isset($_POST['SchedID']) ? $_POST['SchedID'] : "";

   $StartTime = $StartDay.($StartAmPm == "AM" ? $StartHour : $StartHour+12).$StartMin;

   if ($StartDay  == "" or
       $StartHour == "" or
       $StartMin  == "" or
       $StartAmPm == "" or
       $RoomID    == ""
      )
   {
      $error="<b><== Please choose an option in every selection.</b>";
      $errorID = $EventID;
   }
   else
   {
      if ($_POST['Save'] == 'Update')
      {
         if (!ScheduleEventUpd($SchedID,$EventID,$StartTime,$RoomID))
         {
            $error="<b><== Event Not added due to Conflict</b>";
            $errorID = $EventID;
            $moveSuccessful=0;
         }
         else
         {
            $error="";
            $errorID = "";
            $moveSuccessful=1;
         }
      }
      else
      {
         if (!ScheduleEventAdd($EventID,$StartTime,$RoomID))
         {
            $error="<b><== Event Not added due to conflict</b>";
            $errorID = $EventID;
         }
         else
         {
            $error="";
            $errorID = "";
         }
      }
   }
}
//-----------------------------------------------------------------------------
// print the drop-down selection list for room selection
//-----------------------------------------------------------------------------
function selectRoom($RoomID = '')
{
   //-----------------------------------------------------------------------------
   // Retrieve a list of all avaliable rooms
   //-----------------------------------------------------------------------------
   $roomList  = getRoomList('fullnames');

   //-----------------------------------------------------------------------------
   // Print the selection Drop-Down
   //-----------------------------------------------------------------------------
   print    "\n<select size=\"1\" name=\"RoomID\">\n";
   print    "   <option value=\"\">-Select-</option>\n";
   foreach ($roomList as $roomNum => $roomName)
   {
      if (intval($RoomID) == intval($roomNum))
      {
         print "   <option value=\"$roomNum\" selected>$roomName</option>\n";
      }
      else
      {
         print "   <option value=\"$roomNum\">$roomName</option>\n";
      }
   }
   print    "</select>\n";
}

//-----------------------------------------------------------------------------
// print the drop-down selection list for Day
//-----------------------------------------------------------------------------
function selectDay($Day = '')
{
   print '<select size="1" name="daySelect">
             <option value="">-Select-</option>
             <option value="1"'.($Day == 1 ? " selected" : "").'>Sunday</option>
             <option value="2"'.($Day == 2 ? " selected" : "").'>Monday</option>
             <option value="3"'.($Day == 3 ? " selected" : "").'>Tuesday</option>
             <option value="4"'.($Day == 4 ? " selected" : "").'>Wednesday</option>
             <option value="5"'.($Day == 5 ? " selected" : "").'>Thursday</option>
             <option value="6"'.($Day == 6 ? " selected" : "").'>Friday</option>
             <option value="7"'.($Day == 7 ? " selected" : "").'>Saturday</option>
          </select>
          ';
}
//-----------------------------------------------------------------------------
// print the drop-down selection list for Time
//-----------------------------------------------------------------------------
function selectTime($Timestr = '')
{
   if (strlen($Timestr) == 5)
   {
      $hh = intval(substr($Timestr,1,2));
      $mm = substr($Timestr,3,2);
      if ($hh > 12)
      {
         $hh -= 12;
         $ampm = 'pm';
      }
      else
      {
         $ampm = 'am';
      }
   }
   else
   {
      $hh  = '';
      $mm  = '';
      $ampm= '';
   }
   print '<select size="1" name="hourSelect">
            <option value="">HH</option>
            <option value="01"'.($hh == 1  ? " selected" : "").'>01</option>
            <option value="02"'.($hh == 2  ? " selected" : "").'>02</option>
            <option value="03"'.($hh == 3  ? " selected" : "").'>03</option>
            <option value="04"'.($hh == 4  ? " selected" : "").'>04</option>
            <option value="05"'.($hh == 5  ? " selected" : "").'>05</option>
            <option value="06"'.($hh == 6  ? " selected" : "").'>06</option>
            <option value="07"'.($hh == 7  ? " selected" : "").'>07</option>
            <option value="08"'.($hh == 8  ? " selected" : "").'>08</option>
            <option value="09"'.($hh == 9  ? " selected" : "").'>09</option>
            <option value="10"'.($hh == 10 ? " selected" : "").'>10</option>
            <option value="11"'.($hh == 11 ? " selected" : "").'>11</option>
            <option value="12"'.($hh == 11 ? " selected" : "").'>12</option>
         </select>:
         <select size="1" name="minSelect">
            <option value="">MM</option>
            <option value="00"'.($mm == 0  ? " selected" : "").'>00</option>
            <option value="05"'.($mm == 5  ? " selected" : "").'>05</option>
            <option value="10"'.($mm == 10 ? " selected" : "").'>10</option>
            <option value="15"'.($mm == 15 ? " selected" : "").'>15</option>
            <option value="20"'.($mm == 20 ? " selected" : "").'>20</option>
            <option value="25"'.($mm == 25 ? " selected" : "").'>25</option>
            <option value="30"'.($mm == 30 ? " selected" : "").'>30</option>
            <option value="35"'.($mm == 35 ? " selected" : "").'>35</option>
            <option value="40"'.($mm == 40 ? " selected" : "").'>40</option>
            <option value="45"'.($mm == 45 ? " selected" : "").'>45</option>
            <option value="50"'.($mm == 50 ? " selected" : "").'>50</option>
            <option value="55"'.($mm == 55 ? " selected" : "").'>55</option>
         </select>
         <select size="1" name="ampmSelect">
            <option value="">ampm</option>
            <option value="AM"'.($ampm == 'am' ? " selected" : "").'>am</option>
            <option value="PM"'.($ampm == 'pm' ? " selected" : "").'>pm</option>
         </select>
         ';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

   <head>
      <meta http-equiv="Content-Language" content="en-us"/>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel=stylesheet href="include/registration.css" type="text/css" />

      <title>Schedule Events</title>

   </head>
   <script>
      window.onload = function ()
      {
         <?php
         if (isset($_POST['scrollPos']))
         {
         ?>
            //document.documentElement.scrollTop = document.body.scrollTop = <?php print $_POST['scrollPos']?>;
            window.scrollTo(0, <?php print $_POST['scrollPos']?>);
         <?php
         }
         ?>
      }
      function submitForm()
      {
         //var scrollPos = document.documentElement.scrollTop || document.body.scrollTop;
         var scrollPos = window.scrollY;
         var input = document.createElement( 'input' );
         input.type = 'hidden';
         input.name = 'scrollPos';
         input.value = scrollPos;
         document.forms.mainForm.appendChild(input);

         document.mainForm.submit() ;
      }

      function addEvent(EventID)
      {
         var input = document.createElement( 'input' );
         input.type = 'hidden';
         input.name = 'AddEventID';
         input.value = EventID;
         document.forms.mainForm.appendChild(input);

         submitForm();
      }
      function saveEvent(EventID)
      {
         var input = document.createElement( 'input' );
         input.type = 'hidden';
         input.name = 'EventID';
         input.value = EventID;
         document.forms.mainForm.appendChild(input);

         var input = document.createElement( 'input' );
         input.type = 'hidden';
         input.name = 'Save';
         input.value = 'Save';
         document.forms.mainForm.appendChild(input);

         submitForm();
      }
      function updateEvent(EventID,SchedID)
      {
         var input = document.createElement( 'input' );
         input.type = 'hidden';
         input.name = 'EventID';
         input.value = EventID;
         document.forms.mainForm.appendChild(input);

         var input = document.createElement( 'input' );
         input.type = 'hidden';
         input.name = 'SchedID';
         input.value = SchedID;
         document.forms.mainForm.appendChild(input);

         var input = document.createElement( 'input' );
         input.type = 'hidden';
         input.name = 'Save';
         input.value = 'Update';
         document.forms.mainForm.appendChild(input);

         submitForm();
      }
      function delEvent(EventID,StartTime,RoomID)
      {
         var fldDelEventID = document.createElement( 'input' );
         fldDelEventID.type = 'hidden';
         fldDelEventID.name = 'DelEventID';
         fldDelEventID.value = EventID;
         document.forms.mainForm.appendChild(fldDelEventID);

         var fldStartTime = document.createElement( 'input' );
         fldStartTime.type = 'hidden';
         fldStartTime.name = 'StartTime';
         fldStartTime.value = StartTime;
         document.forms.mainForm.appendChild(fldStartTime);

         var fldRoomID = document.createElement( 'input' );
         fldRoomID.type = 'hidden';
         fldRoomID.name = 'RoomID';
         fldRoomID.value = RoomID;
         document.forms.mainForm.appendChild(fldRoomID);

         submitForm();
      }
      function moveEvent(EventID,SchedID,StartTime,RoomID)
      {
         var input = document.createElement( 'input' );
         input.type = 'hidden';
         input.name = 'MoveEventID';
         input.value = EventID;
         document.forms.mainForm.appendChild(input);

         var input = document.createElement( 'input' );
         input.type = 'hidden';
         input.name = 'SchedID';
         input.value = SchedID;
         document.forms.mainForm.appendChild(input);

         var input = document.createElement( 'input' );
         input.type = 'hidden';
         input.name = 'StartTime';
         input.value = StartTime;
         document.forms.mainForm.appendChild(input);

         var input = document.createElement( 'input' );
         input.type = 'hidden';
         input.name = 'RoomID';
         input.value = RoomID;
         document.forms.mainForm.appendChild(input);

         submitForm();
      }
   </script>
   <body>
      <h1 align="center">Schedule Convention Events</h1>
      <?php
      //dumpSysVars();
      $results = $db->query("select   EventName,
                                       EventID
                              from     $EventsTable
                              where    ConvEvent = 'C'
                              order by EventName")
                 or die ("Unable to obtain convention event list:" . sqlError());
      ?>
      <form id='mainForm' name='mainForm' method="post">
      <table class='registrationTable'>
      <?php
      while ($row = $results->fetch(PDO::FETCH_ASSOC))
      {
         $EventID   = $row['EventID'];
         $EventName = $row['EventName'];

         ?>
         <tr>
            <td style='text-align: center'><a href='javascript:void(0)' onclick='javascript:addEvent(<?php print "\"$EventID\""?>)'>Add</a></td>
            <th colspan="4"><?php  print $EventName; ?></th>
         </tr>
         <?php
         //print "<br>errorID: [$errorID], EventID: [$EventID], AddEventID: [".$_POST['AddEventID']."]<br>\n";
         if ((isset($_POST['EventID']) and $errorID == $EventID) or
             (isset($_POST['AddEventID']) and $_POST['AddEventID'] == $EventID and !isset($_POST['Save'])))
         {
            ?>
            <tr>
               <td colspan="2" style='width: 100px'>&nbsp;</td>
               <td style='width: 75px; text-align: center; vertical-align: middle'>
                  <a href='javascript:void(0)' onclick='javascript:saveEvent(<?php print "\"$EventID\""?>)'><input type='button' value='Save'></input></a></td>
               </td>
               <td colspan="2">Day: <?php selectDay()?>Time: <?php selectTime()?>Room<?php selectRoom(); print $error?></td>
            </tr>
            <?php
         }
         elseif ((isset($_POST['MoveEventID']) and $errorID == $EventID) or (isset($_POST['MoveEventID']) and $_POST['MoveEventID'] == $EventID and !isset($_POST['Save'])))
         {
            $SchedID = isset($_POST['SchedID'])    ? $_POST['SchedID']    : "";
            ?>
            <tr>
               <td colspan="2" style='width: 100px'>&nbsp;</td>
               <td style='width: 75px; text-align: center; vertical-align: middle'>
                  <a href='javascript:void(0)' onclick='javascript:updateEvent(<?php print "\"$EventID\",\"$SchedID\""?>)'><input type='button' value='Update'></a></td>
               </td>
               <td colspan="2">Day: <?php selectDay(substr($_POST['StartTime'],0,1))?>Time: <?php selectTime($_POST['StartTime'])?>Room<?php selectRoom($_POST['RoomID']); print $error?></td>
            </tr>
         <?php
         }

            $cntResult = $db->query("select    count(*)
                                      from     $EventScheduleTable s,
                                               $RoomsTable         r
                                      where    s.EventID = '$EventID'
                                      and      s.RoomID  = r.RoomID
                                      order by StartTime")
                         or die ("Unable to get schedule information for event:" . sqlError());
            if ($cntResult->fetchColumn() > 0)
            {
               $cntResult = $db->query("select    s.StartTime,
                                                  s.EndTime,
                                                  r.RoomName,
                                                  s.RoomID,
                                                  s.SchedID
                                         from     $EventScheduleTable s,
                                                  $RoomsTable         r
                                         where    s.EventID = '$EventID'
                                         and      s.RoomID  = r.RoomID
                                         order by StartTime")
                            or die ("Unable to get schedule information for event:" . sqlError());
               while ($cntRow = $cntResult->fetch(PDO::FETCH_ASSOC))
               {// $moveSuccessful or
                // $_POST['MoveEventID'] != $EventID
                // $_POST['StartTime'] != $cntRow['StartTime']
                // $_POST['RoomID'] != $cntRow['RoomID']
                  if (isset($_POST['MoveEventID']))
                  {
                     if     ($moveSuccessful)                                  $ShowRow=1;
                     elseif ($_POST['MoveEventID'] != $EventID)                $ShowRow=1;
                     elseif ($_POST['StartTime']   != $cntRow['StartTime'])    $ShowRow=1;
                     elseif ($_POST['RoomID']      != $cntRow['RoomID'])       $ShowRow=1;
                     else                                                      $ShowRow=0;
                  }
                  else
                     $ShowRow = 1;

                  if ($ShowRow)
                  {
                     $RoomID    = $cntRow['RoomID'];
                     $RoomName  = $cntRow['RoomName'];
                     $StartTime = $cntRow['StartTime'];
                     $EndTime   = $cntRow['EndTime'];
                     $SchedID   = $cntRow['SchedID'];

                     ?>
                     <tr>
                        <td style='width: 50px;'>&nbsp;</td>
                        <td style='width: 75px; text-align: center;'><a href='javascript:void(0)' onclick='javascript:delEvent(<?php print "\"$EventID\",\"$StartTime\",\"$RoomID\""?>)'>Delete</a></td>
                        <td style='width: 75px; text-align: center;'><a href='javascript:void(0)' onclick='javascript:moveEvent(<?php print "\"$EventID\",\"$SchedID\",\"$StartTime\",\"$RoomID\""?>)'>Move</a></td>
                        <td style='width: 350;'><?php print TimeToStr($StartTime)." to ".TimeToStr($EndTime)?></td>
                        <td><?php print $RoomName?></td>
                     </tr>
                     <?php
                  }
               }
            }
      }
      ?>

         </table>
      </form>
    <?php footer("","")?>
   </body>

</html>
