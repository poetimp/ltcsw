<?php
include 'include/RegFunctions.php';

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

$CoordName        = "";
$CoordAddr        = "";
$CoordCity        = "";
$CoordState       = "";
$CoordZip         = "";
$CoordPhone       = "";
$CoordEmail       = "";

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update')
{
   $mode = 'update';
   $CoordID = $_REQUEST['CoordID'];
}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')
{
   $mode = 'view';
   $CoordID = $_REQUEST['CoordID'];
}
else //if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add')
{
   $mode = 'add';
}

$ErrorMsg = "";

if ($mode == 'update' || $mode == 'view')
{
   $result = mysql_query("select *
                          from   $EventCoordTable
                          where  CoordID ='$CoordID'
                         ")
             or die ("Unable to get coordinator information: ".mysql_error());
   $row = mysql_fetch_assoc($result);

   $CoordID          = isset($row['CoordID'])     ? $row['CoordID']     : "";
   $CoordName        = isset($row['Name'])        ? $row['Name']        : "";
   $CoordAddr        = isset($row['Address'])     ? $row['Address']     : "";
   $CoordCity        = isset($row['City'])        ? $row['City']        : "";
   $CoordState       = isset($row['State'])       ? $row['State']       : "";
   $CoordZip         = isset($row['Zip'])         ? $row['Zip']         : "";
   $CoordPhone       = isset($row['Phone'])       ? $row['Phone']       : "";
   $CoordEmail       = isset($row['Email'])       ? $row['Email']       : "";
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

   $CoordID          = isset($_POST['CoordID'])     ? $_POST['CoordID']     : "";
   $CoordName        = isset($_POST['CoordName'])   ? $_POST['CoordName']   : "";
   $CoordAddr        = isset($_POST['CoordAddr'])   ? $_POST['CoordAddr']   : "";
   $CoordCity        = isset($_POST['CoordCity'])   ? $_POST['CoordCity']   : "";
   $CoordState       = isset($_POST['CoordState'])  ? $_POST['CoordState']  : "";
   $CoordZip         = isset($_POST['CoordZip'])    ? $_POST['CoordZip']    : "";
   $CoordPhone       = isset($_POST['CoordPhone'])  ? $_POST['CoordPhone']  : "";
   $CoordEmail       = isset($_POST['CoordEmail'])  ? $_POST['CoordEmail']  : "";

   if ($CoordName == "")
   {
      $ErrorMsg = "Please enter the required field: Coordinator Name";
   }
   else if ($CoordAddr == "")
   {
      $ErrorMsg = "Please enter the required field: Coordinator Address";
   }
   else if ($CoordCity == "")
   {
      $ErrorMsg = "Please enter the required field: Coordinator City";
   }
   else if ($CoordState == "" or $CoordState == "0")
   {
      $ErrorMsg = "Please enter the required field: Coordinator State";
   }
   else if ($CoordZip == "")
   {
      $ErrorMsg = "Please enter the required field: Coordinator Zipcode";
   }
   else if ($CoordPhone == "")
   {
      $ErrorMsg = "Please enter the required field: Coordinator Phone Number";
   }
   else if ($CoordEmail == "")
   {
      $ErrorMsg = "Please enter the required field: Coordinator Email Address";
   }
   else if (!ereg("^[0-9]{5}$",$CoordZip) and !ereg("^[0-9]{5}-[0-9]{4}$",$CoordZip))
   {
      $ErrorMsg = "Invalid Zip Code specified. Must be in the format: ##### or #####-####";
   }
   else if (!ereg("^\([0-9]{3}\) [0-9]{3}-[0-9]{4}$",$CoordPhone))
   {
      $ErrorMsg = "Invalid Phone number specified. Must be in the format: (###) ###-####";
   }

   if ($ErrorMsg == "")
   {
      ereg_replace("'","''",$CoordName);
      ereg_replace("'","''",$CoordAddr);
      ereg_replace("'","''",$CoordCity);
      ereg_replace("'","''",$CoordState);
      ereg_replace("'","''",$CoordZip);
      ereg_replace("'","''",$CoordPhone);
      ereg_replace("'","''",$CoordEmail);

      if ($mode == 'update')
      {
         $sql = "update $EventCoordTable
                    set Name        = '$CoordName'        ,
                        Address     = '$CoordAddr'        ,
                        City        = '$CoordCity'        ,
                        State       = '$CoordState'       ,
                        Zip         = '$CoordZip'         ,
                        Phone       = '$CoordPhone'       ,
                        Email       = '$CoordEmail'
                  where CoordId     = '$CoordID'
                  ";
         WriteToLog("Coordinator $CoordName Updated");
      }
      else
      {
         $sql = "insert into $EventCoordTable
                        (Name       ,
                         Address    ,
                         City       ,
                         State      ,
                         Zip        ,
                         Phone      ,
                         Email
                         )
                 values ('$CoordName'       ,
                         '$CoordAddr'       ,
                         '$CoordCity'       ,
                         '$CoordState'      ,
                         '$CoordZip'        ,
                         '$CoordPhone'      ,
                         '$CoordEmail'
                         )";
         WriteToLog("Coordinator $CoordName Added");
      }
      $results = mysql_query($sql) or die ("Unable to process update: " . mysql_error());
   ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

   <body style="background-color: rgb(217, 217, 255);">

   <?php
      if ($mode == 'update')
      {
         ?>
         <center>
            <h1>Coordinator</h1>
            <h2>&quot;<?php  print $CoordName; ?>&quot;<br>
            Updated!</h2>
         </center>
         <?php
      }
      else
      {
         ?>
         <center>
            <h1>Coordinator</h1>
            <h2>&quot;<?php  print $CoordName; ?>&quot;<br>
            Added!</h2>
         </center>
         <?php
      }

      ?>
      <center>
         <a href="Coordinators.php">Return to Coordinator List</a>
      </center>

      </body>

      </html>
      <?php
      }
   }

   if ((!isset($_POST['add']) and !isset($_POST['update'])) or $ErrorMsg != "")
   {
      ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

      <head>
      <meta http-equiv="Content-Language" content="en-us">
      <?php
      if ($mode == 'update')
      {
         ?>
         <title>Update Coordinator Record</title>
         <?php
      }
      else if ($mode == 'add')
      {
         ?>
         <title>Add a new Coordinator</title>
         <?php
      }
      else
      {
         ?>
         <title>Coordinator</title>
         <?php
      }
      ?>
      </head>

      <body style="background-color: rgb(217, 217, 255);">

      <?php
      if ($mode == 'update')
      {
         ?>
         <h1 align="center">Update Coordinator Record</h1>
         <?php
      }
      else if ($mode == 'add')
      {
         ?>
         <h1 align="center">Add a new Coordinator</h1>
         <?php
      }
      else
      {
         ?>
         <h1 align="center">Coordinator</h1>
         <?php
      }

      if ($ErrorMsg != "")
      {
         print "<center><font color=\"FF0000\"><b>" . $ErrorMsg . "</b></font></center><br>";
      }
   ?>
<form method="post" action="AdminEventCoord.php">
	<table border="1" width="100%" id="table1">
	   <!------------------------------------------------------------------------------>
	   <!-- Row 1                                                                   -->
	   <!------------------------------------------------------------------------------>
		<tr>
      <!----------------------- Column 1-7 ------------------------------------------>
			<td colspan="7" align="center" bgcolor="#000000">
			<font color="#FFFF00">Coordinator Information</font></td>
		</tr>
	   <!------------------------------------------------------------------------------>
	   <!-- Row 2                                                                   -->
	   <!------------------------------------------------------------------------------>
		<tr>
         <!----------------------- Column 1 ------------------------------------------>
 			<td width="12%">Name</td>
         <!----------------------- Column 2-7 ------------------------------------------>
			<td width="88%" colspan="6">
 			<?php
			if ($mode == "view")
			{
			   print $CoordName;
			}
			else
			{
			?>
			   <input type="text" name="CoordName" size="36" <?php  print ($CoordName != "") ? "value=\"" . $CoordName . "\"" : ""; ?>>
 			<?php
  			}
  			?>
			</td>
		</tr>
	   <!------------------------------------------------------------------------------>
	   <!-- Row 3                                                                   -->
	   <!------------------------------------------------------------------------------>
		<tr>
         <!----------------------- Column 1 ------------------------------------------>
			<td width="12%">Address</td>
         <!----------------------- Column 2-7 ------------------------------------------>
			<td width="88%" colspan="6">
 			<?php
			if ($mode == "view")
			{
			   print $CoordAddr;
			}
			else
			{
			?>
			   <input type="text" name="CoordAddr" size="36" <?php  print ($CoordAddr != "") ? "value=\"" . $CoordAddr . "\"" : ""; ?>>
 			<?php
  			}
  			?>
			</td>
		</tr>
	   <!------------------------------------------------------------------------------>
	   <!-- Row 4                                                                   -->
	   <!------------------------------------------------------------------------------>
		<tr>
         <!----------------------- Column 1 ------------------------------------------>
			<td width="12%">City</td>
         <!----------------------- Column 2-4 ------------------------------------------>
			<td width="38%" colspan="3">
 			<?php
			if ($mode == "view")
			{
			   print $CoordCity;
			}
			else
			{
			?>
			   <input type="text" name="CoordCity" size="36" <?php  print ($CoordCity != "") ? "value=\"" . $CoordCity . "\"" : ""; ?>>
 			<?php
  			}
  			?>
			</td>
         <!----------------------- Column 5 ------------------------------------------>
			<td width="25%">State</td>
         <!----------------------- Column 6-7 ------------------------------------------>
			<td width="25%" colspan="2">
 			<?php
			if ($mode == "view")
			{
			   print $CoordState;
			}
			else
			{
			?>
			   <select size="1" name="CoordState">
                  <option <?php  print ($CoordState == "0")  ? "selected" : "" ?> value="0">Please Select</option>
                  <option <?php  print ($CoordState == "AK") ? "selected" : "" ?>>AK</option>
                  <option <?php  print ($CoordState == "AL") ? "selected" : "" ?>>AL</option>
                  <option <?php  print ($CoordState == "AK") ? "selected" : "" ?>>AR</option>
                  <option <?php  print ($CoordState == "AS") ? "selected" : "" ?>>AS</option>
                  <option <?php  print ($CoordState == "AZ") ? "selected" : "" ?>>AZ</option>
                  <option <?php  print ($CoordState == "CA") ? "selected" : "" ?>>CA</option>
                  <option <?php  print ($CoordState == "CO") ? "selected" : "" ?>>CO</option>
                  <option <?php  print ($CoordState == "CT") ? "selected" : "" ?>>CT</option>
                  <option <?php  print ($CoordState == "DC") ? "selected" : "" ?>>DC</option>
                  <option <?php  print ($CoordState == "DE") ? "selected" : "" ?>>DE</option>
                  <option <?php  print ($CoordState == "FL") ? "selected" : "" ?>>FL</option>
                  <option <?php  print ($CoordState == "FM") ? "selected" : "" ?>>FM</option>
                  <option <?php  print ($CoordState == "GA") ? "selected" : "" ?>>GA</option>
                  <option <?php  print ($CoordState == "GU") ? "selected" : "" ?>>GU</option>
                  <option <?php  print ($CoordState == "HI") ? "selected" : "" ?>>HI</option>
                  <option <?php  print ($CoordState == "IA") ? "selected" : "" ?>>IA</option>
                  <option <?php  print ($CoordState == "ID") ? "selected" : "" ?>>ID</option>
                  <option <?php  print ($CoordState == "IL") ? "selected" : "" ?>>IL</option>
                  <option <?php  print ($CoordState == "IN") ? "selected" : "" ?>>IN</option>
                  <option <?php  print ($CoordState == "KS") ? "selected" : "" ?>>KS</option>
                  <option <?php  print ($CoordState == "KY") ? "selected" : "" ?>>KY</option>
                  <option <?php  print ($CoordState == "LS") ? "selected" : "" ?>>LA</option>
                  <option <?php  print ($CoordState == "MA") ? "selected" : "" ?>>MA</option>
                  <option <?php  print ($CoordState == "MD") ? "selected" : "" ?>>MD</option>
                  <option <?php  print ($CoordState == "ME") ? "selected" : "" ?>>ME</option>
                  <option <?php  print ($CoordState == "MH") ? "selected" : "" ?>>MH</option>
                  <option <?php  print ($CoordState == "MI") ? "selected" : "" ?>>MI</option>
                  <option <?php  print ($CoordState == "MN") ? "selected" : "" ?>>MN</option>
                  <option <?php  print ($CoordState == "MO") ? "selected" : "" ?>>MO</option>
                  <option <?php  print ($CoordState == "MP") ? "selected" : "" ?>>MP</option>
                  <option <?php  print ($CoordState == "MS") ? "selected" : "" ?>>MS</option>
                  <option <?php  print ($CoordState == "MT") ? "selected" : "" ?>>MT</option>
                  <option <?php  print ($CoordState == "NC") ? "selected" : "" ?>>NC</option>
                  <option <?php  print ($CoordState == "ND") ? "selected" : "" ?>>ND</option>
                  <option <?php  print ($CoordState == "NE") ? "selected" : "" ?>>NE</option>
                  <option <?php  print ($CoordState == "NH") ? "selected" : "" ?>>NH</option>
                  <option <?php  print ($CoordState == "NJ") ? "selected" : "" ?>>NJ</option>
                  <option <?php  print ($CoordState == "NM") ? "selected" : "" ?>>NM</option>
                  <option <?php  print ($CoordState == "NV") ? "selected" : "" ?>>NV</option>
                  <option <?php  print ($CoordState == "NY") ? "selected" : "" ?>>NY</option>
                  <option <?php  print ($CoordState == "OH") ? "selected" : "" ?>>OH</option>
                  <option <?php  print ($CoordState == "OK") ? "selected" : "" ?>>OK</option>
                  <option <?php  print ($CoordState == "OR") ? "selected" : "" ?>>OR</option>
                  <option <?php  print ($CoordState == "PA") ? "selected" : "" ?>>PA</option>
                  <option <?php  print ($CoordState == "PR") ? "selected" : "" ?>>PR</option>
                  <option <?php  print ($CoordState == "PW") ? "selected" : "" ?>>PW</option>
                  <option <?php  print ($CoordState == "RI") ? "selected" : "" ?>>RI</option>
                  <option <?php  print ($CoordState == "SC") ? "selected" : "" ?>>SC</option>
                  <option <?php  print ($CoordState == "SD") ? "selected" : "" ?>>SD</option>
                  <option <?php  print ($CoordState == "TN") ? "selected" : "" ?>>TN</option>
                  <option <?php  print ($CoordState == "TX") ? "selected" : "" ?>>TX</option>
                  <option <?php  print ($CoordState == "UT") ? "selected" : "" ?>>UT</option>
                  <option <?php  print ($CoordState == "VA") ? "selected" : "" ?>>VA</option>
                  <option <?php  print ($CoordState == "VI") ? "selected" : "" ?>>VI</option>
                  <option <?php  print ($CoordState == "VT") ? "selected" : "" ?>>VT</option>
                  <option <?php  print ($CoordState == "WA") ? "selected" : "" ?>>WA</option>
                  <option <?php  print ($CoordState == "WI") ? "selected" : "" ?>>WI</option>
                  <option <?php  print ($CoordState == "WV") ? "selected" : "" ?>>WV</option>
                  <option <?php  print ($CoordState == "WY") ? "selected" : "" ?>>WY</option>
            </select>
 			<?php
  			}
  			?>
			</td>
		</tr>
	   <!------------------------------------------------------------------------------>
	   <!-- Row 5                                                                   -->
	   <!------------------------------------------------------------------------------>
		<tr>
         <!----------------------- Column 1 ------------------------------------------>
			<td width="12%">&nbsp;</td>
         <!----------------------- Column 2-4 ------------------------------------------>
			<td width="38%" colspan="3">&nbsp;</td>
         <!----------------------- Column 5 ------------------------------------------>
			<td width="25%">Zip</td>
         <!----------------------- Column 6-7 ------------------------------------------>
			<td width="25%" colspan="2">
 			<?php
			if ($mode == "view")
			{
			   print $CoordZip;
			}
			else
			{
			?>
			   <input type="text" name="CoordZip" size="20" <?php  print ($CoordZip != "") ? "value=\"" . $CoordZip . "\"" : ""; ?>>
 			<?php
  			}
  			?>
			</td>
		</tr>
	   <!------------------------------------------------------------------------------>
	   <!-- Row 6                                                                   -->
	   <!------------------------------------------------------------------------------>
		<tr>
         <!----------------------- Column 1 ------------------------------------------>
			<td width="12%">Email</td>
         <!----------------------- Column 2-7 ------------------------------------------>
			<td width="88%" colspan="6">
 			<?php
			if ($mode == "view")
			{
			   print $CoordEmail;
			}
			else
			{
			?>
			   <input type="text" name="CoordEmail" size="36" <?php  print ($CoordEmail != "") ? "value=\"" . $CoordEmail . "\"" : ""; ?>> (Enter <b>None</b> for no email address
 			<?php
  			}
  			?>
			</td>
		</tr>
	   <!------------------------------------------------------------------------------>
	   <!-- Row 7                                                                   -->
	   <!------------------------------------------------------------------------------>
		<tr>
         <!----------------------- Column 1 ------------------------------------------>
			<td width="12%">Phone</td>
         <!----------------------- Column 2-7 ------------------------------------------>
			<td width="88%" colspan="6">
 			<?php
			if ($mode == "view")
			{
			   print $CoordPhone;
			}
			else
			{
			?>
            <input type="text" name="CoordPhone" size="36" <?php  print ($CoordPhone != "") ? "value=\"" . $CoordPhone . "\"" : ""; ?>>(xxx) xxx-xxxx
 			<?php
  			}
  			?>
			</td>
		</tr>
	</table>
	<p align="center"><?php
            if ($mode == 'update')
            {?> <input type="submit" value="Update" name="update">
	            <input type="hidden" value="<?php  print $CoordID; ?>" name="CoordID">
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
<?php footer("Return to Coordinator List","Coordinators.php")?>
&nbsp;

</body>

</html>
<?php
}
