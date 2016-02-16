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

if ($SchedID == "" or $EventID == "")
{
   header("refresh: 0; URL=Admin.php");
   die();
}

$results = $db->query("Select  TeamEvent,
                                EventName,
                                IndividualAwards
                        from    $EventsTable
                        where   EventID = $EventID
                       ")
           or die ("Unable to determine event type:" . sqlError());
$row = $results->fetch(PDO::FETCH_ASSOC);
$EventName        = $row['EventName'];
$TeamEvent        = ($row['TeamEvent']        == 'Y');
$IndividualAwards = ($row['IndividualAwards'] == 'Y');

if ($TeamEvent)
{

   $results = $db->query("Select  c.ChurchName,
                                   c.ChurchID,
                                   concat('Team No: ',t.TeamID) Name,
                                   IFNULL(Award,'Not Assigned') Award,
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
                          ")
              or die ("Unable to get event Member list:" . sqlError());
}
else
{
   $results = $db->query("Select  p.ParticipantID,
                                   c.ChurchName,
                                   c.ChurchID,
                                   concat(p.FirstName,' ',p.LastName) Name,
                                   IFNULL(Award,'Not Assigned') Award
                           from    $RegistrationTable r,
                                   $EventsTable       e,
                                   $ParticipantsTable p,
                                   $ChurchesTable     c
                           where   r.EventID       = $EventID
                           and     r.SchedID       = $SchedID
                           and     p.ParticipantID = r.ParticipantID
                           and     e.EventID       = r.EventID
                           and     c.ChurchID      = r.ChurchID
                           and     e.TeamEvent    != 'Y'
                           order by c.ChurchName,p.LastName,p.FirstName
                          ")
              or die ("Unable to get event Member list:" . sqlError());
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
   <head>
      <title>
         Awards Report
      </title>
   </head>
   <body>
         <?php
         //--------------------------------------------------------------------
         // No loop through the events reporting on the details
         //--------------------------------------------------------------------
         $prevChurchName='';
         $pageBreak='';
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
               $row         = $memberCnt->fetch(PDO::FETCH_ASSOC);
               $MemberCount = $row['count'];
            }
            else
               $MemberCount = 0;

            if ((!$TeamEvent) or ($TeamEvent and $MemberCount > 0))
            {
               if ($prevChurchName != $ChurchName)
               {
                  if ($prevChurchName != '')
                  {
                  ?>
                     </table>
                  <?php
                  }
                  ?>
                  <div <?php print $pageBreak;$pageBreak="style=\"page-break-before:always;\"";?>>
                  <h1 align="center">Awards Report</h1>
                  <h2 align="center"><?php print $EventName?></h2>
                  <?php
                     if (isset($_REQUEST['Warn']))
                        print "<h2 align=\"center\">*** Warning there are entries without awards Assigned ***</h2>"
                  ?>
                  <hr>
                  <table border="0" width="100%">
               <?php
                  $prevChurchName = $ChurchName;
               }
               ?>
               <tr>
                  <TD colspan="4">&nbsp;</TD>
               </tr>
               <tr>
                  <TD colspan="4">&nbsp;</TD>
               </tr>
               <tr>
               <TD width="30%" bgcolor="#C0C0C0"><b><?php print $ChurchName;?></b></TD>
               <TD width="30%" bgcolor="#C0C0C0"><b><?php print $EventName;?></b></TD>
               <TD width="25%" bgcolor="#C0C0C0"><b><?php print $PatricipantName;?></b></TD>
               <TD width="15%" bgcolor="#C0C0C0"><b><?php print $Award;?></b></TD>
               <tr>

            <?php
               if ($TeamEvent)
               {
                  $memberList = $db->query("select concat(p.FirstName,' ',p.LastName) MemberName,
                                                    IFNULL(m.Award,'Not Assigned') Award
                                             from   $ParticipantsTable p,
                                                    $TeamMembersTable  m
                                             where  m.TeamID        = $ParticipantID
                                             and    p.ParticipantID = m.ParticipantID
                                             order by p.LastName
                                            ")
                                or die ("Unable to get event Team Member list:" . sqlError());
                  while ($row = $memberList->fetch(PDO::FETCH_ASSOC))
                  {
                     $MemberName      = $row['MemberName'];
                  ?>
                     <tr>
                        <td></td>
                        <td><?php print $MemberName?></td>
                        <?php
                        if ($IndividualAwards)
                        {
                           $Award = $row['Award'];
                        ?>
                           <td><?php print $Award?> </td>
                        <?php
                        }
                        else
                        {
                        ?>
                           <td>&nbsp;</td>
                        <?php
                        }
                        ?>
                     </tr>
                  <?php
                  }
               }
            }
         }
         ?>
   </body>
</html>
