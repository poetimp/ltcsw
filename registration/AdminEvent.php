<?php
include 'include/RegFunctions.php';

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

$EventName        = "";
$TeamEvent        = "";
$ConvEvent        = "";
$MinGrade         = "";
$MaxGrade         = "";
$MinSize          = "";
$MaxSize          = "";
$Sex              = "";
$JudgesNeeded     = "";
$MaxRooms         = "";
$MaxEventSlots    = "";
$MaxWebSlots      = "";
$Duration         = "";
$JudgeTrained     = "";
$TeenCoord        = "";
$EventAttended    = "";
$JudgingCatagory  = "";
$IndividualAwards = "";


if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update')
{
   $mode = 'update';
   $EventID = $_REQUEST['EventID'];
}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')
{
   $mode = 'view';
   $EventID = $_REQUEST['EventID'];
}
else //if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add')
{
   $mode = 'add';
}

$ErrorMsg = "";

if ($mode == 'update' || $mode == 'view')
{
   $result = $db->query("select *
                          from   $EventsTable
                          where  EventID ='$EventID'
                         ")
             or die ("Unable to get event information: ".sqlError());
   $row = $result->fetch(PDO::FETCH_ASSOC);

   $EventName        = isset($row['EventName'])        ? $row['EventName']        : "";
   $TeamEvent        = isset($row['TeamEvent'])        ? $row['TeamEvent']        : "";
   $ConvEvent        = isset($row['ConvEvent'])        ? $row['ConvEvent']        : "";
   $MinGrade         = isset($row['MinGrade'])         ? $row['MinGrade']         : "";
   $MaxGrade         = isset($row['MaxGrade'])         ? $row['MaxGrade']         : "";
   $MinSize          = isset($row['MinSize'])          ? $row['MinSize']          : "";
   $MaxSize          = isset($row['MaxSize'])          ? $row['MaxSize']          : "";
   $Sex              = isset($row['Sex'])              ? $row['Sex']              : "";
   $JudgesNeeded     = isset($row['JudgesNeeded'])     ? $row['JudgesNeeded']     : "";
   $MaxRooms         = isset($row['MaxRooms'])         ? $row['MaxRooms']         : "";
   $MaxEventSlots    = isset($row['MaxEventSlots'])    ? $row['MaxEventSlots']    : "";
   $MaxWebSlots      = isset($row['MaxWebSlots'])      ? $row['MaxWebSlots']      : "";
   $Duration         = isset($row['Duration'])         ? $row['Duration']         : "";
   $JudgeTrained     = isset($row['JudgeTrained'])     ? $row['JudgeTrained']     : "";
   $TeenCoord        = isset($row['TeenCoord'])        ? $row['TeenCoord']        : "";
   $EventAttended    = isset($row['EventAttended'])    ? $row['EventAttended']    : "";
   $JudgingCatagory  = isset($row['JudgingCatagory'])  ? $row['JudgingCatagory']  : "";
   $IndividualAwards = isset($row['IndividualAwards']) ? $row['IndividualAwards'] : "";
   $CoordID          = isset($row['CoordID'])          ? $row['CoordID']          : "";
}

if (isset($_POST['add']) or isset($_POST['update']))
{
   if (isset($_POST['add']))
   {
      $mode = 'add';
   }
   else
   {
      $mode = 'update';
   }

   $EventID          = isset($_POST['EventID'])          ? $_POST['EventID']          : "";
   $EventName        = isset($_POST['EventName'])        ? $_POST['EventName']        : "";
   $TeamEvent        = isset($_POST['TeamEvent'])        ? $_POST['TeamEvent']        : "";
   $ConvEvent        = isset($_POST['ConvEvent'])        ? $_POST['ConvEvent']        : "";
   $MinGrade         = isset($_POST['MinGrade'])         ? $_POST['MinGrade']         : "";
   $MaxGrade         = isset($_POST['MaxGrade'])         ? $_POST['MaxGrade']         : "";
   $MinSize          = isset($_POST['MinSize'])          ? $_POST['MinSize']          : "";
   $MaxSize          = isset($_POST['MaxSize'])          ? $_POST['MaxSize']          : "";
   $Sex              = isset($_POST['Sex'])              ? $_POST['Sex']              : "";
   $JudgesNeeded     = isset($_POST['JudgesNeeded'])     ? $_POST['JudgesNeeded']     : "";
   $MaxRooms         = isset($_POST['MaxRooms'])         ? $_POST['MaxRooms']         : "";
   $MaxEventSlots    = isset($_POST['MaxEventSlots'])    ? $_POST['MaxEventSlots']    : "";
   $MaxWebSlots      = isset($_POST['MaxWebSlots'])      ? $_POST['MaxWebSlots']      : "";
   $Duration         = isset($_POST['Duration'])         ? $_POST['Duration']         : "";
   $JudgeTrained     = isset($_POST['JudgeTrained'])     ? $_POST['JudgeTrained']     : "";
   $TeenCoord        = isset($_POST['TeenCoord'])        ? $_POST['TeenCoord']        : "";
   $EventAttended    = isset($_POST['EventAttended'])    ? $_POST['EventAttended']    : "";
   $JudgingCatagory  = isset($_POST['JudgingCatagory'])  ? $_POST['JudgingCatagory']  : "";
   $IndividualAwards = isset($_POST['IndividualAwards']) ? $_POST['IndividualAwards'] : "";
   $CoordID          = isset($_POST['CoordID'])          ? $_POST['CoordID']          : "";


   if ($TeamEvent == "N")
   {
      $MinSize = 1;
      $MaxSize = 1;
   }

   if ($EventName == "")
   {
      $ErrorMsg = "Please enter the required field: Event Name";
   }
   else if ($TeamEvent == "")
   {
      $ErrorMsg = "Please enter the required field: Team Event (Y/N)";
   }
   else if ($MinGrade == "" or $MinGrade == "0")
   {
      $ErrorMsg = "Please enter the required field: Minimum Grade";
   }
   else if ($MinSize == "")
   {
      $ErrorMsg = "Please enter the required field: Minimum Team Size";
   }
   else if ($MaxGrade == "" or $MaxGrade == "0")
   {
      $ErrorMsg = "Please enter the required field: Maximum Grade";
   }
   else if ($Duration == "" or $Duration == "0")
   {
      $ErrorMsg = "Please enter the required field: Duration";
   }
   else if ($JudgesNeeded == "")
   {
      $ErrorMsg = "Please enter the required field: Number of Judges Needed";
   }
   else if ($MaxRooms == "")
   {
      $ErrorMsg = "Please enter the required field: Max Number of rooms";
   }
   else if ($MaxWebSlots == "")
   {
      $ErrorMsg = "Please enter the required field: Max Web Slots";
   }
   else if ($MaxEventSlots == "")
   {
      $ErrorMsg = "Please enter the required field: Maximum number of slots";
   }
   else if ($MaxSize == "")
   {
      $ErrorMsg = "Please enter the required field: Maximum Team Size";
   }
   else if ($Sex == "")
   {
      $ErrorMsg = "Please enter the required field: Sex";
   }
   else if ($ConvEvent == "")
   {
      $ErrorMsg = "Please enter the required field: Convention or pre-Conventtion";
   }
   else if ($JudgeTrained == "")
   {
      $ErrorMsg = "Please enter the required field: Judge Type";
   }
   else if ($TeenCoord == "")
   {
      $ErrorMsg = "Please enter the required field: Allow Teen Coordinator";
   }
   else if ($EventAttended == "")
   {
      $ErrorMsg = "Please enter the required field: Is event attended?";
   }
   else if (!is_numeric($MaxRooms) or $MaxRooms < 0 or $MaxRooms > 9)
   {
      $ErrorMsg = "Invalid Maximum number of rooms. Must be numeric in the range 0-9";
   }
   else if (!is_numeric($MaxEventSlots) or $MaxEventSlots < 0 or $MaxEventSlots > 999)
   {
      $ErrorMsg = "Invalid Maximum Event Slots. Must be numeric in the range 0-999";
   }
   else if (!is_numeric($MaxWebSlots) or $MaxWebSlots < 0 or $MaxWebSlots > 999)
   {
      $ErrorMsg = "Invalid Maximum Web Slots. Must be numeric in the range 0-999";
   }
   else if ($MaxWebSlots > $MaxEventSlots)
   {
      $ErrorMsg = "Maximum Web Slots must be less than or equal to Maximum Event Slots";
   }
   else if (!is_numeric($MinSize) or $MinSize < 0 or $MinSize > 999)
   {
      $ErrorMsg = "Invalid Minimum Team Size. Must be numeric in the range 0-999";
   }
   else if (!is_numeric($JudgesNeeded) or $JudgesNeeded < 0 or $JudgesNeeded > 99)
   {
      $ErrorMsg = "Invalid number of judges needed. Must be numeric in the range 0-99";
   }
   else if (!is_numeric($MaxSize) or $MaxSize < 0 or $MaxSize > 999)
   {
      $ErrorMsg = "Invalid Maximum Team Size. Must be numeric in the range 0-999";
   }
   else if ($MinSize > $MaxSize)
   {
      $ErrorMsg = "Invalid Team Size. Minimum Size must be less than or equal to Maximum Size";
   }

   if ($ErrorMsg == "")
   {
      ereg_replace("'","''",$EventName);

      if ($mode == 'update')
      {
         $sql = "update $EventsTable
                    set EventName        = '$EventName'        ,
                        TeamEvent        = '$TeamEvent'        ,
                        ConvEvent        = '$ConvEvent'        ,
                        MinGrade         = '$MinGrade'         ,
                        MaxGrade         = '$MaxGrade'         ,
                        MinSize          = '$MinSize'          ,
                        MaxSize          = '$MaxSize'          ,
                        Sex              = '$Sex'              ,
                        JudgesNeeded     = '$JudgesNeeded'     ,
                        MaxRooms         = '$MaxRooms'         ,
                        MaxEventSlots    = '$MaxEventSlots'    ,
                        MaxWebSlots      = '$MaxWebSlots'      ,
                        Duration         = '$Duration'         ,
                        JudgeTrained     = '$JudgeTrained'     ,
                        TeenCoord        = '$TeenCoord'        ,
                        EventAttended    = '$EventAttended'    ,
                        JudgingCatagory  = '$JudgingCatagory'  ,
                        IndividualAwards = '$IndividualAwards' ,
                        CoordID          = '$CoordID'
                  where EventId          = $EventID
                  ";
         WriteToLog("Event $EventName Updated");
      }
      else
      {
         $sql = "insert into $EventsTable
                        (EventName       ,
                         TeamEvent       ,
                         ConvEvent       ,
                         MinGrade        ,
                         MaxGrade        ,
                         MinSize         ,
                         MaxSize         ,
                         Sex             ,
                         JudgesNeeded    ,
                         MaxRooms        ,
                         MaxEventSlots   ,
                         MaxWebSlots     ,
                         Duration        ,
                         JudgeTrained    ,
                         TeenCoord       ,
                         EventAttended   ,
                         JudgingCatagory ,
                         IndividualAwards,
                         CoordID
                         )
                 values ('$EventName'       ,
                         '$TeamEvent'       ,
                         '$ConvEvent'       ,
                         '$MinGrade'        ,
                         '$MaxGrade'        ,
                         '$MinSize'         ,
                         '$MaxSize'         ,
                         '$Sex'             ,
                         '$JudgesNeeded'    ,
                         '$MaxRooms'        ,
                         '$MaxEventSlots'   ,
                         '$MaxWebSlots'     ,
                         '$Duration'        ,
                         '$JudgeTrained'    ,
                         '$TeenCoord'       ,
                         '$EventAttended'   ,
                         '$JudgingCatagory' ,
                         '$IndividualAwards',
                         $CoordID
                         )";
         WriteToLog("Event $EventName Added");
      }
      $results = $db->query($sql) or die ("Unable to process update: " . sqlError());
   ?>
<html lang="en">

   <body style="background-color: rgb(217, 217, 255);">

   <?php
      if ($mode == 'update')
      {
         ?>
         <center>
            <h1>Event</h1>
            <h2>&quot;<?php  print $EventName; ?>&quot;<br>
            Updated!</h2>
         </center>
         <?php
      }
      else
      {
         ?>
         <center>
            <h1>Event</h1>
            <h2>&quot;<?php  print $EventName; ?>&quot;<br>
            Added!</h2>
         </center>
         <?php
      }

      ?>
      <center>
         <a href="Events.php">Return to Event List</a>
      </center>

      </body>

      </html>
      <?php
      }
   }

   if ((!isset($_POST['add']) and !isset($_POST['update'])) or $ErrorMsg != "")
   {
      ?>
<html lang="en">

      <head>
      <meta http-equiv="Content-Language" content="en-us">
      <?php
      if ($mode == 'update')
      {
         ?>
         <title>Update Event Record</title>
         <?php
      }
      else if ($mode == 'add')
      {
         ?>
         <title>Add a new Event</title>
         <?php
      }
      else
      {
         ?>
         <title>Event</title>
         <?php
      }
      ?>
      </head>

      <body style="background-color: rgb(217, 217, 255);">

      <?php
      if ($mode == 'update')
      {
         ?>
         <h1 align="center">Update Event Record</h1>
         <?php
      }
      else if ($mode == 'add')
      {
         ?>
         <h1 align="center">Add a new Event</h1>
         <?php
      }
      else
      {
         ?>
         <h1 align="center">Event</h1>
         <?php
      }

      if ($ErrorMsg != "")
      {
         print "<center><font color=\"FF0000\"><b>" . $ErrorMsg . "</b></font></center><br>";
      }
   ?>
<form method="post" action="AdminEvent.php">
	<table border="1" width="100%" id="table1">
	   <!------------------------------------------------------------------------------>
	   <!-- Row 1                                                                    -->
	   <!------------------------------------------------------------------------------>
		<tr>
         <!----------------------- Column 1 ------------------------------------------>
			<td colspan="7" bgcolor="#000000">
			<p align="center"><span style="background-color: #000000">
			<font color="#FFFF00">Event</font></span><font color="#FFFF00"><span style="background-color: #000000">
			Information</span></font></p>
			</td>
		</tr>
	   <!------------------------------------------------------------------------------>
	   <!-- Row 2                                                                    -->
	   <!------------------------------------------------------------------------------>
		<tr>
         <!----------------------- Column 1 ------------------------------------------>
			<td width="12%">Event Name</td>
         <!----------------------- Column 2-4----------------------------------------->
			<td width="38%" colspan="3">
            <?php
			   if ($mode == "view")
			   {
			      print $EventName;
			   }
			   else
			   {
			   ?>
			      <input type="text" name="EventName" size="36" <?php  print ($EventName != "") ? "value=\"" . $EventName . "\"" : ""; ?>>
			   <?php
			   }
			   ?>
			</td>

         <!----------------------- Column 5 ------------------------------------------>
			<td width="25%">Team Event</td>
			   <?php
			   if ($mode == "view")
			   {
			      ?>
               <!----------------------- Column 6-7 ------------------------------------------>
               <td width="25%" colspan="2">
               <?php
			         print $TeamEvent == "Y" ? "Yes" : "No";
			      ?>
			      </td>
			      <?php
			   }
			   else
			   {
			   ?>
               <!----------------------- Column 6 ------------------------------------------>
			      <td width="12%">
                  <input type="radio" name="TeamEvent" value="Y" <?php  print ($TeamEvent == "Y") ? "checked" : "" ?>>Yes
               </td>
               <!----------------------- Column 7 ------------------------------------------>
		         <td width="12%">
                  <input type="radio" name="TeamEvent" value="N" <?php  print ($TeamEvent == "N") ? "checked" : "" ?>>No
               </td>
			      <?php
			   }
			?>
		</tr>
	   <!------------------------------------------------------------------------------>
	   <!-- Row 3                                                                    -->
	   <!------------------------------------------------------------------------------>
		<tr>
         <!----------------------- Column 1 ------------------------------------------>
			<td width="12%">Min Grade</td>
         <!----------------------- Column 2-4----------------------------------------->
			<td width="38%" colspan="3">
   			<?php
   			if ($mode == "view")
   			{
   			   print $MinGrade;
   			}
   			else
   			{
   			?>
               <select name="MinGrade" size="1">
                  <option <?php  print ($MinGrade == "0")  ? "selected" : "" ?> value="0">Please Select</option>
                  <option <?php  print ($MinGrade == "3")  ? "selected" : "" ?>>3</option>
                  <option <?php  print ($MinGrade == "4")  ? "selected" : "" ?>>4</option>
                  <option <?php  print ($MinGrade == "5")  ? "selected" : "" ?>>5</option>
                  <option <?php  print ($MinGrade == "6")  ? "selected" : "" ?>>6</option>
                  <option <?php  print ($MinGrade == "7")  ? "selected" : "" ?>>7</option>
                  <option <?php  print ($MinGrade == "8")  ? "selected" : "" ?>>8</option>
                  <option <?php  print ($MinGrade == "9")  ? "selected" : "" ?>>9</option>
                  <option <?php  print ($MinGrade == "10") ? "selected" : "" ?>>10</option>
                  <option <?php  print ($MinGrade == "11") ? "selected" : "" ?>>11</option>
                  <option <?php  print ($MinGrade == "12") ? "selected" : "" ?>>12</option>
               </select>
   			<?php
   			}
   			?>
            </td>
         <!----------------------- Column 5 ------------------------------------------>
			<td width="25%">Minimum Team Size</td>
         <!----------------------- Column 6-7 ---------------------------------------->
			<td width="25%" colspan="2">
   			<?php
   			if ($mode == "view")
   			{
   			   print $MinSize;
   			}
   			else
   			{
   			?>
               <input type="text" name="MinSize" size="20"<?php  print ($MinSize != "") ? "value=\"" . $MinSize . "\"" : ""; ?>>
   			<?php
   			}
   			?>
         </td>
		</tr>
	   <!------------------------------------------------------------------------------>
	   <!-- Row 4                                                                    -->
	   <!------------------------------------------------------------------------------>
		<tr>
         <!----------------------- Column 1 ------------------------------------------>
			<td width="12%">Max Grade</td>
         <!----------------------- Column 2-4 ---------------------------------------->
			<td width="38%" colspan="3">
   			<?php
   			if ($mode == "view")
   			{
   			   print $MaxGrade;
   			}
   			else
   			{
   			?>
               <select name="MaxGrade" size="1">
                  <option <?php  print ($MaxGrade == "0")  ? "selected" : "" ?> value="0">Please Select</option>
                  <option <?php  print ($MaxGrade == "3")  ? "selected" : "" ?>>3</option>
                  <option <?php  print ($MaxGrade == "4")  ? "selected" : "" ?>>4</option>
                  <option <?php  print ($MaxGrade == "5")  ? "selected" : "" ?>>5</option>
                  <option <?php  print ($MaxGrade == "6")  ? "selected" : "" ?>>6</option>
                  <option <?php  print ($MaxGrade == "7")  ? "selected" : "" ?>>7</option>
                  <option <?php  print ($MaxGrade == "8")  ? "selected" : "" ?>>8</option>
                  <option <?php  print ($MaxGrade == "9")  ? "selected" : "" ?>>9</option>
                  <option <?php  print ($MaxGrade == "10") ? "selected" : "" ?>>10</option>
                  <option <?php  print ($MaxGrade == "11") ? "selected" : "" ?>>11</option>
                  <option <?php  print ($MaxGrade == "12") ? "selected" : "" ?>>12</option>
               </select>
   			<?php
   			}
   			?>
            </td>
         <!----------------------- Column 5 ------------------------------------------>
			<td width="25%">Maximum Team Size</td>
         <!----------------------- Column 6-7 ------------------------------------------>
			<td width="25%" colspan="2">
   			<?php
   			if ($mode == "view")
   			{
   			   print $MaxSize;
   			}
   			else
   			{
   			?>
               <input type="text" name="MaxSize" size="20"<?php  print ($MaxSize != "") ? "value=\"" . $MaxSize . "\"" : ""; ?>>
   			<?php
   			}
   			?>
         </td>
		</tr>
	   <!------------------------------------------------------------------------------>
	   <!-- Row 5                                                                    -->
	   <!------------------------------------------------------------------------------>
		<tr>
         <!----------------------- Column 1 ------------------------------------------>
		   <td width="13%" colspan="1">Duration</td>
         <!----------------------- Column 2-4 ---------------------------------------->
		   <td width="38%" colspan="3">
   			<?php
   			if ($mode == "view")
   			{
   			   $hours   = (int)($Duration/60);
   			   $minutes = $Duration % 60;

   			   $hrsPlural  = ($hours   == 1)                    ? "hour"              : "hours";
   			   $hours      = ($hours   > 0)                     ? "$hours $hrsPlural" : "";
   			   $minutes    = ($minutes > 0)                     ? "$minutes minutes"  : "";
   			   $and        = ($hours != "" and $minutes != "")  ? "and"               : "";

   			   print  "$hours $and $minutes &nbsp;";
   			}
   			else
   			{
   			?>
            <select name="Duration" size="1">
               <option <?php  print ($Duration == "0")  ? "selected" : "" ?> value="0">Please Select</option>
               <option <?php  print ($Duration == "30")  ? "selected" : "" ?> value="30">30 Minutes</option>
               <option <?php  print ($Duration == "40")  ? "selected" : "" ?> value="40">40 Minutes</option>
               <option <?php  print ($Duration == "50")  ? "selected" : "" ?> value="50">50 Minutes</option>
               <option <?php  print ($Duration == "60")  ? "selected" : "" ?> value="60">1 Hour</option>
               <option <?php  print ($Duration == "70")  ? "selected" : "" ?> value="70">1 hour 10 minutes</option>
               <option <?php  print ($Duration == "80")  ? "selected" : "" ?> value="80">1 hour 20 minutes</option>
               <option <?php  print ($Duration == "90")  ? "selected" : "" ?> value="90">1 hour 30 minutes</option>
               <option <?php  print ($Duration == "100") ? "selected" : "" ?> value="100">1 hour 40 minutes</option>
               <option <?php  print ($Duration == "110") ? "selected" : "" ?> value="110">1 hour 50 minutes</option>
               <option <?php  print ($Duration == "120") ? "selected" : "" ?> value="120">2 hours</option>
               <option <?php  print ($Duration == "130")  ? "selected" : "" ?> value="130">2 hour 10 minutes</option>
               <option <?php  print ($Duration == "140")  ? "selected" : "" ?> value="140">2 hour 20 minutes</option>
               <option <?php  print ($Duration == "150")  ? "selected" : "" ?> value="150">2 hour 30 minutes</option>
               <option <?php  print ($Duration == "160")  ? "selected" : "" ?> value="160">2 hour 40 minutes</option>
               <option <?php  print ($Duration == "170")  ? "selected" : "" ?> value="170">2 hour 50 minutes</option>
               <option <?php  print ($Duration == "180")  ? "selected" : "" ?> value="180">3 Hours</option>
               <option <?php  print ($Duration == "240")  ? "selected" : "" ?> value="240">4 Hours</option>
               <option <?php  print ($Duration == "300")  ? "selected" : "" ?> value="300">5 Hours</option>
               <option <?php  print ($Duration == "360")  ? "selected" : "" ?> value="360">6 Hours</option>
               <option <?php  print ($Duration == "420")  ? "selected" : "" ?> value="420">7 Hours</option>
               <option <?php  print ($Duration == "480")  ? "selected" : "" ?> value="480">8 Hours</option>
            </select>
   			<?php
   			}
   			?>
		   </td>
         <!----------------------- Column 5 ------------------------------------------>
		   <td width="25%" colspan="1">Number of Judges Needed</td>
   			<?php
   			if ($mode == "view")
   			{
   			   ?>
               <!----------------------- Column 6-7 ------------------------------------------>
   			   <td width="25%" colspan="2">
   			   <?php
   			   print "$JudgesNeeded";
   			   ?>
   			   </td>
   			   <?php
   			}
   			else
   			{
   			?>
               <!----------------------- Column 6-7 ------------------------------------------>
               <td width="25%" colspan="2">
                  <input type="text" name="JudgesNeeded" size="20"<?php  print ($JudgesNeeded != "") ? "value=\"" . $JudgesNeeded . "\"" : ""; ?>>
               </td>
   			<?php
   			}
   			?>
		</tr>
	   <!------------------------------------------------------------------------------>
	   <!-- Row 6                                                                    -->
	   <!------------------------------------------------------------------------------>
		<tr>
         <!----------------------- Column 1 ------------------------------------------>
			<td width="12%">Sex</td>
			<?php
			if ($mode == "view")
			{
   			?>
            <!----------------------- Column 2-4 ------------------------------------------>
			   <td width="38%" colspan="3">
			   <?php
			   if ($Sex == "E")
			   {
			      print "Either";
			   }
			   else if ($Sex == "M")
			   {
			      print "Male Only";
			   }
			   else
			   {
			      print "Female Only";
			   }
			   ?>
			   </td>
			   <?php
			}
			else
			{
			?>
         <!----------------------- Column 2 ------------------------------------------>
			<td width="13%"><input type="radio" name="Sex" value="E" <?php  print ($Sex == "E") ? "checked" : "" ?>>Either</td>
         <!----------------------- Column 3 ------------------------------------------>
			<td width="13%"><input type="radio" name="Sex" value="M" <?php  print ($Sex == "M") ? "checked" : "" ?>>Male</td>
         <!----------------------- Column 4 ------------------------------------------>
			<td width="12%"><input type="radio" name="Sex" value="F" <?php  print ($Sex == "F") ? "checked" : "" ?>>Female</td>
   	   <?php
   	   }
   	   ?>
         <!----------------------- Column 5 ------------------------------------------>
			<td width="25%" colspan="1">Maximum Number of rooms</td>
			<?php
   		if ($mode == "view")
   		{
   		   print "<td width=25% colspan=2>$MaxRooms</td>";
   		}
   		else
   		{
   		?>
         <!----------------------- Column 6-7 ------------------------------------------>
			<td width="25%" colspan="2"><input type="text" name="MaxRooms" size="20"<?php  print ($MaxRooms != "") ? "value=\"" . $MaxRooms . "\"" : ""; ?>></td>
 			<?php
  			}
  			?>
		</tr>
	   <!------------------------------------------------------------------------------>
	   <!-- Row 7                                                                    -->
	   <!------------------------------------------------------------------------------>
		<tr>
         <!----------------------- Column 1 ------------------------------------------>
		   <td width="13%" colspan="1">Event Type</td>
			<?php
   		if ($mode == "view")
   		{
   		   ?>
            <!----------------------- Column 2-4 ------------------------------------------>
   		   <td width="25%" colspan="3">
   		   <?php
   		   if ($ConvEvent == 'C')
   		   {
   		      print "Convention";
   		   }
   		   else
   		   {
   		      print "Pre-Convention";
   		   }
   		   ?>
   		   </td>
   		   <?php
   		}
   		else
   		{
   		?>
            <!----------------------- Column 2 ------------------------------------------>
	   	   <td width="12%" colspan="1"><input type="radio" name="ConvEvent" value="C" <?php  print ($ConvEvent == "C") ? "checked" : "" ?>>Convention</td>
            <!----------------------- Column 3-4 ------------------------------------------>
		      <td width="25%" colspan="2"><input type="radio" name="ConvEvent" value="P" <?php  print ($ConvEvent == "P") ? "checked" : "" ?>>Pre-convention</td>
 			<?php
  			}
  			?>
         <!----------------------- Column 5 ------------------------------------------>
			<td width="25%" colspan="1">Maximum time slots per room</td>
			<?php
   		if ($mode == "view")
   		{
   		   print "<td width=25% colspan=2>$MaxEventSlots</td>";
   		}
   		else
   		{
   		?>
         <!----------------------- Column 6-7 ------------------------------------------>
			<td width="25%" colspan="2"><input type="text" name="MaxEventSlots" size="20"<?php  print ($MaxEventSlots != "") ? "value=\"" . $MaxEventSlots . "\"" : ""; ?>></td>
 			<?php
  			}
  			?>
		</tr>
	   <!------------------------------------------------------------------------------>
	   <!-- Row 8                                                                    -->
	   <!------------------------------------------------------------------------------>
		<tr>
         <!----------------------- Column 1 ------------------------------------------>
		   <td width="13%" colspan="1">Judge Type</td>
			<?php
   		if ($mode == "view")
   		{
   		   ?>
            <!----------------------- Column 2-4 ------------------------------------------>
   		   <td width="25%" colspan="3">
   		   <?php
   		   if ($JudgeTrained == 'Y')
   		   {
   		      print "Must be trained for event";
   		   }
   		   else
   		   {
   		      print "No special training needed";
   		   }
   		   ?>
   		   </td>
   		   <?php
   		}
   		else
   		{
   		?>
            <!----------------------- Column 2 ------------------------------------------>
		      <td width="12%" colspan="1"><input type="radio" name="JudgeTrained" value="Y" <?php  print ($JudgeTrained == "Y") ? "checked" : "" ?>>Special Skills</td>
            <!----------------------- Column 3-4 ------------------------------------------>
		      <td width="25%" colspan="2"><input type="radio" name="JudgeTrained" value="N" <?php  print ($JudgeTrained == "N") ? "checked" : "" ?>>No Special Training needed</td>
 			<?php
  			}
  			?>
         <!----------------------- Column 5 ------------------------------------------>
		   <td width="25%" colspan="1">Maximum Web Signups Slots per room</td>
 			<?php
   		if ($mode == "view")
   		{
   		   ?>
            <!----------------------- Column 6-7 ------------------------------------------>
   		   <td width="25%" colspan="2">
   		   <?php
   		   print "$MaxWebSlots";
   		   ?>
   		   </td>
   		   <?php
   		}
   		else
   		{
   		?>
            <!----------------------- Column 6-7 ------------------------------------------>
		      <td width="25%" colspan="2">
		         <input type="text" name="MaxWebSlots" size="20"<?php  print ($MaxWebSlots != "") ? "value=\"" . $MaxWebSlots . "\"" : ""; ?>>
		      </td>
 			   <?php
  			}
  			?>
		</tr>
	   <!------------------------------------------------------------------------------>
	   <!-- Row 9                                                                    -->
	   <!------------------------------------------------------------------------------>
		<tr>
         <!----------------------- Column 1 ------------------------------------------>
		   <td width="13%" colspan="1">Judging Catagory</td>
         <!----------------------- Column 1-4 ------------------------------------------>
		   <td width="37%" colspan="3">
 			<?php
   		if ($mode == "view")
   		{
   		   print "$JudgingCatagory";
   		}
   		else
   		{
   		?>
   	      <input type="text" name="JudgingCatagory" size="36" <?php  print ($JudgingCatagory != "") ? "value=\"" . $JudgingCatagory . "\"" : ""; ?>>
   		<?php
   		}
   		?>
         </td>
         <!----------------------- Column 5 ------------------------------------------>
		   <td width="25%" colspan="1">Allow Teen Coordinators</td>
 			<?php
   		if ($mode == "view")
   		{
   		   ?>
            <!----------------------- Column 6-7 ------------------------------------------>
   		   <td width="25%" colspan="2">
   		   <?php
   		   if ($TeenCoord == "Y")
   		   {
   		      print "Yes";
   		   }
   		   else
   		   {
   		      print "No";
   		   }
            ?>
   		   </td>
   		   <?php
   		}
   		else
   		{
   		?>
            <!----------------------- Column 6 ------------------------------------------>
		   <td width="12%" colspan="1"><input type="radio" name="TeenCoord" value="Y" <?php  print ($TeenCoord == "Y") ? "checked" : "" ?>>Yes</td>
            <!----------------------- Column 7 ------------------------------------------>
		   <td width="13%" colspan="1"><input type="radio" name="TeenCoord" value="N" <?php  print ($TeenCoord == "N") ? "checked" : "" ?>>No</td>
 			<?php
  			}
  			?>
		</tr>
	   <!------------------------------------------------------------------------------>
	   <!-- Row 10                                                                    -->
	   <!------------------------------------------------------------------------------>
		<tr>
         <?php
         if ($mode == "view")
         {
            ?>
            <!----------------------- Column 1-4 ------------------------------------------>
            <td width="25%" colspan="4">
            <?php
            if ($IndividualAwards == 'Y')
            {
               print "Both Team and Individual awards given";
            }
            else
            {
               print "Only Team awards are given";
            }
            ?>
            </td>
            <?php
         }
         else
         {
         ?>
         <!----------------------- Column 1 ------------------------------------------>
            <td width="13%" colspan="1">Individual Awards?</td>
            <!----------------------- Column 2 ------------------------------------------>
            <td width="12%" colspan="1"><input type="radio" name="IndividualAwards" value="Y" <?php  print ($IndividualAwards == "Y") ? "checked" : "" ?>>Yes</td>
            <!----------------------- Column 3-4 ------------------------------------------>
            <td width="25%" colspan="2"><input type="radio" name="IndividualAwards" value="N" <?php  print ($IndividualAwards == "N") ? "checked" : "" ?>>No</td>
         <!----------------------- Column 5 ------------------------------------------>
         <?php
         }
         ?>
		   <td width="25%" colspan="1">Is this an attended event?</td>
 			<?php
   		if ($mode == "view")
   		{
            ?>
            <!----------------------- Column 6-7 ------------------------------------------>
   		   <td width="25%" colspan="2">
   		   <?php
   		   if ($EventAttended == "Y")
   		   {
   		      print "Yes";
   		   }
   		   else
   		   {
   		      print "No";
   		   }
            ?>
   		   </td>
   		   <?php
   		}
   		else
   		{
   		?>
         <!----------------------- Column 6 ------------------------------------------>
		   <td width="12%" colspan="1"><input type="radio" name="EventAttended" value="Y" <?php  print ($EventAttended == "Y") ? "checked" : "" ?>>Yes</td>
         <!----------------------- Column 7 ------------------------------------------>
		   <td width="25%" colspan="2"><input type="radio" name="EventAttended" value="N" <?php  print ($EventAttended == "N") ? "checked" : "" ?>>No</td>
 			<?php
  			}
  			?>
      <!------------------------------------------------------------------------------>
      <!-- Row 11                                                                    -->
      <!------------------------------------------------------------------------------>
      </tr><tr>
         <?php
         if ($mode == "view")
         {
            ?>
            <!----------------------- Column 1-4 ------------------------------------------>
            <td width="25%" colspan="4">
            <?php
               if ($CoordID != "")
               {
                  $results = $db->query("select   Name
                                 from     $EventCoordTable
                                 where    CoordID=$CoordID")
                  or die ("Unable to get coordinator:" . sqlError());

                  $row = $results->fetch(PDO::FETCH_ASSOC);
                  $CoordName = $row['Name'];
               }
               else
                  $CoordName="-Nobody-";

               print "Event Coordinator Name: $CoordName";
               print "<td colspan=3>&nbsp;</td>";
            ?>
            </td>
         </tr>
         <?php
         }
         else
         {
         ?>
         <!----------------------- Column 1 ------------------------------------------>
            <td width="13%" colspan="1">Event Coordinator?</td>
            <!----------------------- Column 2 ------------------------------------------>
            <td width="12%" colspan="1">
               <select name="CoordID">
                  <option value="">-Select-</option>
                  <?php
                     $results = $db->query("select   Name,
                                                      CoordID
                                    from     $EventCoordTable
                                    order by Name")
                     or die ("Unable to get coordinator list:" . sqlError());

                     while ($row = $results->fetch(PDO::FETCH_ASSOC))
                     {
                        $CoordName    = $row['Name'];
                        $CoordIDstr   = $row['CoordID'];
                        if ($CoordID == $CoordIDstr)
                           $sel="Selected";
                        else
                           $sel = "";

                        print "<option $sel value=\"$CoordIDstr\">$CoordName</option>";
                     }
                  ?>
               </select>
            </td>
            <!----------------------- Column 3-4 ------------------------------------------>
            <td width="25%" colspan="5">&nbsp;</td>
         <!----------------------- Column 5 ------------------------------------------>
         <?php
         }
         ?>
		<tr></tr>
	</table>
	<p align="center"><?php
            if ($mode == 'update')
            {?> <input type="submit" value="Update" name="update">
	            <input type="hidden" value="&lt;?php  print $EventID; ?&gt;" name="EventID">
	            <input type="hidden" value="update" name="action"><?php
            }
            else if ($mode == 'add')
            {?> <input type="submit" value="Add" name="add">
             	<input type="hidden" value="add" name="action"><?php
            }
            else if ($mode == 'view')
            {?>
	            <input type="hidden" value="update" name="action"><?php
            }
         ?>
         <br>
      </p>
</form>
<?php footer("Return to Event List","Events.php")?>
&nbsp;

</body>

</html>
<?php
}