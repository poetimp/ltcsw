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
       <meta http-equiv="Content-Language" content="en-us">
       <title>
          Award Counts
       </title>
    </head>

    <body bgcolor="White">
    <h1 align="center">Summary Award Counts</h1>
    <hr>
    <?php
         $results = mysql_query("select Award,
                                        count(*) AwardCount
                                 from   LTC_PHX_Registration
                                 where  Award != 'No Award'
                                 and    Award != 'No Show'
                                 and    Award IS NOT NULL
                                 group  by Award
                                 order  BY Award")
                    or die ("Unable to get summary award list:" . mysql_error());
         ?>
         <table border="1" width="100%" id="table1">
            <tr>
               <td bgcolor="#000000"><font color="#FFFF00">Gold</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Silver</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Bronze</font></td>
            </tr>
         <?php
         while ($row = mysql_fetch_assoc($results))
         {
            $Award       = $row['Award'];
            $AwardCount  = $row['AwardCount'];

            if ($Award == 'Gold')
               $GoldCount = $AwardCount;
            else if ($Award == 'Bronze')
               $BronzeCount = $AwardCount;
            else if ($Award == 'Silver')
            {
               $SilverCount = $AwardCount;
               ?>
               <tr>
                  <td><?php  print $GoldCount; ?></td>
                  <td><?php  print $SilverCount; ?></td>
                  <td><?php  print $BronzeCount; ?></td>
               </tr>
               <?php
            }
         }
         ?>
         </table>

        <h1 align="center">Award Counts By Church</h1>
    <hr>
    <?php
         $results = mysql_query("select ChurchName,
                                        Award,
                                        count(*) AwardCount
                                 from   LTC_PHX_Registration r,
                                        LTC_PHX_Churches c
                                 where  Award != 'No Award'
                                 and    Award != 'No Show'
                                 and    Award IS NOT NULL
                                 and    r.ChurchID = c.ChurchID
                                 group  BY ChurchName,Award")
                    or die ("Unable to get award list by church:" . mysql_error());
         ?>
         <table border="1" width="100%" id="table1">
            <tr>
               <td bgcolor="#000000"><font color="#FFFF00">Church Name</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Gold</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Silver</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Bronze</font></td>
                           </tr>
         <?php
         while ($row = mysql_fetch_assoc($results))
         {
            $ChurchName  = $row['ChurchName'];
            $Award       = $row['Award'];
            $AwardCount  = $row['AwardCount'];

            if ($Award == 'Gold')
               $GoldCount = $AwardCount;
            else if ($Award == 'Bronze')
               $BronzeCount = $AwardCount;
            else if ($Award == 'Silver')
            {
               $SilverCount = $AwardCount;
            ?>
               <tr>
                  <td><?php  print $ChurchName; ?></td>
                  <td><?php  print $GoldCount; ?></td>
                  <td><?php  print $SilverCount; ?></td>
                  <td><?php  print $BronzeCount; ?></td>
               </tr>
               <?php
            }
         }
         ?>
         </table>

        <h1 align="center">Award Counts By Event</h1>
    <hr>
    <?php
         $results = mysql_query("select   e.EventName,
                                          r.Award,
                                          count(*) as AwardCount
                                 from     $RegistrationTable r,
                                          $EventsTable e
                                 where    r.EventID=e.EventID
                                 and      r.Award != 'No Show'
                                 group by e.EventName,
                                          r.Award")
                    or die ("Unable to get award list:" . mysql_error());
         ?>
         <table border="1" width="100%" id="table1">
            <tr>
               <td bgcolor="#000000"><font color="#FFFF00">Event Name</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Gold</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Silver</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Bronze</font></td>
            </tr>
         <?php
         while ($row = mysql_fetch_assoc($results))
         {
            $EventName   = $row['EventName'];
            $Award       = $row['Award'];
            $AwardCount  = $row['AwardCount'];

            if ($Award == 'Gold')
               $GoldCount = $AwardCount;
            else if ($Award == 'Bronze')
               $BronzeCount = $AwardCount;
            else if ($Award == 'Silver')
            {
               $SilverCount = $AwardCount;
            ?>
               <tr>
                  <td><?php  print $EventName; ?></td>
                  <td><?php  print $GoldCount; ?></td>
                  <td><?php  print $SilverCount; ?></td>
                  <td><?php  print $BronzeCount; ?></td>
               </tr>
               <?php
            }
         }
         ?>
         </table>

    </body>

</html>