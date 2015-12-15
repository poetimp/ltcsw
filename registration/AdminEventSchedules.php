<?php
include 'include/RegFunctions.php';

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}


//-----------------------------------------------------------------------------
// Get the list of available rooms
//-----------------------------------------------------------------------------
function ScheduleEventGet($EventID)
{
   $schedList = array();
   global $EventSchedulesTable;

   $result     = $db->query("select   RoomID,
                                       StartTime
                              from     $EventSchedulesTable
                              where    EventID = $EventID
                              order by RoomID, StartTime
                              ")
               or die ("Unable to get schedule information for event: ".sqlError());

   while ($row = $result->fetch(PDO::FETCH_ASSOC))
   {
      $schedList[TimeToStr($row['StartTime'])] = getRoomName(isset($row['RoomID']) ? $row['RoomID'] : "No Room");
   }

   return $schedList;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

   <head>
      <meta http-equiv="Content-Language" content="en-us">
      <title>Schedule Events</title>

   </head>

   <body style="background-color: rgb(217, 217, 255);">
      <h1 align="center">Schedule Convention Events</h1>
      <?php
      $results = $db->query("select   EventName,
                                       EventID
                              from     $EventsTable
                              where    ConvEvent = 'C'
                              and      EventAttended='Y'
                              order by EventName")
                 or die ("Unable to obtain convention event list:" . sqlError());
      ?>
      <table border="1" width="100%">
      <?php
      while ($row = $results->fetch(PDO::FETCH_ASSOC))
      {
         $EventID   = $row['EventID'];
         $EventName = $row['EventName'];
         ?>
         <tr>
            <td colspan="3"><?php print "<b>$EventName</b>"; ?></td>
         </tr>
      <?php
         $schedList=ScheduleEventGet($EventID);
//          print"<pre>";print_r($schedList);print"</pre>";
         foreach ($schedList as $StartTime=>$RoomName)
         {
         ?>
            <tr>
               <td width="5%">&nbsp;</td>
               <td width="15%"><?php print $StartTime; ?></td>
               <td><?php print $RoomName; ?></td>
            </tr>
         <?php
         }
      }
      ?>
      </table>
      <?php footer("","")?>
   </body>

</html>