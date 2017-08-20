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
         T-Shirts by Congregation
      </title>
      <meta http-equiv="Content-Language" content="en-us">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel=stylesheet href="include/registration.css" type="text/css" />
   </head>
   <body>
      <h1 style='text-align: center'>T-Shirt Orders by Congregation</h1>
      <table class='registrationTable' id="table1">
         <tr>
            <th style='width: 20%'><b>Church Name</b></th>
            <th style='width: 10%; text-align: center'><b>YM</b></th>
            <th style='width: 10%; text-align: center'><b>YL</b></th>
            <th style='width: 10%; text-align: center'><b>S </b></th>
            <th style='width: 10%; text-align: center'><b>M </b></th>
            <th style='width: 10%; text-align: center'><b>LG</b></th>
            <th style='width: 10%; text-align: center'><b>XL</b></th>
            <th style='width: 10%; text-align: center'><b>XX</b></th>
            <th style='width: 10%; text-align: center'><b>Total</b></th>
         </tr>
   <?php
//=================================================================================================
//
//=================================================================================================
      $total['YM'] = 0;
      $total['YL'] = 0;
      $total['S']  = 0;
      $total['M']  = 0;
      $total['LG'] = 0;
      $total['XL'] = 0;
      $total['XX'] = 0;
      $total['grand'] = 0;
      function ShirtsNeeded($ChurchID,$ChurchName)
      {
         global $total;
         global $ChurchesTable,
                $EventsTable,
                $RegistrationTable,
                $ParticipantsTable,
                $TeamMembersTable,
                $ExtraOrdersTable,
                $db;
         //====================================================================
         // Generate a sql "in" clause from the list of active participants
         //====================================================================
         $participantList = ActiveParticipants($ChurchID);
         $inClause = "(0,";
         foreach ($participantList as $participantID=>$participantName)
            $inClause.="$participantID,";
         $inClause=trim($inClause,",").")";
         //====================================================================
         // Get shirt count from Participants list
         //====================================================================
 //        print "<br><pre>select   distinct
 //                                        p.ShirtSize,
 //                                        count(p.ShirtSize) ShirtCount
 //                               from     $ParticipantsTable p
 //                               where    p.ChurchID=$ChurchID
 //                               and      p.ParticipantID in $inClause
 //                               group by p.ShirtSize
 //                              </pre>";
         $shirts = $db->query("select   distinct
                                         p.ShirtSize,
                                         count(p.ShirtSize) ShirtCount
                                from     $ParticipantsTable p
                                where    p.ChurchID=$ChurchID
                                and      p.ParticipantID in $inClause
                                group by p.ShirtSize
                               ")
                   or die ("Unable to obtain Shirt List:" . sqlError());

         //====================================================================
         // Start with zero counts
         //====================================================================
         $shirt['YM'] = 0;
         $shirt['YL'] = 0;
         $shirt['S']  = 0;
         $shirt['M']  = 0;
         $shirt['LG'] = 0;
         $shirt['XL'] = 0;
         $shirt['XX'] = 0;
         $rowTotal    = 0;
         //====================================================================
         // Capture counts for shirts and sum the total
         //====================================================================
         while ($row = $shirts->fetch(PDO::FETCH_ASSOC))
         {
            $shirt[$row['ShirtSize']]  = $row['ShirtCount'];
            $total[$row['ShirtSize']] += $row['ShirtCount'];
            $rowTotal                 += $row['ShirtCount'];
            $total['grand']           += $row['ShirtCount'];
         }

         //====================================================================
         // Add in the counts for extra orders for shirts and sum the total
         //====================================================================
         $extraOrders = $db->query("select   ItemType,
                                              ItemCount
                                     from     $ExtraOrdersTable
                                     where    ChurchID = $ChurchID
                                     and      ItemType in ('YM','YL','S','M','LG','XL','XX')
                                     ")
                        or die ("Unable to Read Extra Orders Table" . sqlError());

         while ($row = $extraOrders->fetch(PDO::FETCH_ASSOC))
         {
            $shirt[$row['ItemType']] += $row['ItemCount'];
            $total[$row['ItemType']] += $row['ItemCount'];
            $rowTotal                += $row['ItemCount'];
            $total['grand']          += $row['ItemCount'];
         }
         //====================================================================
         // Print the table row with the data we just collected
         //====================================================================
         ?>
            <tr>
               <td ><?php  print $ChurchName; ?></td>
               <td style='text-align: center'><?php  print $shirt['YM'];?></td>
               <td style='text-align: center'><?php  print $shirt['YL'];?></td>
               <td style='text-align: center'><?php  print $shirt['S'] ;?></td>
               <td style='text-align: center'><?php  print $shirt['M'] ;?></td>
               <td style='text-align: center'><?php  print $shirt['LG'];?></td>
               <td style='text-align: center'><?php  print $shirt['XL'];?></td>
               <td style='text-align: center'><?php  print $shirt['XX'];?></td>
               <td style='text-align: center'><?php  print $rowTotal   ;?></td>
            </tr>
         <?php
         }
         //=================================================================================================
         // Calculate the T-Shirt order for each registered church and print a line in a table
         //=================================================================================================
            $ChuchList = ChurchesRegistered();
            foreach ($ChuchList as $ChurchID=>$ChurchName)
               ShirtsNeeded($ChurchID,$ChurchName);

         //=================================================================================================
         // Print the totals
         //=================================================================================================
         ?>
      </table>
      <table class='registrationTable' id="table2">
         <tr>
            <td style='width: 20%;'>Total</td>
            <td style='width: 10%; text-align: center'><?php  print $total['YM'];?></td>
            <td style='width: 10%; text-align: center'><?php  print $total['YL'];?></td>
            <td style='width: 10%; text-align: center'><?php  print $total['S']; ?></td>
            <td style='width: 10%; text-align: center'><?php  print $total['M']; ?></td>
            <td style='width: 10%; text-align: center'><?php  print $total['LG'];?></td>
            <td style='width: 10%; text-align: center'><?php  print $total['XL'];?></td>
            <td style='width: 10%; text-align: center'><?php  print $total['XX'];?></td>
            <td style='width: 10%; text-align: center'><?php  print $total['grand'];?></td>
         </tr>
      </table>
   </body>
</html>
