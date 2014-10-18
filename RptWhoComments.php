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
    </head>

    <body bgcolor="White">
    <h1 align="center">LTC Participants With Comments</h1>
    <hr>
    <?php
         $results = mysql_query("select   p.ParticipantID,
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
                    or die ("Unable to get participant information:" . mysql_error());
         $first = 1;
         ?>
         <table border="1" width="100%" id="table1">
         <?php
         while ($row = mysql_fetch_assoc($results))
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
               <td width=30% bgcolor=#CCCCCC><b><?php  print $Name;  ?></b></td>
               <td width=30% bgcolor=#CCCCCC><b><?php  print $Church;?></b></td>
               <td width=30% bgcolor=#CCCCCC><b><?php  print $Phone; ?></b></td>
               <td width=10% bgcolor=#CCCCCC><b><?php  print $Grade; ?></b></td>
            </tr>
            <tr>
               <td width=100% colspan=4 bgcolor=#CCCCCC><b><?php  print $Comment; ?></b></td>
            </tr>
         <?php
         }
         ?>
         </table>

    </body>

</html>