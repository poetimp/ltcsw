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
<title>Maintain Events</title>

</head>

<body style="background-color: rgb(217, 217, 255);">
<h1 align="center">Event Maintenance </h1>
<form method="post" action=Events.php>
      <?php
         $results = mysql_query("select   EventName,
                                          EventID
                                 from     $EventsTable
                                 order by EventName")
                    or die ("Unable to get events list:" . mysql_error());

         $count = 0;
         ?>
         <table border="1" width="100%">
         <?php
         while ($row = mysql_fetch_assoc($results))
         {
            ?>
            <tr>
               <td width="70" align="center">[<a href="AdminEvent.php?action=view<?php  print "&EventID=".$row['EventID']; ?>">View</a>]</td>
               <td width="70" align="center">[<a href="AdminEvent.php?action=update<?php  print "&EventID=".$row['EventID']; ?>">Update</a>]</td>
               <td width="70" align="center"> [<a href="DelEvent.php?action=del<?php  print "&EventID=".$row['EventID']."&EventName=".urlencode($row['EventName']); ?>">Delete</a>]</td>
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
