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
	header("refresh: 0; URL=AdminEventCoord.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

   <head>
      <meta http-equiv="Content-Language" content="en-us">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel=stylesheet href="include/registration.css" type="text/css" />

      <title>Maintain Coordinators</title>

   </head>

   <body>
      <h1 align="center">Coordinator Maintenance </h1>
      <form method="post" action=Coordinators.php>
            <?php
               $results = $db->query("select   Name,
                                                CoordID
                                       from     $EventCoordTable
                                       order by Name")
                          or die ("Unable to get coordinator list:" . sqlError());

               $count = 0;
               ?>
               <table class='registrationTable' style='width: 95%'>
               <?php
               while ($row = $results->fetch(PDO::FETCH_ASSOC))
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