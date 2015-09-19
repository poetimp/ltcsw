<?php
include 'include/RegFunctions.php';

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

$awardsGold   = 0;
$awardsSilver = 0;
$awardsBronze = 0;
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

    <h1 align="center">Award Counts By Individual Event</h1>
    <hr>
    <?php
         $results = mysql_query("select   e.EventName,
                                          r.Award,
                                          count(*) as AwardCount
                                 from     $RegistrationTable r,
                                          $EventsTable e
                                 where    r.EventID    = e.EventID
                                 and      r.Award     != 'No Show'
                                 and      e.TeamEvent  = 'N'
                                 group by e.EventName,
                                          r.Award")
                    or die ("Unable to get award list:" . mysql_error());
          $numRows = mysql_num_rows($results);

          ?>
         <table border="1" width="100%" id="table1">
            <tr>
               <td bgcolor="#000000"><font color="#FFFF00">Event Name</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Gold</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Silver</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Bronze</font></td>
            </tr>
         <?php
         $currentEvent = '';
         while ($row = mysql_fetch_assoc($results))
         {
            $EventName   = $row['EventName'];
            $Award       = $row['Award'];
            $AwardCount  = $row['AwardCount'];

            if ($currentEvent == '')
               $currentEvent = $EventName;

            $numRows--;
            if (($EventName != $currentEvent) or $numRows==0)
            {
               if ($numRows==0)
               {
                  if      ($Award == 'Gold')   $GoldCount   = $AwardCount;
                  else if ($Award == 'Bronze') $BronzeCount = $AwardCount;
                  else if ($Award == 'Silver') $SilverCount = $AwardCount;
               }
               ?>
               <tr>
                  <td><?php  print $currentEvent; ?></td>
                  <td><?php  print $GoldCount;    ?></td>
                  <td><?php  print $SilverCount;  ?></td>
                  <td><?php  print $BronzeCount;  ?></td>
               </tr>
               <?php

               $currentEvent = $EventName;
               $GoldCount    = '';
               $BronzeCount  = '';
               $SilverCount  = '';
            }

            if ($Award == 'Gold')
            {
               $GoldCount   = $AwardCount;
               $awardsGold += $GoldCount;
            }
               else if ($Award == 'Bronze')
            {
               $BronzeCount   = $AwardCount;
               $awardsSilver += $SilverCount;
            }
               else if ($Award == 'Silver')
            {
               $SilverCount   = $AwardCount;
               $awardsBronze += $BronzeCount;
            }
         }
         ?>
         </table>

    <h1 align="center">Award Counts By Team Event</h1>
    <hr>
    <?php
         $results = mysql_query("select distinct
                                        e.EventName,
                                        e.IndividualAwards,
                                        t.TeamID,
                                        r.Award
                                 from   $EventsTable         e,
                                        $RegistrationTable   r,
                                        $TeamsTable          t
                                 where  e.EventID=t.EventID
                                 and    r.EventID=t.EventID
                                 and    t.TeamID=r.ParticipantID")
                    or die ("Unable to get team list:" . mysql_error());

         while ($team = mysql_fetch_assoc($results))
         {

            if ($team['IndividualAwards'] =='Y')
            {
               if (isset($teamAwards[$team['EventName']][$team['Award']]))
                  $teamAwards[$team['EventName']][$team['Award']] ++;
               else
                  $teamAwards[$team['EventName']][$team['Award']]  = 1;

               $memberQry = mysql_query("select m.Award,
                                                count(*) as Count
                                         from   $TeamMembersTable  m,
                                                $TeamsTable        t
                                         where  t.TeamID=m.TeamID
                                         and    t.TeamID=".$team['TeamID']."
                                         group by m.Award")
                          or die ("Unable to get team member count:" . mysql_error());
               while ($members = mysql_fetch_assoc($memberQry))
               {
                  if (isset($teamAwards[$team['EventName']][$members['Award']]))
                     $teamAwards[$team['EventName']][$members['Award']] += $members['Count'];
                  else
                     $teamAwards[$team['EventName']][$members['Award']]  = $members['Count'];
               }
            }
            else
            {
               $memberQry = mysql_query("select count(*) as Count
                                         from   $TeamMembersTable  m,
                                                $TeamsTable        t
                                         where  t.TeamID=m.TeamID
                                         and    t.TeamID=".$team['TeamID'])
                          or die ("Unable to get team member count:" . mysql_error());
               $members = mysql_fetch_assoc($memberQry);
               if (isset($teamAwards[$team['EventName']][$team['Award']]))
                  $teamAwards[$team['EventName']][$team['Award']] += $members['Count'];
               else
                  $teamAwards[$team['EventName']][$team['Award']]  = $members['Count'];
            }

         }
         //print "<pre>";print_r($teamAwards); print "</pre>";
         ?>
         <table border="1" width="100%" id="table1">
            <tr>
               <td bgcolor="#000000"><font color="#FFFF00">Event Name</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Gold</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Silver</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Bronze</font></td>
            </tr>
         <?php
         foreach ($teamAwards as $EventName=>$Award)
         {
            $awardsGold   += $Award['Gold'];
            $awardsSilver += $Award['Silver'];
            $awardsBronze += $Award['Bronze'];
            ?>
               <tr>
                  <td><?php  print $EventName; ?></td>
                  <td><?php  print isset($Award['Gold'])   ? $Award['Gold']   : ''; ?></td>
                  <td><?php  print isset($Award['Silver']) ? $Award['Silver'] : ''; ?></td>
                  <td><?php  print isset($Award['Bronze']) ? $Award['Bronze'] : ''; ?></td>
               </tr>
               <?php
         }
         ?>
         </table>
    <hr>
    <h1 align="center">Summary Award Counts</h1>
    <hr>
    <table border="1" width="100%" id="table1">
       <tr>
          <td bgcolor="#000000"><font color="#FFFF00">Gold</font></td>
          <td bgcolor="#000000"><font color="#FFFF00">Silver</font></td>
          <td bgcolor="#000000"><font color="#FFFF00">Bronze</font></td>
       </tr>
       <tr>
          <td><?php  print $awardsGold; ?></td>
          <td><?php  print $awardsSilver; ?></td>
          <td><?php  print $awardsBronze; ?></td>
      </tr>
   </table>

   </body>

</html>