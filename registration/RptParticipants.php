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
          LTC Participants
       </title>
       <meta http-equiv="Content-Language" content="en-us">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link rel=stylesheet href="include/registration.css" type="text/css" />
    </head>

    <body>
    <?php
      $pageBreak='';
      function PrintRoster($ChurchID)
      {
         global $ChurchesTable,
                $EventsTable,
                $RegistrationTable,
                $ParticipantsTable,
                $TeamMembersTable,
                $ExtraOrdersTable,
                $pageBreak,
                $db;

         $participantList = ActiveParticipants($ChurchID);
         $first = 1;
         foreach ($participantList as $participantID=>$participantName)
         {
            $results = $db->query("select   p.Grade,
                                             CASE p.ShirtSize
                                                WHEN 'S'  THEN 'Small'
                                                WHEN 'M'  THEN 'Medium'
                                                WHEN 'YL' THEN 'Youth Large'
                                                WHEN 'XL' THEN 'Extra Large'
                                                WHEN 'LG' THEN 'Large'
                                                WHEN 'YM' THEN 'Youth Medium'
                                                WHEN 'XX' THEN 'Double X-Large'
                                                ELSE           '<Error>'
                                             END
                                             ShirtSize,
                                             CASE p.MealTicket
                                                WHEN '3' THEN 'Three Meals'
                                                WHEN '5' THEN 'Five Meals'
                                                WHEN 'N' THEN 'No Meals'
                                                WHEN 'I' THEN 'Included'
                                                ELSE          '<Error>'
                                             END
                                             MealTicket,
                                             c.ChurchName
                                    from     $ParticipantsTable p,
                                             $ChurchesTable     c
                                    where    p.ChurchID      = c.ChurchID
                                    and      p.ChurchID      = $ChurchID
                                    and      p.ParticipantID = $participantID
                                    order by p.LastName,
                                             p.FirstName"
                                 )
                     or die ("Unable to get participant information:" . sqlError());
            ?>
            <?php
            $row = $results->fetch(PDO::FETCH_ASSOC);

            $ShirtSize     = $row['ShirtSize'];
            $MealTicket    = $row['MealTicket'];
            $Grade         = $row['Grade'];
            $Church        = $row['ChurchName'];
            if ($first == 1)
            {
               $first = 0;
               ?>
               <h1 align="center" <?php print $pageBreak;$pageBreak="style=\"page-break-before:always;\"";?>="<?php print $pageBreak;$pageBreak="style=\"page-break-before:always;\"";?>"><?php print $Church; ?></h1>
               <hr />
               <table class='registrationTable'>
                  <tr>
                     <th style='width: 10%'>ID          </th>
                     <th style='width: 10%'>Grade       </th>
                     <th style='width: 25%'>T-Shirt Size</th>
                     <th style='width: 25%'>Meal Ticket </th>
                     <th style='width: 30%'>Name        </th>
                  </tr>
               <?php
            }
            ?>
            <tr>
               <td><?php  print $participantID; ?></td>
               <td><?php  print $Grade; ?></td>
               <td><?php  print $ShirtSize;  ?></td>
               <td><?php  print $MealTicket;  ?></td>
               <td><?php  print $participantName;  ?></td>
            </tr>
         <?php
         }
         ?>
         </table>
       <?php
      }

      if ($AdminReport)
      {
         $ChuchList = ChurchesRegistered();
         if (count($ChuchList) > 0)
         {
            foreach ($ChuchList as $ChurchID=>$ChurchName)
            {
               PrintRoster($ChurchID);
            }
         }
         else
         {
            ?>
               <center>
               <h1>No churches with participating registrants have been defined</h1>
               <h2>Participants report is empty</h2>
               </center>
            <?php
         }
      }
      else
      {
         PrintRoster($ChurchID);
      }
      ?>
   </body>

</html>