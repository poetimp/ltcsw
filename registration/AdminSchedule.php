<?php
include 'include/RegFunctions.php';

$errorID='';

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

//-----------------------------------------------------------------------------
// Delete an existing schedule entry
//-----------------------------------------------------------------------------
if (isset($_REQUEST['DelEventID']))
{
   $EventID    = isset($_REQUEST['DelEventID']) ? $_REQUEST['DelEventID'] : "";
   $StartTime  = isset($_REQUEST['StartTime'])  ? $_REQUEST['StartTime']  : "";
   $RoomID     = isset($_REQUEST['RoomID'])     ? $_REQUEST['RoomID']     : "";

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
   elseif (!ScheduleEventAdd($EventID,$StartTime,$RoomID))
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
//-----------------------------------------------------------------------------
// print the drop-down selection list for room selection
//-----------------------------------------------------------------------------
function selectRoom()
{
   //-----------------------------------------------------------------------------
   // Retrieve a list of all avaliable rooms
   //-----------------------------------------------------------------------------
   $roomList  = getRoomList('fullnames');

   //-----------------------------------------------------------------------------
   // Print the selection Drop-Down
   //-----------------------------------------------------------------------------
   print    "<select size=\"1\" name=\"RoomID\">";
   print    "   <option value=\"\">-Select-</option>";
   foreach ($roomList as $roomNum => $roomName)
   {
      print "<option value=\"$roomNum\">$roomName</option>";
   }
   print    "</select>";
}

//-----------------------------------------------------------------------------
// print the drop-down selection list for Day
//-----------------------------------------------------------------------------
function selectDay()
{
?>
   <select size="1" name=daySelect>
      <option value="">-Select-</option>
      <option value="1">Sunday</option>
      <option value="2">Monday</option>
      <option value="3">Tuesday</option>
      <option value="4">Wednesday</option>
      <option value="5">Thursday</option>
      <option value="6">Friday</option>
      <option value="7">Saturday</option>
   </select>
<?php
}
//-----------------------------------------------------------------------------
// print the drop-down selection list for Time
//-----------------------------------------------------------------------------
function selectTime()
{?>
   <select size="1" name=hourSelect>
      <option value="">HH</option>
      <option value="01">01</option>
      <option value="02">02</option>
      <option value="03">03</option>
      <option value="04">04</option>
      <option value="05">05</option>
      <option value="06">06</option>
      <option value="07">07</option>
      <option value="08">08</option>
      <option value="09">09</option>
      <option value="10">10</option>
      <option value="11">11</option>
      <option value="12">12</option>
   </select>:
   <select size="1" name=minSelect>
      <option value="">MM</option>
      <option value="00">00</option>
      <option value="05">05</option>
      <option value="10">10</option>
      <option value="15">15</option>
      <option value="20">20</option>
      <option value="25">25</option>
      <option value="30">30</option>
      <option value="35">35</option>
      <option value="40">40</option>
      <option value="45">45</option>
      <option value="50">50</option>
      <option value="55">55</option>
   </select>
   <select size="1" name=ampmSelect>
      <option value="">ampm</option>
      <option value="AM">am</option>
      <option value="PM">pm</option>
   </select>
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

   <head>
      <meta http-equiv="Content-Language" content="en-us">
      <title>Schedule Events</title>

   </head>

   <body style="background-color: rgb(217, 217, 255);" onload="window.scrollTo(0,document.body.scrollHeight);document.forms[0].Save.focus();window.scrollBy(0,-30);">
      <h1 align="center">Schedule Convention Events</h1>
      <?php
      $results = mysql_query("select   EventName,
                                       EventID
                              from     $EventsTable
                              where    ConvEvent = 'C'
                              order by EventName")
                 or die ("Unable to obtain convention event list:" . mysql_error());
      ?>
      <table border="1" width="100%">
         <form method="post">
      <?php
      while ($row = mysql_fetch_assoc($results))
      {
         $EventID   = $row['EventID'];
         $EventName = $row['EventName'];

         ?>
         <td align=center><a href="<?php print $_SERVER['PHP_SELF']."?AddEventID=$EventID"?>">Add</a></td>
         <td colspan=3><?php  print $EventName; ?></td>
         <?php

         if ($errorID == $EventID or (isset($_REQUEST['AddEventID']) and $_REQUEST['AddEventID'] == $EventID))
         {
            ?>
            <tr>
            <td width=50>&nbsp;</td>
            <td width=75 align=center valign="middle">
                  <input type=hidden value="<?php print $EventID?>" name=EventID>
                  <input type="submit" value="Save" name="Save">
            </td>
            <td colspan=2>Day: <?php selectDay()?>Time: <?php selectTime()?>Room<?php selectRoom(); print $error?></td>
            <td></td>
            </tr>
            <?php
         }

//            print "<tr><td colspan=4><pre>
//                                      select   s.StartTime,
//                                               s.EndTime,
//                                               r.RoomName,
//                                               s.RoomID
//                                      from     $EventScheduleTable s,
//                                               $RoomsTable         r
//                                      where    s.EventID = '$EventID'
//                                      and      s.RoomID  = r.RoomID
//                                      order by StartTime
//                                      </pre></td></tr>";
         ?>
         <tr>
            <?php
            $cntResult = mysql_query("select   s.StartTime,
                                               s.EndTime,
                                               r.RoomName,
                                               s.RoomID
                                      from     $EventScheduleTable s,
                                               $RoomsTable         r
                                      where    s.EventID = '$EventID'
                                      and      s.RoomID  = r.RoomID
                                      order by StartTime")
                         or die ("Unable to get schedule information for event:" . mysql_error());
            if (mysql_num_rows($cntResult) != 0)
            {
               while ($cntRow = mysql_fetch_assoc($cntResult))
               {
                  $RoomID    = $cntRow['RoomID'];
                  $RoomName  = $cntRow['RoomName'];
                  $StartTime = $cntRow['StartTime'];
                  $EndTime   = $cntRow['EndTime'];

                  ?>
                  <tr>
                  <td width=50>&nbsp;</td>
                  <td width=75 align=center><a href="<?php  print $_SERVER['PHP_SELF']."?DelEventID=$EventID&StartTime=$StartTime&RoomID=$RoomID"; ?>">Delete</a></td>
                  <td width=350><?php print TimeToStr($StartTime)." to ".TimeToStr($EndTime)?></td>
                  <td><?php print $RoomName?></td>
                  </tr>
                  <?php
               }
            }
            ?>
         </tr>
      <?php
      }
      ?>
         </form>
      </table>
      <?php footer("","")?>
   </body>

</html>
