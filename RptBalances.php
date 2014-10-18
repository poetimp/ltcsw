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
   </head>
   <body bgcolor="White">
   <h1 align="center">Expense Balance Report</h1>

   <?php
      $ChuchList = ChurchesDefined();
      if (count($ChuchList) > 0)
      {
         ?>
         <table border="1" width="100%">
               <tr>
                  <td width="40%" align="left" bgcolor="#C0C0C0">Church</td>
                  <td width="10%" align="center" bgcolor="#C0C0C0">Participants?</td>
                  <td width="25%" align="center" bgcolor="#C0C0C0">Description</td>
                  <td width="25%" align="left" bgcolor="#C0C0C0">Amount</td>
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
                  <td width="40%" align="left"   ><?php print "$ChurchName";?></td>
                  <td width="10%" align="center" ><?php print "$registered";?></td>
                  <td width="25%" align="center" ><?php print "$BalanceComment";?></td>
                  <td width="25%" align="left"   ><?php print FormatMoney($costDetail["Balance"]);?></td>
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
