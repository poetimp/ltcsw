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
   </head>
   <body bgcolor="White">
      <h1 align="center">T-Shirt Orders by Congregation</h1>
      <table border="1" width="100%" id="table1">
         <tr>
            <td width="20%" bgcolor="#C0C0C0"><b>Church Name</b></td>
            <td align="center" width="10%" bgcolor="#C0C0C0"><b>YM</b></td>
            <td align="center" width="10%" bgcolor="#C0C0C0"><b>YL</b></td>
            <td align="center" width="10%" bgcolor="#C0C0C0"><b>S</b></td>
            <td align="center" width="10%" bgcolor="#C0C0C0"><b>M</b></td>
            <td align="center" width="10%" bgcolor="#C0C0C0"><b>LG</b></td>
            <td align="center" width="10%" bgcolor="#C0C0C0"><b>XL</b></td>
            <td align="center" width="10%" bgcolor="#C0C0C0"><b>XX</b></td>
            <td align="center" width="10%" bgcolor="#C0C0C0"><b>Total</b></td>
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
                   or die ("Unable to obtain Shirt List:" . sqlError($db->errorInfo()));

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
                        or die ("Unable to Read Extra Orders Table" . sqlError($db->errorInfo()));

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
               <td align="center"><?php  print $shirt['YM'];?></td>
               <td align="center"><?php  print $shirt['YL'];?></td>
               <td align="center"><?php  print $shirt['S'] ;?></td>
               <td align="center"><?php  print $shirt['M'] ;?></td>
               <td align="center"><?php  print $shirt['LG'];?></td>
               <td align="center"><?php  print $shirt['XL'];?></td>
               <td align="center"><?php  print $shirt['XX'];?></td>
               <td align="center" bgcolor="#C0C0C0"><?php  print $rowTotal   ;?></td>
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
         <tr>
            <td bgcolor="#C0C0C0">Total</td>
            <td align="center" bgcolor="#C0C0C0"><?php  print $total['YM'];?></td>
            <td align="center" bgcolor="#C0C0C0"><?php  print $total['YL'];?></td>
            <td align="center" bgcolor="#C0C0C0"><?php  print $total['S']; ?></td>
            <td align="center" bgcolor="#C0C0C0"><?php  print $total['M']; ?></td>
            <td align="center" bgcolor="#C0C0C0"><?php  print $total['LG'];?></td>
            <td align="center" bgcolor="#C0C0C0"><?php  print $total['XL'];?></td>
            <td align="center" bgcolor="#C0C0C0"><?php  print $total['XX'];?></td>
            <td align="center" bgcolor="#C0C0C0"><?php  print $total['grand'];?></td>
         </tr>
      </table>
   </body>
</html>
