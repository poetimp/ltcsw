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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
   <BODY>
      <?php
         print "<pre>";
         print "_SERVER:";
         print_r($_SERVER);
         print "<br><br>_SESSION:";
         print_r($_SESSION);
         print "</pre><br><hr><br>";
         ?>
   </body>
</html>
<?php

$EventID    =   100;
$StartTime  = 61430;
$RoomID     =     2;

if (ScheduleEventAdd($EventID,$StartTime,$RoomID))
{
   print "<br>Add Success!<br>";
}
else
{
   print "<br>Add Bummer!<br>";
}
if (ScheduleEventAdd($EventID,"61450",5))
{
   print "<br>Add Success!<br>";
}
else
{
   print "<br>Add Bummer!<br>";
}
if (ScheduleEventAdd($EventID,$StartTime,4))
{
   print "<br>Add Success!<br>";
}
else
{
   print "<br>Add Bummer!<br>";
}

$eventSchedule= ScheduleEventGet(100);
print("<table class='registrationTable' border=1>");
foreach ($eventSchedule as $RoomName=>$StartTime)
   print("<tr><td>$RoomName</td><td>".TimeToStr($StartTime)."</td></tr>");
print("</table>");
print("<br><br>");

$EventID    =   100;
$StartTime  = 61430;
$RoomID     =     2;
if (ScheduleEventDel($EventID,$StartTime,$RoomID))
{
   print "<br>Del Success!<br>";
}
else
{
   print "<br>Del Bummer!<br>";
}

//$church_list = ChurchesRegistered();
//print("<table class='registrationTable' border=1>");
//foreach ($church_list as $ChurchID=>$ChurchName)
//   print("<tr><td>$ChurchID</td><td>$ChurchName</td></tr>");
//print("</table>");
//print("<br><br>");
//$ParticipantIDs = ActiveParticipants(108);
//print("<table class='registrationTable' border=1>");
//foreach ($ParticipantIDs as $ParticipantID=>$ParticipantName)
//   print("<tr><td>$ParticipantID</td><td>$ParticipantName</td></tr>");
//print("</table>");
//
//print("<table class='registrationTable' border=1>");
//foreach ($_SERVER as $Name=>$Value)
//   print("<tr><td>$Name</td><td>$Value</td></tr>");
//print("</table>");
?>
