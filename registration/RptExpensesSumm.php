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
   </head>
   <body bgcolor="White">
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

      <table border="1" width="100%">
         <tr>
            <td width="25%">Registered Participants</td>
            <td width="25%" align="center"><?php print $ParticipantCount;?></td>
            <td width="25%" align="center">x <?php print FormatMoney($RegCost);?></td>
            <td width="25%" align="right"><?php print FormatMoney($costParticipant);?></td>
         </tr>
         <tr>
            <td width="25%">Extra Adult Meal Tickets</td>
            <td width="25%" align="center"><?php print $ExtraAdultMealCount;?></td>
            <td width="25%" align="center">x <?php print FormatMoney($AdultMealCost);?></td>
            <td width="25%" align="right"><?php print FormatMoney($costExtraAdultMeals);?></td>
         </tr>
         <tr>
            <td width="25%">Extra Child Meal Tickets</td>
            <td width="25%" align="center"><?php print $ExtraChildMealCount;?></td>
            <td width="25%" align="center">x <?php print FormatMoney($ChildMealCost);?></td>
            <td width="25%" align="right"><?php print FormatMoney($costExtraChildMeals);?></td>
         </tr>
         <tr>
            <td width="25%">Extra T-Shirts</td>
            <td width="25%" align="center"><?php print $ExtraShirtCount;?></td>
            <td width="25%" align="center">x <?php print FormatMoney($ShirtCost);?></td>
            <td width="25%" align="right"><?php print FormatMoney($costExtraShirts);?></td>
         </tr>
         <tr>
            <td width="50%" colspan=2>&nbsp;</td>
            <td width="25%" align="right">Total:</td>
            <td width="25%" align="right"><?php print FormatMoney($costTotal);?></td>
         </tr>
         <tr>
            <td width="50%" colspan=2>&nbsp;</td>
            <td width="25%" align="right">Monies Received:</td>
            <td width="25%" align="right"><?php print FormatMoney($MoneyInOut)?></td>
         </tr>
         <tr>
            <td width="50%" colspan=2>&nbsp;</td>
            <td width="25%" align="right">Balance:</td>
            <td width="25%" align="right"><?php print FormatMoney($costBalance);?></td>
         </tr>
      </table>

   </body>
</html>