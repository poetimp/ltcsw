<?php
//----------------------------------------------------------------------------
// This software is licensed under the MIT license. Use as you wish but give
// and take credit where due.
//
// Author: Paul Lemmons
//----------------------------------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

<?php
include 'include/RegFunctions.php';

$RoomID          = "";
$RoomName        = "";
$RoomSeats       = "";

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update')
{
   $mode = 'update';
   $RoomID = $_REQUEST['RoomID'];
}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')
{
   $mode = 'view';
   $RoomID = $_REQUEST['RoomID'];
}
else //if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add')
{
   $mode = 'add';
}

$ErrorMsg = "";

if ($mode == 'update' || $mode == 'view')
{
   $result = $db->query("select RoomName,
                                 RoomSeats
                         from $RoomsTable where RoomID = '$RoomID'")
             or die ("Unable to get Room information: ".sqlError());
   $row = $result->fetch(PDO::FETCH_ASSOC);

   $RoomName  = $row['RoomName'];
   $RoomSeats = $row['RoomSeats'];
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

   $RoomID         = isset($_POST['RoomID'])         ? $_POST['RoomID']         : "";
   $RoomName       = isset($_POST['RoomName'])       ? $_POST['RoomName']       : "";
   $RoomSeats      = isset($_POST['RoomSeats'])      ? $_POST['RoomSeats']      : "";


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
      if ($mode == 'update')
      {
         $sql = "update $RoomsTable
                    set RoomSeats    = $RoomSeats,
                        RoomName     = ".$db->quote($RoomName)."
                    where RoomID     = $RoomID";
      }
      else
      {
         $sql = "insert into $RoomsTable
                        (RoomName,
                         RoomSeats
                         )
                 values (".$db->quote($RoomName).",
                         $RoomSeats
                         )";
      }

      //print "<pre>";print_r($sql);print "</pre>\n";
      $results = $db->query($sql) or die ("Unable to process update: " . sqlError());

      if ($mode == 'add')
         $RoomID = $db->lastInsertId();

      ?>
         <body>
         <?php
              if ($mode == 'update')
              {
                ?>
                  <h1 align="center">
                     Room <br/>"<?php  print $RoomName; ?>"<br/>Updated!
                  </h1>
                <?php
              }
              else
              {
                ?>
                  <h1 align="center">
                     Room <br/>"<?php  print $RoomName; ?>"<br/>Added!
                  </h1>
                <?php
              }

         ?>
            <div style="text-align: center"><a href="Rooms.php">Return to Rooms List</a></div>
         </body>
      </html>

      <?php
   }
}

if ((!isset($_POST['add']) and !isset($_POST['update'])) or $ErrorMsg != "")
{
   ?>


   <head>
      <meta http-equiv="Content-Language" content="en-us" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="stylesheet" href="include/registration.css" type="text/css" />

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

   <body>
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
         print "<div style='text-align: center'><font color=\"FF0000\"><b>" . $ErrorMsg . "</b></font></div><br />";
      }
   ?>

   <form method="post">
      <table class='registrationTable' id="table1">
         <tr>
            <th colspan="4" style='text-align: center'>Room Information</th>
         </tr>
         <tr>
            <th style='width: 12%;'>Name</td>
            <td style='width: 85%;' colspan="3">
               <?php
                  if ($mode == 'view')
                  {
                     print $RoomName;
                  }
                  else
                  {?>
                     <input type="text" name="RoomName" size="36" <?php  print ($RoomName != "") ? "value=\"" . $RoomName . "\"" : ""; ?>/>
                  <?php
                  }
               ?>
            </td>
         </tr>
         <tr>
            <th style='width: 12%;'>Seats</td>
            <td style='width: 85%;' colspan="3">
            <?php
            if ($mode == "view")
            {
               print $RoomSeats;
            }
            else
            {
            ?>
               <input type="text" name="RoomSeats" size="36" <?php  print ($RoomSeats != "") ? "value=\"" . $RoomSeats . "\"" : ""; ?>/></td>
            <?php
            }
            ?>
         </tr>
      </table>
      <p align="center">
         <?php
            if ($mode == 'update')
            {?>
               <input type="submit" value="Update" name="update"/>
               <input type="hidden" value="<?php  print $RoomID; ?>"   name="RoomID"/>
               <input type="hidden" value="update" name="action"/>
             <?php
            }
            else if ($mode == 'add')
            {?>
               <input type="submit" value="Add" name="add"/>
               <input type="hidden" value="add" name="action"/>
             <?php
            }
            else if ($mode == 'view')
            {?>
               <input type="hidden" value="update" name="action"/>
             <?php
            }
         ?>
         <br/>
      </p>
   </form>
   <?php footer("Return to Rooms List","Rooms.php")?>
   </body>

   </html>
<?php
}