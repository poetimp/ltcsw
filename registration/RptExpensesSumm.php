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
      <title>
         Summary Expense Report
      </title>
      <meta http-equiv="Content-Language" content="en-us">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel=stylesheet href="include/registration.css" type="text/css" />
   </head>
   <body>
   <?php

      //-----------------------------------------------------------------------
      // Get the cost for various items
      //-----------------------------------------------------------------------
      $price               = GetPrices();
      $RegCost             = $price["Registration"];
      $ShirtCost           = $price["Shirt"];
      $AdultMealCost       = $price["AdultMeal"];
      $ChildMealCost       = $price["ChildMeal"];

      //-----------------------------------------------------------------------
      // Initalize Accumulating Variables
      //-----------------------------------------------------------------------
      $ParticipantCount    = 0;

      $ExtraAdultMealCount = 0;
      $ExtraChildMealCount = 0;
      $ExtraShirtCount     = 0;

      $costParticipant     = 0;

      $costExtraAdultMeals = 0;
      $costExtraChildMeals = 0;
      $costExtraShirts     = 0;

      $costTotal           = 0;
      $costBalance         = 0;
      $MoneyInOut          = 0;

      //=================================================================================================
      // Calculate the expense encured by active congregation
      //=================================================================================================
      $ChuchList = ChurchesDefined();
      foreach ($ChuchList as $ChurchID=>$ChurchName)
      {
         $costDetail = ChurchExpenses($ChurchID);
         //-----------------------------------------------------------------------
         // Get the Counts for various items purchased
         //-----------------------------------------------------------------------
         $ParticipantCount    += $costDetail["ParticipantCount"];
         $ExtraAdultMealCount += $costDetail["ExtraAdultMealCount"];
         $ExtraChildMealCount += $costDetail["ExtraChildMealCount"];
         $ExtraShirtCount     += $costDetail["ExtraShirtCount"];

         //-----------------------------------------------------------------------
         // Get the cost for various items Purchased
         //-----------------------------------------------------------------------
         $costParticipant     += $costDetail["Participant"];
         $costExtraAdultMeals += $costDetail["ExtraAdultMeals"];
         $costExtraChildMeals += $costDetail["ExtraChildMeals"];
         $costExtraShirts     += $costDetail["ExtraShirts"];
         $costTotal           += $costDetail["Total"];
         $costBalance         += $costDetail["Balance"];
         $MoneyInOut          += $costDetail["MoneyInOut"];

      }

      //-----------------------------------------------------------------------
      // Display to user
      //-----------------------------------------------------------------------
      ?>
      <h1 align="center">Summary Expense Report</h1>

      <table class='registrationTable' style='width: 50%;margin-left: auto;margin-right: auto;'>
         <tr>
            <td style='width: 25%'><b>Registered Participants</b></td>
            <td style='width: 25%; text-align: right'><?php print $ParticipantCount;?></td>
            <td style='width: 25%; text-align: center'>x <?php print FormatMoney($RegCost);?></td>
            <td style='width: 25%; text-align: right'><?php print FormatMoney($costParticipant);?></td>
         </tr>
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
            <td style='width: 25%; text-align: right'><b>Balance:</b></td>
            <td style='width: 25%; text-align: right'><?php print FormatMoney($costBalance);?></td>
         </tr>
      </table>

   </body>
</html>
