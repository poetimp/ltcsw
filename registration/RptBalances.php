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

if (isset($_REQUEST["nozero"]))
{
   $showZeroBalances=0;
}
else
{
   $showZeroBalances=1;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

   <head>
      <title>
         Expense Ballance Report
      </title>
      <meta http-equiv="Content-Language" content="en-us">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel=stylesheet href="include/registration.css" type="text/css" />
   </head>
   <body bgcolor="White">
   <h1 align="center">Expense Balance Report</h1>

   <?php
      $ChuchList = ChurchesDefined();
      if (count($ChuchList) > 0)
      {
         ?>
         <table class='registrationTable'>
               <tr>
                  <th style='width: 40%; text-align: left'>  Church</th>
                  <th style='width: 10%; text-align: center'>Participants?</th>
                  <th style='width: 25%; text-align: center'>Description</th>
                  <th style='width: 25%; text-align: left'>  Amount</th>
               </tr>
         <?php
         foreach ($ChuchList as $ChurchID=>$ChurchName)
         {
            $costDetail = ChurchExpenses($ChurchID);
            $ChurchName = ChurchName($ChurchID);

            if ($costDetail["Balance"] != 0 or $showZeroBalances)
            {
               if ($costDetail["Balance"] > 0)
                  $BalanceComment = "Owed to LTC";
               else if ($costDetail["Balance"] < 0)
                  $BalanceComment = "Credit to Church";
               else
                  $BalanceComment = "Zero Balance";

               if (count(ActiveParticipants($ChurchID)) > 0)
                  $registered = 'Yes';
               else
                  $registered = 'No';
            ?>
               <tr>
                  <td style='width: 40%; text-align: left'   ><?php print "$ChurchName";?></td>
                  <td style='width: 10%; text-align: center' ><?php print "$registered";?></td>
                  <td style='width: 25%; text-align: center' ><?php print "$BalanceComment";?></td>
                  <td style='width: 25%; text-align: left'   ><?php print FormatMoney($costDetail["Balance"]);?></td>
               </tr>
            <?php
            }
         }
         ?>
         </table>
         <?php
      }
      else
      {
         ?>
            <center>
            <h1>No churches have been defined</h1>
            <h2>Expense report is empty</h2>
            </center>
         <?php
      }
   ?>

   </body>
</html>
