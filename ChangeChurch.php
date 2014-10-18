<?php
include 'include/RegFunctions.php';

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

if (isset($_POST['Update']))
{
   WriteToLog("Changed Church from ".$_SESSION['ChurchID']." to ".$_POST['ChurchID']);
   $_SESSION['ChurchID'] = $_POST['ChurchID'];
   header("refresh: 0; URL=Admin.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

<head>
<meta http-equiv="Content-Language" content="en-us">
<title>Change Church</title>

</head>

<body style="background-color: rgb(217, 217, 255);">
<h1 align="center">Change Church</h1>
<form method="post" action=ChangeChurch.php>
      <?php
         $results = mysql_query("select   ChurchName,
                                          ChurchID
                                 from     $ChurchesTable
                                 order by ChurchName")
                    or die ("Unable to obtain church list:" . mysql_error());
         ?>
         <center>
         <select name=ChurchID>
         <?php
         while ($row = mysql_fetch_assoc($results))
         {
            ?>
               <option value=<?php  print $row['ChurchID']; ?>><?php  print $row['ChurchName']; ?></option>
            </tr>
         <?php
         }
         ?>
         </select>
         </center>
   <p align="center"><input type="submit" value="Update" name="Update"></p>
</form>
<?php footer("","")?>

</body>

</html>