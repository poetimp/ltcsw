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
   $SchedID    = isset($_REQUEST['SchedID']) ? $_REQUEST['SchedID'] : "";

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

      <title>Schedule Events</title>

   </head>

   <body style="background-color: rgb(217, 217, 255);" onload="window.scrollTo(0,document.body.scrollHeight);document.forms[0].Save.focus();window.scrollBy(0,-30);">
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
      <form method="post">
      <table border="1" width="100%">
      <?php
      while ($row = $results->fetch(PDO::FETCH_ASSOC))
      {
         $EventID   = $row['EventID'];
         $EventName = $row['EventName'];

         ?>
         <tr>
         <td align="center"><a href="<?php print $_SERVER['PHP_SELF']."?AddEventID=$EventID"?>">Add</a></td>
         <td colspan="4"><?php  print $EventName; ?></td>
         </tr>
         <?php
         //print "<br>errorID: [$errorID], EventID: [$EventID]<br>\n";
         if ((isset($_REQUEST['AddEventID']) and $errorID == $EventID) or (isset($_REQUEST['AddEventID']) and $_REQUEST['AddEventID'] == $EventID and !isset($_POST['Save'])))
         {
            ?>
            <tr>
            <td colspan="2" width="100">&nbsp;</td>
            <td width="75" align="center" valign="middle">
               <input type="hidden" value="<?php print $EventID?>" name="EventID"/>
               <input type="submit" value="Save" name="Save"/>
            </td>
            <td colspan="2">Day: <?php selectDay()?>Time: <?php selectTime()?>Room<?php selectRoom(); print $error?></td>
            </tr>
            <?php
         }
         elseif ((isset($_REQUEST['MoveEventID']) and $errorID == $EventID) or (isset($_REQUEST['MoveEventID']) and $_REQUEST['MoveEventID'] == $EventID and !isset($_POST['Save'])))
         {
            $SchedID = isset($_REQUEST['SchedID'])    ? $_REQUEST['SchedID']    : "";
            ?>
            <tr>
               <td colspan="2" width="100">&nbsp;</td>
               <td width="75" align="center" valign="middle">
                  <input type="hidden" value="<?php print $EventID?>" name="EventID"/>
                  <input type="hidden" value="<?php print $SchedID?>" name="SchedID"/>
                  <input type="submit" value="Update" name="Save"/>
               </td>
               <td colspan="2">Day: <?php selectDay(substr($_REQUEST['StartTime'],0,1))?>Time: <?php selectTime($_REQUEST['StartTime'])?>Room<?php selectRoom($_REQUEST['RoomID']); print $error?></td>
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
                // $_REQUEST['MoveEventID'] != $EventID
                // $_REQUEST['StartTime'] != $cntRow['StartTime']
                // $_REQUEST['RoomID'] != $cntRow['RoomID']
                  if (isset($_REQUEST['MoveEventID']))
                  {
                     if     ($moveSuccessful)                                  $ShowRow=1;
                     elseif ($_REQUEST['MoveEventID'] != $EventID)             $ShowRow=1;
                     elseif ($_REQUEST['StartTime']   != $cntRow['StartTime']) $ShowRow=1;
                     elseif ($_REQUEST['RoomID']      != $cntRow['RoomID'])    $ShowRow=1;
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
                     <td width="50">&nbsp;</td>
                     <td width="75" align="center"><a href="<?php  print $_SERVER['PHP_SELF']."?DelEventID=$EventID&StartTime=$StartTime&RoomID=$RoomID"; ?>">Delete</a></td>
                     <td width="75" align="center"><a href="<?php  print $_SERVER['PHP_SELF']."?MoveEventID=$EventID&SchedID=$SchedID&StartTime=$StartTime&RoomID=$RoomID"; ?>">Move</a></td>
                     <td width="350"><?php print TimeToStr($StartTime)." to ".TimeToStr($EndTime)?></td>
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
