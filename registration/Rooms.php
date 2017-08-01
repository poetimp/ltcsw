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

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

if (isset($_POST['AddNew']))
{
   header("refresh: 0; URL=AdminRooms.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

<head>
<meta http-equiv="Content-Language" content="en-us">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel=stylesheet href="include/registration.css" type="text/css" />
<title>Hotel Conference Rooms</title>

</head>

<body>
<h1 align="center">Conference Room Maintenance </h1>
<form method="post">
      <?php
         $results = $db->query("select   RoomID,
                                          RoomName,
                                          RoomSeats
                                 from     $RoomsTable
                                 order by RoomName")
                    or die ("Unable to obtain room list:" . sqlError());

         $count = 0;
         ?>
         <table class='registrationTable'>
            <tr>
               <th width=100 align="center" colspan="3">Action</th>
               <th width=10 align="center">Seats</th>
               <th>Room Name</th>
            </tr>
         <?php
         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            ?>
            <tr>
               <td width=5% align="center">[<a href="AdminRooms.php?action=view<?php  print "&RoomID=".$row['RoomID']; ?>">View</a>]</td>
               <td width=5% align="center">[<a href="AdminRooms.php?action=update<?php  print "&RoomID=".$row['RoomID']; ?>">Update</a>]</td>
               <td width=5% align="center">[<a href="DelRoom.php?action=del<?php  print "&RoomID=".$row['RoomID']; ?>">Delete</a>]</td>
               <td width=5% align="center"><?php  print $row['RoomSeats']; ?></td>
               <td><?php  print $row['RoomName']; ?></td>
            </tr>
         <?php
         }
         ?>
         </table>
   <p align="center"><input type="submit" value="Add New" name="AddNew"></p>
   <p align="center"><a href="Admin.php">Back to Administration Home</a></p>
</form>

</body>

</html>
