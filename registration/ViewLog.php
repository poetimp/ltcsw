<<?php
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

<head>
<meta http-equiv="Content-Language" content="en-us">
<title>View Log</title>

</head>

<body style="background-color: rgb(217, 217, 255);">
   <h1 align="center">View Access Log</h1>
   <?php
      $results = $db->query("select   *
                              from     $LogTable
                              where    Date > date_sub(current_date(),interval 6 month)
                              order by Date Desc")
                 or die ("Unable to read the log:" . sqlError());

      ?>
      <div align="center">
      <table border="1">
         <tr>
            <td width="250" align="left" bgcolor="#000000"><font color="yellow">Date</font></td>
            <td width="100" align="left" bgcolor="#000000"><font color="yellow">Userid</font></td>
            <td width="250" align="left" bgcolor="#000000"><font color="yellow">Log Entry</font></td>
         </tr>
      <?php
      while ($row = $results->fetch(PDO::FETCH_ASSOC))
      {
         $Date = $row['Date'];
         $User = $row['UserID'];
         $Action = $row['Action'];
         ?>
         <tr>
            <td align="left"><?php  print $Date; ?></td>
            <td align="left"><?php  print $User; ?>&nbsp;</td>
            <td align="left"><?php  print $Action; ?></td>
         </tr>
      <?php
      }
      ?>
      </table>
      </div>
      <?php
      $results = $db->query("select   *
                              from     $LogTable
                              where    Date > date_sub(current_date(),interval 6 month)
                              order by Date Desc")
                 or die ("Unable to read the log:" . sqlError());

      ?>
      <div align="center">
      <h1 align="center">View Current Convention Log</h1>
      <table border="1">
         <tr>
            <td align="left" bgcolor="#000000"><font color="yellow">Date</font></td>
            <td align="left" bgcolor="#000000"><font color="yellow">Userid</font></td>
            <td align="left" bgcolor="#000000"><font color="yellow">Log Entry</font></td>
         </tr>
      <?php
      while ($row = $results->fetch(PDO::FETCH_ASSOC))
      {
         $Date = $row['Date'];
         $User = $row['UserID'];
         $Action = $row['Action'];
         ?>
         <tr>
            <td align="left"><?php  print $Date; ?></td>
            <td align="left"><?php  print $User; ?>&nbsp;</td>
            <td align="left"><?php  print $Action; ?></td>
         </tr>
      <?php
      }
      ?>
      </table>
      </div>
   <?php footer("","")?>
</body>

</html>