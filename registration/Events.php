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

if (isset($_POST['AddNew']))
{
   header("refresh: 0; URL=AdminEvent.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

<head>
<meta http-equiv="Content-Language" content="en-us">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel=stylesheet href="include/registration.css" type="text/css" />
<title>Maintain Events</title>

</head>

<body>
<h1 align="center">Event Maintenance </h1>
<form method="post" action=Events.php>
      <?php
         $results = $db->query("select   EventName,
                                          EventID
                                 from     $EventsTable
                                 order by EventName")
                    or die ("Unable to get events list:" . sqlError());

         $count = 0;
         ?>
         <table class='registrationTable'>
         <?php
         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            ?>
            <tr>
               <td style='width: 70px; text-align: center;'>[<a href="AdminEvent.php?action=view<?php  print "&EventID=".$row['EventID']; ?>">View</a>]</td>
               <td style='width: 70px; text-align: center;'>[<a href="AdminEvent.php?action=update<?php  print "&EventID=".$row['EventID']; ?>">Update</a>]</td>
               <td style='width: 70px; text-align: center;'> [<a href="DelEvent.php?action=del<?php  print "&EventID=".$row['EventID']."&EventName=".urlencode($row['EventName']); ?>">Delete</a>]</td>
               <td><?php  print $row['EventName']; ?></td>
            </tr>
         <?php
         }
         ?>
         </table>
   <p align="center"><input type="submit" value="Add New" name="AddNew"></p>
</form>
<?php footer("","")?>

</body>

</html>
