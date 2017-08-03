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
          Participants with Comments
       </title>
       <meta http-equiv="Content-Language" content="en-us">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link rel=stylesheet href="include/registration.css" type="text/css" />
    </head>

    <body bgcolor="White">
    <h1 align="center">LTC Participants With Comments</h1>
    <hr>
    <?php
         $results = $db->query("select   p.ParticipantID,
                                          p.FirstName,
                                          p.LastName,
                                          p.Email,
                                          p.Phone,
                                          p.Grade,
                                          p.Comments,
                                          c.ChurchName
                                 from     $ParticipantsTable p,
                                          $ChurchesTable     c
                                 where    p.ChurchID = c.ChurchID
                                 and      Comments != ''
                                 order by LastName,
                                          FirstName")
                    or die ("Unable to get participant information:" . sqlError());
         $first = 1;
         ?>
         <table class='registrationTable' style='width: 95%' id="table1">
         <?php
         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            $ParticipantID = $row['ParticipantID'];
            $Name    = $row['LastName'].", ".$row['FirstName'];
            $Email   = $row['Email'];
            $Phone   = $row['Phone'];
            $Grade   = $row['Grade'];
            $Comment = $row['Comments'];
            $Church  = $row['ChurchName'];
            if ($first == 0)
            {
               ?>
               <tr>
                  <td colspan=4>&nbsp;</td>
               </tr>
               <?php
            }
            else
            {
               $first = 0;
            }
            ?>
            <tr>
               <th style='width: 30%'><b><?php  print $Name;  ?></b></th>
               <th style='width: 30%'><b><?php  print $Church;?></b></th>
               <th style='width: 30%'><b><?php  print $Phone; ?></b></th>
               <th style='width: 30%'><b><?php  print $Grade; ?></b></th>
            </tr>
            <tr>
               <td  style='width: 100%' colspan=4><b><?php  print $Comment; ?></b></td>
            </tr>
         <?php
         }
         ?>
         </table>

    </body>

</html>