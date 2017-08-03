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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

<head>
   <meta http-equiv="Content-Language" content="en-us">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel=stylesheet href="include/registration.css" type="text/css" />

   <title>View Log</title>

</head>

<body>
   <h1 align="center">View Access Log</h1>
   <?php
      $results = $db->query("select   *
                              from     $LogTable
                              where    Date > date_sub(current_date(),interval 6 month)
                              order by Date Desc")
                 or die ("Unable to read the log:" . sqlError());

      ?>
      <div align="center">
      <table class='registrationTable' style='width: 50%'>
         <tr>
            <th style='width: 250px; text-align: left'>Date</th>
            <th style='width: 100px; text-align: left'>Userid</th>
            <th style='width: 250px; text-align: left'>Log Entry</th>
         </tr>
      <?php
      while ($row = $results->fetch(PDO::FETCH_ASSOC))
      {
         $Date = $row['Date'];
         $User = $row['UserID'];
         $Action = $row['Action'];
         ?>
         <tr>
            <td style='text-align: left'><?php  print $Date; ?></td>
            <td style='text-align: left'><?php  print $User; ?>&nbsp;</td>
            <td style='text-align: left'><?php  print $Action; ?></td>
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
      <table class='registrationTable' style='width: 50%'>
         <tr>
            <th style='text-align: left'>Date</th>
            <th style='text-align: left'>Userid</th>
            <th style='text-align: left'>Log Entry</th>
         </tr>
      <?php
      while ($row = $results->fetch(PDO::FETCH_ASSOC))
      {
         $Date = $row['Date'];
         $User = $row['UserID'];
         $Action = $row['Action'];
         ?>
         <tr>
            <td style='text-align: left'><?php  print $Date; ?></td>
            <td style='text-align: left'><?php  print $User; ?>&nbsp;</td>
            <td style='text-align: left'><?php  print $Action; ?></td>
         </tr>
      <?php
      }
      ?>
      </table>
      </div>
   <?php footer("","")?>
</body>

</html>