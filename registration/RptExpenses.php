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

if (isset($_REQUEST['Admin']) and $Admin == 'Y')
{
   $AdminReport = 1;
}
else
{
   $AdminReport = 0;
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

   <head>
      <title>
         Expense Report
      </title>
      <meta http-equiv="Content-Language" content="en-us">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel=stylesheet href="include/registration.css" type="text/css" />
   </head>
   <body>
   <?php
   function PrintReport($ChurchID)
   {

      $ChurchName = ChurchName($ChurchID);
      $costDetail = ChurchExpenses($ChurchID);

      //-----------------------------------------------------------------------
      // Get the cost for various items
      //-----------------------------------------------------------------------
      $price               = GetPrices();
      $RegCost             = $price["Registration"];
      $ShirtCost           = $price["Shirt"];
      $AdultMealCost       = $price["AdultMeal"];
      $ChildMealCost       = $price["ChildMeal"];

      //-----------------------------------------------------------------------
      // Get the Counts for various items purchased
      //-----------------------------------------------------------------------
      $ParticipantCount    = $costDetail["ParticipantCount"];
      $ExtraAdultMealCount = $costDetail["ExtraAdultMealCount"];
      $ExtraChildMealCount = $costDetail["ExtraChildMealCount"];
      $ExtraShirtCount     = $costDetail["ExtraShirtCount"];

      //-----------------------------------------------------------------------
      // Get the cost for various items Purchased
      //-----------------------------------------------------------------------
      $costParticipant     = $costDetail["Participant"];
      $costExtraAdultMeals = $costDetail["ExtraAdultMeals"];
      $costExtraChildMeals = $costDetail["ExtraChildMeals"];
      $costExtraShirts     = $costDetail["ExtraShirts"];
      $costTotal           = $costDetail["Total"];
      $costBalance         = $costDetail["Balance"];
      $MoneyInOut          = $costDetail["MoneyInOut"];

      if ($costBalance > 0)
         $balanceCarity = 'Due';
      else if ($costBalance < 0)
         $balanceCarity = 'Credit';
      else
         $balanceCarity = '';

      //-----------------------------------------------------------------------
      // Display to the user
      //-----------------------------------------------------------------------
      ?>
      <h1 align="center">Expense Report</h1>
      <h1 align="center">For: <?php  print "$ChurchName";?></h1>
      <table class='registrationTable' style='width: 50%;margin-left: auto;margin-right: auto;'>
         <tr>
            <td style='width: 25%'><b>Registered Participants</b></td>
            <td style='width: 25%; text-align: right'><?php print $ParticipantCount;?></td>
            <td style='width: 25%; text-align: center'>x <?php print FormatMoney($RegCost);?></td>
            <td style='width: 25%; text-align: right'><?php print FormatMoney($costParticipant);?></td>
         </tr>
         <!--
         <tr>
            <td style='width: 25%'><b>Extra Adult Meal Tickets</b></td>
            <td style='width: 25%; text-align: right'><?php print $ExtraAdultMealCount;?></td>
            <td style='width: 25%; text-align: center'>x <?php print FormatMoney($AdultMealCost);?></td>
            <td style='width: 25%; text-align: right'><?php print FormatMoney($costExtraAdultMeals);?></td>
         </tr>
         <tr>
            <td style='width: 25%'><b>Extra Child Meal Tickets</b></td>
            <td style='width: 25%; text-align: right'><?php print $ExtraChildMealCount;?></td>
            <td style='width: 25%; text-align: center'>x <?php print FormatMoney($ChildMealCost);?></td>
            <td style='width: 25%; text-align: right'><?php print FormatMoney($costExtraChildMeals);?></td>
         </tr>
          -->
         <tr>
            <td style='width: 25%'><b>Extra T-Shirts</b></td>
            <td style='width: 25%; text-align: right'><?php print $ExtraShirtCount;?></td>
            <td style='width: 25%; text-align: center'>x <?php print FormatMoney($ShirtCost);?></td>
            <td style='width: 25%; text-align: right'><?php print FormatMoney($costExtraShirts);?></td>
         </tr>
         <tr>
            <td style='width: 50%' colspan=2>&nbsp;</td>
            <td style='width: 25%; text-align: right'><b>Total:</b></td>
            <td style='width: 25%; text-align: right'><?php print FormatMoney($costTotal);?></td>
         </tr>
         <tr>
            <td style='width: 50%' colspan=2>&nbsp;</td>
            <td style='width: 25%; text-align: right'><b>Monies Received:</b></td>
            <td style='width: 25%; text-align: right'><?php print FormatMoney($MoneyInOut)?></td>
         </tr>
         <tr>
            <td style='width: 50%' colspan=2>&nbsp;</td>
            <td style='width: 25%; text-align: right'><b>Balance <?php print $balanceCarity?>:</b></td>
            <td style='width: 25%; text-align: right"'<?php print FormatMoney($costBalance);?></td>
         </tr>
      </table>
      <?php
   }

   if ($AdminReport)
   {
      if (isset($_REQUEST['all']))
      {
         $ChuchList = ChurchesDefined();
      }
      else
      {
         $ChuchList = ChurchesRegistered();
      }
      if (count($ChuchList) > 0)
      {
         foreach ($ChuchList as $ChurchID=>$ChurchName)
         {
            PrintReport($ChurchID);
            print "<br><hr>";
         }
      }
      else
      {
         ?>
            <center>
            <h1>No churches with participating registrants have been defined</h1>
            <h2>Expense report is empty</h2>
            </center>
         <?php
      }
   }
   else
   {
      PrintReport($ChurchID);
   }
   ?>

   </body>
</html>
