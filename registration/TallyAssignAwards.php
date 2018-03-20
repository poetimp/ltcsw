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


$EventID = isset($_REQUEST['EventID']) ? $_REQUEST['EventID'] : "";
$SchedID = isset($_REQUEST['SchedID']) ? $_REQUEST['SchedID'] : "";
$Closed=1;

if ($SchedID == "" or $EventID == "")
{
   header("refresh: 0; URL=Admin.php");
   die();
}

$results = $db->query("Select  TeamEvent,
                                IndividualAwards,
                                EventName
                        from    $EventsTable
                        where   EventID = $EventID
                       ")
           or die ("Unable to determine event type:" . sqlError());
$row = $results->fetch(PDO::FETCH_ASSOC);
$EventName        = $row['EventName'];
$TeamEvent        = ($row['TeamEvent'] == 'Y');
$IndividualAwards = ($row['IndividualAwards'] == 'Y');

if ($TeamEvent)
{

   $sql = "Select  c.ChurchName,
                   c.ChurchID,
                   concat('Team No: ',t.TeamID) Name,
                   IFNULL(r.Award,'Not Assigned') Award,
                   t.TeamID ParticipantID
           from    $RegistrationTable r,
                   $EventsTable       e,
                   $ChurchesTable     c,
                   $TeamsTable        t
           where r.EventID       = $EventID
           and   r.SchedID       = $SchedID
           and   r.ParticipantID = t.TeamID
           and   e.EventID       = r.EventID
           and   c.ChurchID      = r.ChurchID
           and   e.TeamEvent     = 'Y'
           order by c.ChurchName,t.TeamID
          ";

   $results = $db->query($sql)
              or die ("Unable to get event Member list:" . sqlError());
}
else
{
   $sql = "Select  p.ParticipantID,
                   c.ChurchName,
                   c.ChurchID,
                   concat(p.FirstName,' ',p.LastName) Name,
                   IFNULL(r.Award,'Not Assigned') Award
           from    $RegistrationTable r,
                   $ParticipantsTable p,
                   $ChurchesTable     c
           where   r.EventID       = $EventID
           and     r.SchedID       = $SchedID
           and     p.ParticipantID = r.ParticipantID
           and     c.ChurchID      = r.ChurchID
           order by c.ChurchName,p.LastName,p.FirstName
          ";

   $results = $db->query($sql)
              or die ("Unable to get event Member list:" . sqlError());
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
   <script language="JavaScript" src="include/jscriptFunctions.js"></script>
   <head>
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>
         Assign Awards
      </title>
      <meta http-equiv="Content-Language" content="en-us" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="stylesheet" href="include/registration.css" type="text/css" />
   </head>
   <body>
      <h1 align="center">Assign Awards</h1>
      <h2 align="center"><?php print $EventName?></h2>
      <hr />
      <form action="TallyAssignAwards.php?EventID=<?php print $EventID?>&SchedID=<?php
                 print $SchedID;
                 if (isset($_REQUEST['View']))
                    print "&View=".$_REQUEST['View'];?>"
            method="post">
         <table class='registrationTable'
                onmouseover="javascript:trackTableHighlight(event, '#8888FF');"
                onmouseout="javascript:highlightTableRow(0);"
         >
           <tr id="header">
               <th style='width: 2%;'>&nbsp;</th>
               <th style='width: 20%;'>Church</th>
               <th style='width: 18%;'>
                  <?php
                     if ($TeamEvent)
                        print "Team Number";
                     else
                        print "Participant";
                  ?>
               </th>
               <th style='width: 80%; text-align: center' colspan='5'>Award</th>
           </tr>
         <?php
           //--------------------------------------------------------------------
           // No loop through the events reporting on the details
           //--------------------------------------------------------------------
           while ($row = $results->fetch(PDO::FETCH_ASSOC))
           {
               $ChurchName      = $row['ChurchName'];
               $ChurchID        = $row['ChurchID'];
               $ParticipantID   = $row['ParticipantID'];
               $PatricipantName = $row['Name'];
               $Award           = $row['Award'];

               if ($TeamEvent)
               {
                  $memberCnt = $db->query("select count(*) count
                                            from   $TeamMembersTable  m
                                            where  m.TeamID = $ParticipantID
                                            ")
                  or die ("Unable to get event Team Member list:" . sqlError());
                  $row = $memberCnt->fetch(PDO::FETCH_ASSOC);
                  $MemberCount      = $row['count'];
               }
               else
                  $MemberCount = 0;

               if ((!$TeamEvent) or ($TeamEvent and $MemberCount > 0))
               {
                  $RadioName = 'Award_'.$EventID.'_'.$ParticipantID.'_'.$SchedID;

                  $Gold_Checked   = "";
                  $Silver_Checked = "";
                  $Bronze_Checked = "";
                  $None_Checked   = "";
                  $Noshow_Checked = "";


                  if (isset($_POST['Submit']))
                  {
                      if (isset($_POST[$RadioName]))
                      {
                        $Award = $_POST[$RadioName];

                        $db->query("update  $RegistrationTable
                                     set     Award         = '$Award'
                                     where   EventID       = $EventID
                                     and     SchedID       = $SchedID
                                     and     ParticipantID = $ParticipantID
                                    ")
                        or die ("Unable to Update Award Value:" . sqlError());
                      }
                  }

                  if ($Award == 'Gold')
                  {
                     $Gold_Checked   = 'checked';
                     $flagColor      = 'yellow';
                  }
                  else if ($Award == 'Silver')
                  {
                     $Silver_Checked = 'checked';
                     $flagColor      = 'silver';
                  }
                  else if ($Award == 'Bronze')
                  {
                     $Bronze_Checked = 'checked';
                     $flagColor      = '#DAA520';
                  }
                  else if ($Award == 'No Award')
                  {
                     $None_Checked   = 'checked';
                     $flagColor      = 'white';
                  }
                  else if ($Award == 'No Show')
                  {
                     $Noshow_Checked = 'checked';
                     $flagColor      = 'black';
                  }
                  else
                     $flagColor = 'red';



                  if ($TeamEvent and !$IndividualAwards)
                  {
                         $ViewMembers = "&nbsp;&nbsp;<input id=\"team_$ParticipantID\"
                                                type=button
                                                onclick=\"javascript:show_team('$ParticipantID')\"
                                                value=\"(open)\">";
                  }
                  else
                      $ViewMembers = "";

                  ?>
                  <tr>
                    <td style='width: 2%' id="preserve" bgcolor="<?php print $flagColor?>">
                        &nbsp;
                    </td>
                    <td style='width: 28%;'><?php print $ChurchName;?></td>
                    <td style='width: 20%;'><?php print "$ViewMembers$PatricipantName";?></td>
                    <td style='width: 10%;'>
                        <input type="radio" value="Gold" name=<?php print "\"$RadioName\" $Gold_Checked";?> />
                          Gold
                    </td>
                    <td style='width: 10%;'>
                        <input type="radio" value="Silver" name=<?php print "\"$RadioName\" $Silver_Checked";?> />
                          Silver
                    </td>
                    <td style='width: 10%;'>
                        <input type="radio" value="Bronze" name=<?php print "\"$RadioName\" $Bronze_Checked";?> />
                          Bronze
                    </td>
                    <td style='width: 10%;'>
                        <input type="radio" value="No Award" name=<?php print "\"$RadioName\" $None_Checked";?> />
                          No Award
                    </td>
                    <td style='width: 10%;'>
                        <input type="radio" value="No Show" name=<?php print "\"$RadioName\" $Noshow_Checked";?> />
                          No Show
                    </td>
                  </tr>
                  <?php
//                  if ($IndividualAwards or ($TeamEvent and isset($_REQUEST['View']) and $_REQUEST['View'] == $ParticipantID))
//                  {
                      $ExpandTeam = $ParticipantID;
                      $memberList = $db->query("select concat(p.FirstName,' ',p.LastName) MemberName,
                                                        m.ParticipantID MemberID,
                                                        m.Award
                                                 from   $ParticipantsTable p,
                                                        $TeamMembersTable  m
                                                 where  m.TeamID        = $ExpandTeam
                                                 and    p.ParticipantID = m.ParticipantID
                                                 order by p.LastName
                                                ")
                                    or die ("Unable to get event Team Member list:" . sqlError());
                      $MemberIndex=0;
                      while ($row = $memberList->fetch(PDO::FETCH_ASSOC))
                      {
                        $MemberName      = $row['MemberName'];
                        $MemberID        = $row['MemberID'];
                        $Solo_Award      = $row['Award'];

                        if ($IndividualAwards)
                        {
                        ?>
                           <tr id="team_<?php print $ParticipantID."_".$MemberIndex; $MemberIndex++;?>">
                        <?php
                        }
                        else
                        {
                        ?>
                           <tr id="team_<?php print $ParticipantID."_".$MemberIndex; $MemberIndex++;?>" style="display:none">
                        <?php
                        }

                          if (!$IndividualAwards)
                          {
                          ?>
                             <td style='width: 2%' id="preserve" bgcolor="<?php print $flagColor?>">
                                &nbsp;
                             </td>
                             <td colspan="1">&nbsp;</td>
                             <td colspan="6"><?php print $MemberName?></td>
                          <?php
                          }
                          else
                          {
                             $Solo_Gold_Checked   = "";
                             $Solo_Silver_Checked = "";
                             $Solo_Bronze_Checked = "";
                             $Solo_None_Checked   = "";
                             $Solo_Noshow_Checked = "";

                             $TeamID     = $ParticipantID;

                             $RadioSolo           = 'Solo_'.$EventID.'_'.$MemberID.'_'.$SchedID;

                             if (isset($_POST[$RadioSolo]))
                             {
                                $Solo_Award = $_POST[$RadioSolo];

                                $db->query("update  $TeamMembersTable
                                             set     Award         = '$Solo_Award'
                                             where   TeamID        = $TeamID
                                             and     ParticipantID = $MemberID
                                            ")
                                or die ("Unable to Update Team Individual Award Value:" . sqlError());
                               }

                              if ($Solo_Award == 'Gold')
                              {
                                 $Solo_Gold_Checked   = 'checked';
                                 $Solo_flagColor      = '"yellow"';
                              }
                              else if ($Solo_Award == 'Silver')
                              {
                                 $Solo_Silver_Checked = 'checked';
                                 $Solo_flagColor      = '"silver"';
                              }
                              else if ($Solo_Award == 'Bronze')
                              {
                                 $Solo_Bronze_Checked = 'checked';
                                 $Solo_flagColor      = '"#DAA520"';
                              }
                              else if ($Solo_Award == 'No Award')
                              {
                                 $Solo_None_Checked   = 'checked';
                                 $Solo_flagColor      = '"white"';
                              }
                              else if ($Solo_Award == 'No Show')
                              {
                                 $Solo_Noshow_Checked = 'checked';
                                 $Solo_flagColor      = '"black"';
                              }
                              else
                                 $Solo_flagColor = '"red"';
                          ?>
                             <td id='preserve' bgcolor="<?php print $Solo_flagColor?>">&nbsp;</td>
                             <td>&nbsp;</td>
                             <td><?php print $MemberName?></td>
                             <td style='width: 10%;'>
                                <input type="radio" value="Gold" name="<?php print "\"$RadioSolo\" $Solo_Gold_Checked";?>" />
                                   Gold
                             </td>
                             <td style='width: 10%;'>
                                <input type="radio" value="Silver" name="<?php print "\"$RadioSolo\" $Solo_Silver_Checked";?>" />
                                   Silver
                             </td>
                             <td style='width: 10%;'>
                                <input type="radio" value="Bronze" name="<?php print "\"$RadioSolo\" $Solo_Bronze_Checked";?>" />
                                   Bronze
                             </td>
                             <td style='width: 10%;'>
                                <input type="radio" value="No Award" name="<?php print "\"$RadioSolo\" $Solo_None_Checked";?>" />
                                   No Award
                             </td>
                             <td style='width: 10%;'>
                                <input type="radio" value="No Show" name="<?php print "\"$RadioSolo\" $Solo_Noshow_Checked";?>" />
                                   No Show
                             </td>
                          <?php
                          }
                          ?>
                        </tr>
                        <?php
                    }
              }
           }
         ?>
         </table>
         <p align="center"><input type="submit" name="Submit" value="Update" /></p>
      </form>
   <?php footer("Return to Event List","TallyEventList.php")?>
   </body>
</html>
