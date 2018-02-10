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
      <meta http-equiv="Content-Language" content="en-us" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="stylesheet" href="include/registration.css" type="text/css" />

   <title>Change Church</title>

   </head>

   <body>
   <h1 align="center">Change Church</h1>
   <form method="post">
         <?php
            $results = $db->query("select   ChurchName,
                                             ChurchID
                                    from     $ChurchesTable
                                    order by ChurchName")
                       or die ("Unable to obtain church list:" . sqlError());
            ?>
            <div style="text-align: center">
            <select name="ChurchID">
            <?php
            while ($row = $results->fetch(PDO::FETCH_ASSOC))
            {
               ?>
                  <option value="<?php  print $row['ChurchID']; ?>"><?php  print $row['ChurchName']; ?></option>
            <?php
            }
            ?>
            </select>
            </div>
      <p align="center"><input type="submit" value="Update" name="Update" /></p>
   </form>
   <?php footer("","")?>

   </body>

</html>