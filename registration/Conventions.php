<?php
include 'include/RegFunctions.php';

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

if (isset($_POST['AddNew']))
{
   header("refresh: 0; URL=AdminConventions.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

<head>
<meta http-equiv="Content-Language" content="en-us">
<title>LTC Conventions</title>

</head>

<body style="background-color: rgb(217, 217, 255);">
<h1 align="center">Convention Maintenance </h1>
<form method="post" action=Conventions.php>
      <?php
         $results = mysql_query("select   ConvName,
                                          ConvCode
                                 from     $ConventionsTable
                                 order by ConvName")
                    or die ("Unable to obtain Convention list:" . mysql_error());

         $count = 0;
         ?>
         <table border="1" width="100%">
         <?php
         while ($row = mysql_fetch_assoc($results))
         {
            ?>
            <tr>
               <td width="70" align="center">[<a href="AdminConventions.php?action=view<?php  print "&ConvCode=".urlencode($row['ConvCode']); ?>">View</a>]</td>
               <td width="70" align="center">[<a href="AdminConventions.php?action=update<?php  print "&ConvCode=".urlencode($row['ConvCode']); ?>">Update</a>]</td>
               <td width="70" align="center">[<a href="DelConvention.php?action=del<?php  print "&ConvCode=".urlencode($row['ConvCode'])."&ConvName=".urlencode($row['ConvName']); ?>">Delete</a>]</td>
               <td width="70" align="center"><?php print $row['ConvCode']?></td>
               <td><?php  print $row['ConvName'] ?></td>
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