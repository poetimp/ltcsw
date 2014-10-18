<?php
include 'include/RegFunctions.php';

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}
//------------------------------------------------------------------------------
// Get the event details
//------------------------------------------------------------------------------
$EventID = $_REQUEST['EventID'];

$result     = mysql_query("select EventName
                           from   $EventsTable
                           where EventID='$EventID'")
              or die ("Unable to get event information: ".mysql_error());
$row        = mysql_fetch_assoc($result);
$EventName  = isset($row['EventName'])  ? $row['EventName']  : "";

//------------------------------------------------------------------------------
// If reset was pressed clear all selections
//------------------------------------------------------------------------------

if (isset($_POST['Reset']))
{
   mysql_query("delete from $ScheduleTable where EventID='$EventID'")
         or die ("Unable to delete existing schedule: ".mysql_error());
   $message    = "Schedule Reset";
}
//------------------------------------------------------------------------------
// If update was pressed apply changes
//------------------------------------------------------------------------------
else if (isset($_POST['Update']))
{
   mysql_query("delete from $ScheduleTable where EventID='$EventID'")
         or die ("Unable to delete existing schedule: ".mysql_error());

   foreach (array_keys($_POST) as $keyValue)
   {
      if (ereg("^[FS][0-9]{2}$",$keyValue))
      {
         $DisplayText       = substr($_POST[$keyValue],2);
         $SchedID           = $keyValue.substr($_POST[$keyValue],0,2);
         $RoomID            = implode(',',$_POST['Room_'.$keyValue]);
         $checked[$SchedID] = 1;
         mysql_query("insert into $ScheduleTable
                            (EventId    ,
                             SchedID    ,
                             DisplayText,
                             RoomID
                            )
                     values ('$EventID'     ,
                             '$SchedID'     ,
                             '$DisplayText' ,
                             '$RoomID'
                             )
                     ")
         or die ("Unable to Add Schedule: ".mysql_error());
      }
   }
   $message = "Updated";
}
//------------------------------------------------------------------------------
// If not reset and not update display current schedule
//------------------------------------------------------------------------------
else
{
   $result     = mysql_query("select SchedID,
                                     RoomID
                              from   $ScheduleTable
                              where EventID='$EventID'")
                 or die ("Unable to get event information: ".mysql_error());
   while ($row = mysql_fetch_assoc($result))
   {
      $checked[$row['SchedID']] = 1;
   }
}
//-----------------------------------------------------------------------------
// print the drop-down selection list for room selection
//-----------------------------------------------------------------------------
function selectRoom($SchedID)
{
   //-----------------------------------------------------------------------------
   // Retrieve a list of all avaliable rooms
   //-----------------------------------------------------------------------------
   $roomList  = getRoomList();
   global $ScheduleTable;
   global $EventID;
   //-----------------------------------------------------------------------------
   // Check to see if scheduled event has room assigned
   //-----------------------------------------------------------------------------
   $roomKey   = substr($SchedID,0,3).'%';
   $result    = mysql_query("select RoomID
                             from   $ScheduleTable
                             where  SchedID like '$roomKey'
                             and    EventID = $EventID
                            ")
              or die ("Unable to get room ID: ".mysql_error());

   if (mysql_num_rows($result) > 0)
   {
      $row    = mysql_fetch_assoc($result);
      $roomIDList = explode(',',$row['RoomID']);
   }
   else
      $roomIDList = array(0);

   //-----------------------------------------------------------------------------
   // Set Row background Color
   //-----------------------------------------------------------------------------
   $hour      = substr($SchedID,1,2);
   if ($hour % 2 == 0)
      $bgcolor="#6699FF";
   else
      $bgcolor="#D9D9FF";

   //-----------------------------------------------------------------------------
   // Print the selection Drop-Down
   //-----------------------------------------------------------------------------
   $fieldName = 'Room_'.substr($SchedID,0,3).'[]';
   print "<td bgcolor=\"$bgcolor\" align=\"center\">";
   print    "<select multiple size=\"4\" name=\"$fieldName\">";
   foreach ($roomList as $roomNum => $roomName)
   {
      if (in_array($roomNum,$roomIDList))
         print "<option selected value=\"$roomNum\">$roomName</option>";
   }
   foreach ($roomList as $roomNum => $roomName)
   {
      if (!in_array($roomNum,$roomIDList))
         print "<option value=\"$roomNum\">$roomName</option>";
   }
   print    "</select>";
   print "</td>";
}
//-----------------------------------------------------------------------------
// Print appropreatly shaded and highlighted line to select start time
//-----------------------------------------------------------------------------

function timeLine($SchedID,$DisplayText,$checked)
{
   $fieldName = substr($SchedID,0,3);
   $hour      = substr($SchedID,1,2);
   $minute    = substr($SchedID,3,2);

   if ($hour % 2 == 0)
      $bgcolor="#6699FF";
   else
      $bgcolor="#D9D9FF";


   print "<td align=\"center\" bgcolor=";
   if (isset($checked[$SchedID]))
      print "\"#FF00FF\">";
   else
      print "\"$bgcolor\">";

   print "<input type=\"radio\" value=\"$minute$DisplayText\" name=\"$fieldName\"";
   if (isset($checked[$SchedID]))
      print " checked>";
   else
      print ">";
   print "</td>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

   <head>
      <meta http-equiv="Content-Language" content="en-us">
      <title>
         Schedule Events
      </title>
      <h1 align="center">
         Enter the schedule for event:
      </h1>
      <h2 align="center">
         <?php  print $EventName?>
      </h2>
      <?php
      if (isset($message))
      {
         print "<center><font color=\"FF0000\"><b>" . $message . "</b></font></center><br>";
      }
      ?>
      <center>
         Select the start times for the event
      </center>
   </head>

   <body style="background-color: rgb(217, 217, 255); text-align:center">
      <form method="post">
         <table border="1" width="100%" id="table1">
            <tr>
               <td colspan="8" align="center" bgcolor="#000000">
               <font color="#FFFF00"><b>Friday</b></font></td>
            </tr>
            <tr>
               <td width="13%" bgcolor="#000000">&nbsp;</td>
               <td width="21%" align="center" bgcolor="#000000">
                  <b>
                     <font color="#FFFF00">
                        Room
                     </font>
                  </b>
               </td>
               <td width="11%" align="center" bgcolor="#9966FF"><b>:00</b></td>
               <td width="11%" align="center" bgcolor="#9966FF"><b>:10</b></td>
               <td width="11%" align="center" bgcolor="#9966FF"><b>:20</b></td>
               <td width="11%" align="center" bgcolor="#9966FF"><b>:30</b></td>
               <td width="11%" align="center" bgcolor="#9966FF"><b>:40</b></td>
               <td width="11%" align="center" bgcolor="#9966FF"><b>:50</b></td>
            </tr>
            <tr>
               <td bgcolor="#D9D9FF" align="center">3 pm</td>
               <?php
                  selectRoom('F1500');
                  timeLine('F1500','Friday 3:00pm',$checked);
                  timeLine('F1510','Friday 3:10pm',$checked);
                  timeLine('F1520','Friday 3:20pm',$checked);
                  timeLine('F1530','Friday 3:30pm',$checked);
                  timeLine('F1540','Friday 3:40pm',$checked);
                  timeLine('F1550','Friday 3:50pm',$checked);
               ?>
            </tr>
            <tr>
               <td bgcolor="#6699FF" align="center">4 pm</td>
               <?php
                  selectRoom('F1600');
                  timeLine('F1600','Friday 4:00pm',$checked);
                  timeLine('F1610','Friday 4:10pm',$checked);
                  timeLine('F1620','Friday 4:20pm',$checked);
                  timeLine('F1630','Friday 4:30pm',$checked);
                  timeLine('F1640','Friday 4:40pm',$checked);
                  timeLine('F1650','Friday 4:50pm',$checked);
               ?>
            </tr>
            <tr>
               <td bgcolor="#D9D9FF" align="center">5 pm</td>
               <?php
                  selectRoom('F1700');
                  timeLine('F1700','Friday 5:00pm',$checked);
                  timeLine('F1710','Friday 5:10pm',$checked);
                  timeLine('F1720','Friday 5:20pm',$checked);
                  timeLine('F1730','Friday 5:30pm',$checked);
                  timeLine('F1740','Friday 5:40pm',$checked);
                  timeLine('F1750','Friday 5:50pm',$checked);
               ?>
            </tr>
            <tr>
               <td bgcolor="#6699FF" align="center">6 pm</td>
               <?php
                  selectRoom('F1800');
                  timeLine('F1800','Friday 6:00pm',$checked);
                  timeLine('F1810','Friday 6:10pm',$checked);
                  timeLine('F1820','Friday 6:20pm',$checked);
                  timeLine('F1830','Friday 6:30pm',$checked);
                  timeLine('F1840','Friday 6:40pm',$checked);
                  timeLine('F1850','Friday 6:50pm',$checked);
               ?>
            </tr>
            <tr>
               <td bgcolor="#D9D9FF" align="center">7 pm</td>
               <?php
                  selectRoom('F1900');
                  timeLine('F1900','Friday 7:00pm',$checked);
                  timeLine('F1910','Friday 7:10pm',$checked);
                  timeLine('F1920','Friday 7:20pm',$checked);
                  timeLine('F1930','Friday 7:30pm',$checked);
                  timeLine('F1940','Friday 7:40pm',$checked);
                  timeLine('F1950','Friday 7:50pm',$checked);
               ?>
            </tr>
            <tr>
               <td bgcolor="#6699FF" align="center">8 pm</td>
               <?php
                  selectRoom('F2000');
                  timeLine('F2000','Friday 8:00pm',$checked);
                  timeLine('F2010','Friday 8:10pm',$checked);
                  timeLine('F2020','Friday 8:20pm',$checked);
                  timeLine('F2030','Friday 8:30pm',$checked);
                  timeLine('F2040','Friday 8:40pm',$checked);
                  timeLine('F2050','Friday 8:50pm',$checked);
               ?>
            </tr>
         </table>
         <br>
         <table border="1" width="100%" id="table1">

            <tr>
               <td colspan="8" align="center" bgcolor="#000000">
               <font color="#FFFF00"><b>Saturday</b></font></td>
            </tr>
            <tr>
               <td width="13%" bgcolor="#000000">&nbsp;</td>
               <td width="21%" align="center" bgcolor="#000000">
                  <b>
                     <font color="#FFFF00">
                        Room
                     </font>
                  </b>
               </td>
               <td width="11%" align="center" bgcolor="#9966FF"><b>:00</b></td>
               <td width="11%" align="center" bgcolor="#9966FF"><b>:10</b></td>
               <td width="11%" align="center" bgcolor="#9966FF"><b>:20</b></td>
               <td width="11%" align="center" bgcolor="#9966FF"><b>:30</b></td>
               <td width="11%" align="center" bgcolor="#9966FF"><b>:40</b></td>
               <td width="11%" align="center" bgcolor="#9966FF"><b>:50</b></td>
            </tr>
            <tr>
               <td bgcolor="#6699FF" align="center">8 am</td>
               <?php
                  selectRoom('S0800');
                  timeLine('S0800','Saturday 8:00am',$checked);
                  timeLine('S0810','Saturday 8:10am',$checked);
                  timeLine('S0820','Saturday 8:20am',$checked);
                  timeLine('S0830','Saturday 8:30am',$checked);
                  timeLine('S0840','Saturday 8:40am',$checked);
                  timeLine('S0850','Saturday 8:50am',$checked);
               ?>
            </tr>
            <tr>
               <td bgcolor="#D9D9FF" align="center">9 am</td>
               <?php
                  selectRoom('S0900');
                  timeLine('S0900','Saturday 9:00am',$checked);
                  timeLine('S0910','Saturday 9:10am',$checked);
                  timeLine('S0920','Saturday 9:20am',$checked);
                  timeLine('S0930','Saturday 9:30am',$checked);
                  timeLine('S0940','Saturday 9:40am',$checked);
                  timeLine('S0950','Saturday 9:50am',$checked);
               ?>
            </tr>
            <tr>
               <td bgcolor="#6699FF" align="center">10 am</td>
               <?php
                  selectRoom('S1000');
                  timeLine('S1000','Saturday 10:00am',$checked);
                  timeLine('S1010','Saturday 10:10am',$checked);
                  timeLine('S1020','Saturday 10:20am',$checked);
                  timeLine('S1030','Saturday 10:30am',$checked);
                  timeLine('S1040','Saturday 10:40am',$checked);
                  timeLine('S1050','Saturday 10:50am',$checked);
               ?>
            </tr>
            <tr>
               <td bgcolor="#D9D9FF" align="center">11 am</td>
               <?php
                  selectRoom('S1100');
                  timeLine('S1100','Saturday 11:00am',$checked);
                  timeLine('S1110','Saturday 11:10am',$checked);
                  timeLine('S1120','Saturday 11:20am',$checked);
                  timeLine('S1130','Saturday 11:30am',$checked);
                  timeLine('S1140','Saturday 11:40am',$checked);
                  timeLine('S1150','Saturday 11:50am',$checked);
               ?>
            </tr>
            <tr>
               <td bgcolor="#6699FF" align="center">12 pm</td>
               <?php
                  selectRoom('S1200');
                  timeLine('S1200','Saturday 12:00pm',$checked);
                  timeLine('S1210','Saturday 12:10pm',$checked);
                  timeLine('S1220','Saturday 12:20pm',$checked);
                  timeLine('S1230','Saturday 12:30pm',$checked);
                  timeLine('S1240','Saturday 12:40pm',$checked);
                  timeLine('S1250','Saturday 12:50pm',$checked);
               ?>
            </tr>
            <tr>
               <td bgcolor="#D9D9FF" align="center">1 pm</td>
               <?php
                  selectRoom('S1300');
                  timeLine('S1300','Saturday 1:00pm',$checked);
                  timeLine('S1310','Saturday 1:10pm',$checked);
                  timeLine('S1320','Saturday 1:20pm',$checked);
                  timeLine('S1330','Saturday 1:30pm',$checked);
                  timeLine('S1340','Saturday 1:40pm',$checked);
                  timeLine('S1350','Saturday 1:50pm',$checked);
               ?>
            </tr>
            <tr>
               <td bgcolor="#6699FF" align="center">2 pm</td>
               <?php
                  selectRoom('S1400');
                  timeLine('S1400','Saturday 2:00pm',$checked);
                  timeLine('S1410','Saturday 2:10pm',$checked);
                  timeLine('S1420','Saturday 2:20pm',$checked);
                  timeLine('S1430','Saturday 2:30pm',$checked);
                  timeLine('S1440','Saturday 2:40pm',$checked);
                  timeLine('S1450','Saturday 2:50pm',$checked);
               ?>
            </tr>
            <tr>
               <td bgcolor="#D9D9FF" align="center">3 pm</td>
               <?php
                  selectRoom('S1500');
                  timeLine('S1500','Saturday 3:00pm',$checked);
                  timeLine('S1510','Saturday 3:10pm',$checked);
                  timeLine('S1520','Saturday 3:20pm',$checked);
                  timeLine('S1530','Saturday 3:30pm',$checked);
                  timeLine('S1540','Saturday 3:40pm',$checked);
                  timeLine('S1550','Saturday 3:50pm',$checked);
               ?>
            </tr>
            <tr>
               <td bgcolor="#6699FF" align="center">4 pm</td>
               <?php
                  selectRoom('S1600');
                  timeLine('S1600','Saturday 4:00pm',$checked);
                  timeLine('S1610','Saturday 4:10pm',$checked);
                  timeLine('S1620','Saturday 4:20pm',$checked);
                  timeLine('S1630','Saturday 4:30pm',$checked);
                  timeLine('S1640','Saturday 4:40pm',$checked);
                  timeLine('S1650','Saturday 4:50pm',$checked);
               ?>
            </tr>
            </table>
         <input type="submit" value="Update" name="Update">
         <input type="submit" value="Clear Schedule" name="Reset">
      </form>
      <?php footer("Return to Event Selection","AdminSchedule.php")?>
   </body>

</html>