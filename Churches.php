<?php
include 'include/RegFunctions.php';

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

if (isset($_POST['AddNew']))
{
   header("refresh: 0; URL=AdminChurch.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

   <head>
      <meta http-equiv="Content-Language" content="en-us">
      <title>Maintain Churches</title>
   </head>

   <body style="background-color: rgb(217, 217, 255);">
      <h1 align="center">Church Maintenance </h1>
      <form method="post" action=Churches.php>
         <?php
            $results = mysql_query("select   ChurchName,
                                             ChurchID
                                    from     $ChurchesTable
                                    order by ChurchName")
                     or die ("Unable to obtain church list:" . mysql_error());

            $count = 0;
            ?>
            <table border="1" width="100%">
            <?php
            while ($row = mysql_fetch_assoc($results))
            {
               ?>
               <tr>
                  <td width="70" align="center">[<a href="AdminChurch.php?action=view<?php  print "&ChurchID=".$row['ChurchID']; ?>">View</a>]</td>
                  <td width="70" align="center">[<a href="AdminChurch.php?action=update<?php  print "&ChurchID=".$row['ChurchID']; ?>">Update</a>]</td>
                  <td width="70" align="center"> [<a href="DelChurch.php?action=del<?php  print "&ChurchID=".$row['ChurchID']."&ChurchName=".urlencode($row['ChurchName']); ?>">Delete</a>]</td>
                  <td><?php  print "[".$row['ChurchID']."]: ".$row['ChurchName']; ?></td>
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