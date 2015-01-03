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
<title>Hotel Conference Rooms</title>

</head>

<body style="background-color: rgb(217, 217, 255);">
<h1 align="center">Conference Room Maintenance </h1>
<form method="post">
      <?php
         $results = mysql_query("select   RoomID,
                                          RoomName,
                                          RoomSeats
                                 from     $RoomsTable
                                 order by RoomName")
                    or die ("Unable to obtain room list:" . mysql_error());

         $count = 0;
         ?>
         <table border="1" width="100%">
            <tr>
               <td width=100 align="center" colspan="3" bgcolor="#000000">
                  <font color="#FFFF00">
                     Action
                  </font>
               </td>
               <td width=10 align="center" bgcolor="#000000">
                  <font color="#FFFF00">
                     Seats
                  </font>
               </td>
               <td bgcolor="#000000">
                  <font color="#FFFF00">
                     Room Name
                  </font>
               </td>
            </tr>
         <?php
         while ($row = mysql_fetch_assoc($results))
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
