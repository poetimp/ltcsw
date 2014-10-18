<?php
include 'include/RegFunctions.php';

//----------------------------------------------------------------------------------
// Setup some constants. These are only loosely used in this piece of code. There
// is still way too much hard coded in the logic.
//----------------------------------------------------------------------------------
$prices = GetPrices();

$shirtList  = array('YM',
                    'YL',
                    'S',
                    'M',
                    'LG',
                    'XL',
                    'XX'
                   );

$mealList   = array('AdultMeal',
                    'ChildMeal'
                   );
//-----------------------------------------------------------------------
// Get the active participant list and put it in sql "in" clause format
//-----------------------------------------------------------------------
$participantList = ActiveParticipants($ChurchID);
if (count($participantList) <= 0)
{
   $ParticipantTotal = 0;
   $ParticipantMealTotal=0;
   foreach ($shirtList as $shirtSize) {
      $shirt[$shirtSize] =  0;
   }
   foreach ($mealList as $mealType) {
      $meal[$mealType] = "0";
   }
}
else
{
   $inClause = "(";
   foreach ($participantList as $participantID=>$participantName)
      $inClause.="$participantID,";
   $inClause=trim($inClause,",").")";

   //----------------------------------------------------------------------------------
   // Populate the t-shirt information from data in the database
   //----------------------------------------------------------------------------------
   $shirts = mysql_query("select   ShirtSize,
                                 count(*) Count
                        from     $ParticipantsTable
                        where    ChurchID = $ChurchID
                        and      ParticipantID in $inClause
                        group by ShirtSize")
            or die ("Unable to read participant table" . mysql_error());

   $ParticipantTotal = 0;
   while ($row = mysql_fetch_assoc($shirts))
   {
      $shirt[$row['ShirtSize']] = $row['Count'];
      $ParticipantTotal        += $row['Count'];
   }
   foreach ($shirtList as $shirtSize) {
      $shirt[$shirtSize] = isset($shirt[$shirtSize]) ? $shirt[$shirtSize] : 0;
   }

//   //----------------------------------------------------------------------------------
//   // Populate the Meal Ticket information for participante from data in the database
//   // Note: righ tnow participants meals are included in registration
//   //----------------------------------------------------------------------------------
//   $meals = mysql_query("select   MealTicket,
//                                 count(*) Count
//                        from     $ParticipantsTable
//                        where    ChurchID   = $ChurchID
//                        and      ParticipantID in $inClause
//                        and      (MealTicket = '3'
//                        or        MealTicket = '5')
//                        group by MealTicket")
//            or die ("Unable to read participant table" . mysql_error());
//
//   $ParticipantMealTotal=0;
//   while ($row = mysql_fetch_assoc($meals))
//   {
//      if ($row['MealTicket'] == '3')
//         $row['MealTicket'] = '3MealTicket';
//      else if ($row['MealTicket'] == '5')
//         $row['MealTicket'] = '5MealTicket';
//      else
//         $row['MealTicket'] = 'None';
//
//      $meal[$row['MealTicket']]   = $row['Count'];
//      $ParticipantMealTotal      += $row['Count'];
//   }
//   foreach ($mealList as $mealType) {
//      $meal[$mealType] = isset($meal[$mealType]) ? $meal[$mealType] : "0";
//   }
}
//----------------------------------------------------------------------------------
// Perform update to database using data collected from the form
//----------------------------------------------------------------------------------
if (isset($_POST['Update']))
{

   //-------------------------------------------------------------------------------
   // Validate that shirt requests are all numeric and valid
   //-------------------------------------------------------------------------------
   $extraShirtTotal = 0;
   foreach ($shirtList as $shirtSize) {
      $extraShirt[$shirtSize] = isset($_POST[$shirtSize]) ? $_POST[$shirtSize] : 0;
      $extraShirtTotal       += $extraShirt[$shirtSize];
      if (!is_numeric($extraShirt[$shirtSize]) or $extraShirt[$shirtSize] < 0 or $extraShirt[$shirtSize] > 999)
      {
         $ErrorMsg = "Invalid Shirt Order. All oders must be numeric in the range 0-999";
      }
   }
   //-------------------------------------------------------------------------------
   // Validate that Meal Ticket requests are all numeric and valid
   //-------------------------------------------------------------------------------
   $extraMealTotal = 0;
   foreach ($mealList as $mealType) {
      $extraMeal[$mealType] = isset($_POST[$mealType]) ? $_POST[$mealType] : 0;
      $extraMealTotal       += $extraMeal[$mealType];
      if (!is_numeric($extraMeal[$mealType]) or $extraMeal[$mealType] < 0 or $extraMeal[$mealType] > 999)
      {
         $ErrorMsg = "Invalid Meal Ticket Order. Must be numeric in the range 0-999";
      }
   }

   //-------------------------------------------------------------------------------
   // Reset totals if there is an error message
   //-------------------------------------------------------------------------------
   if (isset($ErrorMsg) and $ErrorMsg != "")
   {
      $extraShirtTotal = "";
      $extraMealTotal  = "";
   }
   else
   {
      //----------------------------------------------------------------------------
      // No errors in validation so lets update the database. First clear all
      // existing extra order data for this church.
      //----------------------------------------------------------------------------
      mysql_query("delete from $ExtraOrdersTable where ChurchID=$ChurchID")
            or die ("Unable to clear ExtraOrder Table: ".mysql_error());

      //----------------------------------------------------------------------------
      // Add all of the shirt data
      //----------------------------------------------------------------------------
      foreach ($shirtList as $shirtSize) {
         $ShirtCount = $extraShirt[$shirtSize];
         if ($ShirtCount > 0)
         {
            mysql_query("insert into $ExtraOrdersTable
                               (ChurchID,
                                ItemType,
                                ItemCount)
                         values('$ChurchID',
                                '$shirtSize',
                                '$ShirtCount')
                        ")
            or die ("Unable to insert extra shirts into ExtraOrders table: ".mysql_error());
         }
      }

      //----------------------------------------------------------------------------
      // Add Extra Meal ticket counts into the database
      //----------------------------------------------------------------------------
      foreach ($mealList as $mealType) {
         $MealCount = $extraMeal[$mealType];
         if ($MealCount > 0)
         {
            mysql_query("insert into $ExtraOrdersTable
                               (ChurchID,
                                ItemType,
                                ItemCount)
                         values('$ChurchID',
                                '$mealType',
                                '$MealCount')
                        ")
            or die ("Unable to insert extra meal ticket into ExtraOrders table: ".mysql_error());
         }
      }
   }
}
else
{
//----------------------------------------------------------------------------------
// Not updating so must be displaying form in prep for updating
//----------------------------------------------------------------------------------

   $extraShirts = mysql_query("select   ItemType,
                                        ItemCount
                               from     $ExtraOrdersTable
                               where    ChurchID = $ChurchID")
                  or die ("Unable to Read Extra Orders Table" . mysql_error());

   //-------------------------------------------------------------------------------
   // Load data from the database into a local array to parse
   //-------------------------------------------------------------------------------
   while ($row = mysql_fetch_assoc($extraShirts))
   {
      $extra[$row['ItemType']] = $row['ItemCount'];
   }

   //-------------------------------------------------------------------------------
   // Get the shirt data from the array
   //-------------------------------------------------------------------------------
   $extraShirtTotal=0;
   foreach ($shirtList as $shirtSize) {
      $extraShirt[$shirtSize] = isset($extra[$shirtSize]) ? $extra[$shirtSize] : 0;
      $extraShirtTotal       += $extraShirt[$shirtSize];
   }

   //-------------------------------------------------------------------------------
   // Get the meal data from the Array
   //-------------------------------------------------------------------------------
   $extraMealTotal=0;
   foreach ($mealList as $mealType) {
      $extraMeal[$mealType] = isset($extra[$mealType]) ? $extra[$mealType] : 0;
      $extraMealTotal      += $extraMeal[$mealType];
   }
}

if ($UserStatus == 'O')
   {$readOnly='';}
else
   {$readOnly='readonly="readonly" style="background:silver"';}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <title>
          Extra Orders
       </title>
          <h1 align=center>Extra Orders</h1>
    </head>
       <body style="background-color: rgb(217, 217, 255);">

    <form method="post" action=ExtraOrders.php>
         <h2>T-Shirts</h2>
         Participant T-Shirts are covered in the cost of registration. Extra T-Shirts will be
         <?php print '$'.number_format($prices['Shirt'],2);?> each.
         <table border="1" width="60%">
            <tr>
               <td width="20%" bgcolor="#000000"><font color="#FFFF00">Size</font></td>
               <td width="15%" bgcolor="#000000"><font color="#FFFF00">Participants</font></td>
               <td width="15%" bgcolor="#000000"><font color="#FFFF00">Extra Orders</font></td>
            </tr>
            <tr>
               <td width="20%">Youth Medium</td>
               <td width="15%" align="center"><?php  print $shirt['YM']; ?></td>
               <td width="15%"><input type="text" name="YM" size="5" <?php  print "value=\"".$extraShirt['YM']."\" ".$readOnly;?> ></td>
            </tr>
            <tr>
               <td width="20%">Youth Large</td>
               <td width="15%" align="center"><?php  print $shirt['YL']; ?></td>
               <td width="15%"><input type="text" name="YL" size="5" <?php  print "value=\"".$extraShirt['YL']."\" ".$readOnly;?> ></td>
            </tr>
            <tr>
               <td width="20%">Adult Small</td>
               <td width="15%" align="center"><?php  print $shirt['S']; ?></td>
               <td width="15%"><input type="text" name="S" size="5" <?php  print "value=\"".$extraShirt['S']."\" ".$readOnly;?> ></td>
            </tr>
            <tr>
               <td width="20%">Adult Medium</td>
               <td width="15%" align="center"><?php  print $shirt['M']; ?></td>
               <td width="15%"><input type="text" name="M" size="5" <?php  print "value=\"".$extraShirt['M']."\" ".$readOnly;?> ></td>
            </tr>
            <tr>
               <td width="20%">Adult Large</td>
               <td width="15%" align="center"><?php  print $shirt['LG']; ?></td>
               <td width="15%"><input type="text" name="LG" size="5" <?php  print "value=\"".$extraShirt['LG']."\" ".$readOnly;?> ></td>
            </tr>
            <tr>
               <td width="20%">Adult X-Large</td>
               <td width="15%" align="center"><?php  print $shirt['XL']; ?></td>
               <td width="15%"><input type="text" name="XL" size="5" <?php  print "value=\"".$extraShirt['XL']."\" ".$readOnly;?> ></td>
            </tr>
            <tr>
               <td width="20%">Adult XX-Large</td>
               <td width="15%" align="center"><?php  print $shirt['XX']; ?></td>
               <td width="15%"><input type="text" name="XX" size="5" <?php  print "value=\"".$extraShirt['XX']."\" ".$readOnly;?> ></td>
            </tr>
            <tr>
               <td width="20%" bgcolor="#808080"><b>Totals</b></td>
               <td width="15%" align="center" bgcolor="#808080"><b><?php  print $ParticipantTotal; ?></b></td>
               <td width="15%" bgcolor="#808080"> <?php  print $extraShirtTotal; ?> </td>
            </tr>
         </table>

         <br>
         <h2>Meal Tickets </h2>
         (<b>Note:</b> Event directors, Charmers and Board members are covered)
         <table border="1" width="60%" id="table1">
            <tr>
               <td width="20%" bgcolor="#000000"><font color="#FFFF00">Meal Ticket</font></td>
               <td width="15%" bgcolor="#000000"><font color="#FFFF00" align="center">Cost</font></td>
               <td width="15%" bgcolor="#000000"><font color="#FFFF00">Extra Orders</font></td>
            </tr>
            <tr>
               <td width="20%">Adult Meal Tickets</td>
               <td width="15%" align="center"><?php print '$'.number_format($prices['AdultMeal'],2);?></td>
               <td width="15%"><input type="text" name="AdultMeal" size="5" <?php  print "value=\"".$extraMeal['AdultMeal']."\" ".$readOnly;?> ></td>
            </tr>
            <tr>
               <td width="20%">Child Meal Tickets (7 and under)</td>
               <td width="15%" align="center"><?php print '$'.number_format($prices['ChildMeal'],2);?></td>
               <td width="15%"><input type="text" name="ChildMeal" size="5" <?php  print "value=\"".$extraMeal['ChildMeal']."\" ".$readOnly;?> ></td>
            </tr>
            <tr>
               <td width="20%" bgcolor="#808080"><b>Totals</b></td>
               <td width="15%" align="center" bgcolor="#808080"><b><?php  print $ParticipantMealTotal; ?></b></td>
               <td width="15%" bgcolor="#808080"> <?php  print $extraMealTotal; ?> </td>
            </tr>
         </table>
         <br>
         <table border="0" width="60%" id="table2">
            <tr>
               <?php if ($UserStatus == 'O')
               {?>
               <td><p align="center"><input type="submit" value="Update" name="Update"></p></td>
               <?php
               }
               ?>
            </tr>
         </table>
      </form>
      <?php footer("","")?>
    </body>
</html>
