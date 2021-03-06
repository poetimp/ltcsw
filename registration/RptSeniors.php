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
          Seniors
       </title>
       <meta http-equiv="Content-Language" content="en-us" />
       <meta name="viewport" content="width=device-width, initial-scale=1.0" />
       <link rel="stylesheet" href="include/registration.css" type="text/css" />
    </head>

    <body>
    <h1 align="center">All Seniors</h1>
    <hr />
    <?php
         $results = $db->query("select   p.ParticipantID,
                                          p.FirstName,
                                          p.LastName,
                                          p.Email,
                                          p.Phone,
                                          p.Grade,
                                          p.Comments,
                                          p.Address,
                                          p.City,
                                          p.State,
                                          p.Zip,
                                          c.ChurchName
                                 from     $ParticipantsTable p,
                                          $ChurchesTable     c
                                 where    p.ChurchID = c.ChurchID
                                 and      p.Grade    = 12
                                 order by LastName,
                                          FirstName")
                    or die ("Not found:" . sqlError());
         $first = 1;
         ?>
         <table class='registrationTable'>
         <?php
         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            $ParticipantID = $row['ParticipantID'];
            $eventTypeCount=EventCounts($ParticipantID);
            $eventCount=$eventTypeCount['Team']+$eventTypeCount['Solo'];
            if ($eventCount > 0)
            {
               $Name    = $row['LastName'].", ".$row['FirstName'];
               $Email   = $row['Email'];
               $Phone   = $row['Phone'];
               $Grade   = $row['Grade'];
               $Comment = $row['Comments'];
               $Address = $row['Address'];
               $City    = $row['City'];
               $State   = $row['State'];
               $Zip     = $row['Zip'];
               $Church  = $row['ChurchName'];

               if ($Comment == '')
                  $Comment = "&nbsp;";

               if ($first == 0)
               {
               ?>
                  <tr>
                     <td colspan='3'>&nbsp;</td>
                  </tr>
               <?php
               }
               else
               {
                  $first = 0;
               }
            ?>
               <tr>
                  <th style='width: 33%'><b><?php  print $Name;  ?></b></th>
                  <th style='width: 33%'><b><?php  print $Church;?></b></th>
                  <th style='width: 33%'><b><?php  print $Phone; ?></b></th>
               </tr>
               <tr>
                  <td style='width: 33%' colspan='1'><b><?php  print "$Address<br />$City, $State $Zip"; ?></b></td>
                  <td style='width: 33%' colspan='2'><b><?php  print $Comment; ?></b></td>
               </tr>
               <?php
            }
         }
      ?>
         </table>
    </body>
</html>
