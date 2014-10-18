<?php
include 'include/RegFunctions.php';

$RoomName        = "";
$RoomSeats       = "";
$AllowConflicts  = 0;

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update')
{
   $mode = 'update';
   $RoomName = $_REQUEST['RoomName'];
}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')
{
   $mode = 'view';
   $RoomName = $_REQUEST['RoomName'];
}
else //if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add')
{
   $mode = 'add';
}

$ErrorMsg = "";

if ($mode == 'update' || $mode == 'view')
{
   $result = mysql_query("select * from $RoomsTable where RoomName = '$RoomName'")
             or die ("Unable to get Room information: ".mysql_error());
   $row = mysql_fetch_assoc($result);

   $RoomName       = isset($row['RoomName'])       ? $row['RoomName']       : "";
   $RoomSeats      = isset($row['RoomSeats'])      ? $row['RoomSeats']      : "";
   $AllowConflicts = isset($row['AllowConflicts']) ? $row['AllowConflicts'] : 0;
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

   $RoomName       = isset($_POST['RoomName'])       ? $_POST['RoomName']       : "";
   $RoomSeats      = isset($_POST['RoomSeats'])      ? $_POST['RoomSeats']      : "";
   $AllowConflicts = isset($_POST['AllowConflicts']) ? $_POST['AllowConflicts'] : 0;


   if ($RoomName == "")
   {
      $ErrorMsg = "Please enter the required field: Room Name";
   }
   else if ($RoomSeats == "")
   {
      $ErrorMsg = "Please enter the required field: Number of seat";
   }
   else if (!is_numeric($RoomSeats) or $RoomSeats < 0 or $RoomSeats > 9999)
   {
      $ErrorMsg = "Invalid Maximum number of seats. Must be numeric in the range 0-9999";
   }

   if ($ErrorMsg == "")
   {
      ereg_replace("'","''",$RoomName);

      if ($mode == 'update')
      {
         $sql = "update $RoomsTable
                    set RoomSeats      = $RoomSeats,
                        AllowConflicts = $AllowConflicts
                    where RoomName     = '$RoomName'";
      }
      else
      {
         $sql = "insert into $RoomsTable
                        (RoomName,
                         RoomSeats,
                         AllowConflicts
                         )
                 values ('$RoomName',
                         $RoomSeats,
                         $AllowConflicts
                         )";
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
                  <h1 align=center>
                     Room <br>"<?php  print $RoomName; ?>"<br>Updated!
                  </h1>
                <?php
              }
              else
              {
                ?>
                  <h1 align=center>
                     Room <br>"<?php  print $RoomName; ?>"<br>Added!
                  </h1>
                <?php
              }

         ?>
            <center><a href="Rooms.php">Return to Rooms List</a></center>
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
   <?php
      if ($mode == 'update')
      {
      ?>
         <title>Update Room Record</title>
      <?php
      }
      else if ($mode == 'add')
      {
      ?>
         <title>Add a new Room</title>
      <?php
      }
      else
      {
      ?>
         <title>Room</title>
      <?php
      }
   ?>
   </head>

   <body style="background-color: rgb(217, 217, 255);">
   <?php
      if ($mode == 'update')
      {
      ?>
         <h1 align="center">Update Room Record</h1>
      <?php
      }
      else if ($mode == 'add')
      {
      ?>
         <h1 align="center">Add a new Room</h1>
      <?php
      }
      else
      {
      ?>
         <h1 align="center">Room</h1>
      <?php
      }

      if ($ErrorMsg != "")
      {
         print "<center><font color=\"FF0000\"><b>" . $ErrorMsg . "</b></font></center><br>";
      }
   ?>

   <form method="post" action=AdminRooms.php>
      <table border="1" width="100%" id="table1">
         <tr>
            <td colspan="4" bgcolor="#000000">
            <p align="center"><font color="#FFFF00">
            <span style="background-color: #000000">Room Information</span></font></td>
         </tr>
         <tr>
            <td width="12%">Name</td>
            <td width="85%" colspan="3">
               <?php
               if ($mode != "add")
               {
                  print $RoomName;
               }
               else
               {
               ?>
                  <input type="text" name="RoomName" size="36" <?php  print ($RoomName != "") ? "value=\"" . $RoomName . "\"" : ""; ?>>
               <?php
               }
               ?>
            </td>
         </tr>
         <tr>
            <td width="12%">Seats</td>
            <td width="85%" colspan="3">
            <?php
            if ($mode == "view")
            {
               print $RoomSeats;
            }
            else
            {
            ?>
               <input type="text" name="RoomSeats" size="36" <?php  print ($RoomSeats != "") ? "value=\"" . $RoomSeats . "\"" : ""; ?>></td>
            <?php
            }
            ?>
         </tr>
         <tr>
            <td width="12%">Allow Conflicts</td>
            <?php
            if ($mode == "view")
            {
               print "<td width=\"85%\" colspan=\"3\">";
               print ($AllowConflicts == 1) ? "Yes" : "No";
               print "</td>";
            }
            else
            {
            ?>
            <td width="14%">
            <input type="radio" value="1" name="AllowConflicts" <?php print ($AllowConflicts == 1) ? "checked" : "" ?>>Yes</td>
            <td width="14%">
            <input type="radio" value="0" name="AllowConflicts" <?php  print ($AllowConflicts == 0) ? "checked" : "" ?>>No</td>
            <td>&nbsp;</td>
            <?php
            }
            ?>
         </tr>
      </table>
      <p align="center">
         <?php
            if ($mode == 'update')
            {?>
               <input type="submit" value="Update" name="update">
               <input type="hidden" value="<?php  print $RoomName; ?>" name=RoomName>
               <input type="hidden" value="update" name=action>
             <?php
            }
            else if ($mode == 'add')
            {?>
               <input type="submit" value="Add" name="add">
               <input type="hidden" value="add" name=action>
             <?php
            }
            else if ($mode == 'view')
            {?>
               <input type="hidden" value="update" name=action>
             <?php
            }
         ?>
         <br>
      </p>
   </form>
   <?php footer("Return to Rooms List","Rooms.php")?>
   </body>

   </html>
<?php
}