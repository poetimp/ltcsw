<?php
include 'include/RegFunctions.php';
if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}


if (isset($_POST['AddNew']))
{
	header("refresh: 0; URL=AdminEventCoord.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

<head>
<meta http-equiv="Content-Language" content="en-us">
<title>Maintain Coordinators</title>

</head>

<body style="background-color: rgb(217, 217, 255);">
<h1 align="center">Coordinator Maintenance </h1>
<form method="post" action=Coordinators.php>
      <?php
         $results = mysql_query("select   Name,
                                          CoordID
                                 from     $EventCoordTable
                                 order by Name")
                    or die ("Unable to get coordinator list:" . mysql_error());

         $count = 0;
         ?>
         <table border="1" width="100%">
         <?php
         while ($row = mysql_fetch_assoc($results))
         {
            ?>
            <tr>
               <td width="70" align="center">[<a href="AdminEventCoord.php?action=view<?php  print "&CoordID=".$row['CoordID']; ?>">View</a>]</td>
               <td width="70" align="center">[<a href="AdminEventCoord.php?action=update<?php  print "&CoordID=".$row['CoordID']; ?>">Update</a>]</td>
               <td width="70" align="center"> [<a href="DelEventCoord.php?action=del<?php  print "&CoordID=".$row['CoordID']."&CoordName=".urlencode($row['Name']); ?>">Delete</a>]</td>
               <td><?php  print $row['Name']; ?></td>
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