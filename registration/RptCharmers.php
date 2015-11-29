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
          Charmers
       </title>
    </head>

    <body bgcolor="White">
    <h1 align="center">LTC Participants With Comments</h1>
    <hr>
    <?php
         $results = $db->query("select   p.Name,
                                          p.Phone,
                                          p.Email,
                                          c.ChurchName
                                 from     $NonParticipantsTable p,
                                          $ChurchesTable        c
                                 where    p.ChurchID = c.ChurchID
                                 order by c.ChurchName,
                                          p.Name")
                    or die ("Unable to get Charmer information:" . sqlError($db->errorInfo()));
         $first = 1;
         ?>
         <table border="1" width="100%" id="table1">
            <tr>
               <td width=30% bgcolor=#CCCCCC><b>Church</b></td>
               <td width=30% bgcolor=#CCCCCC><b>Charmer</b></td>
               <td width=30% bgcolor=#CCCCCC><b>Phone</b></td>
               <td width=10% bgcolor=#CCCCCC><b>Email</b></td>
            </tr>
         <?php
         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            $Name    = $row['Name'];
            $Email   = $row['Email'];
            $Phone   = $row['Phone'];
            $Church  = $row['ChurchName'];
            ?>
            <tr>
               <td width=30% bgcolor=#FFFFFF><?php  print $Church;?></td>
               <td width=30% bgcolor=#FFFFFF><?php  print $Name;  ?></td>
               <td width=30% bgcolor=#FFFFFF><?php  print $Phone; ?></td>
               <td width=10% bgcolor=#FFFFFF><?php  print $Email; ?></td>
            </tr>
         <?php
         }
         ?>
         </table>
    </body>
</html>