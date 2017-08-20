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
$pageBreak='';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <title>
          Event Roster
       </title>
       <meta http-equiv="Content-Language" content="en-us">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link rel=stylesheet href="include/registration.css" type="text/css" />
    </head>

    <body>
    <h1 align="center">Event Rosters</h1>
    <hr>
    <?php
       //--------------------------------------------------------------------
       // First collect a list of all of the events
       //--------------------------------------------------------------------
       $eventList = $db->query("select   EventID,
                                          EventName,
                                          CASE ConvEvent
                                             WHEN 'C' THEN 'Convention'
                                             WHEN 'P' THEN 'Preconvention'
                                             ELSE          'Other'
                                          END
                                          ConvEvent,
                                          CASE TeamEvent
                                             WHEN 'Y' THEN 'Team'
                                             WHEN 'N' THEN 'Individual'
                                             ELSE          'Other'
                                          END
                                          EventType
                                 from     $EventsTable
                                 order by EventName
                                ")
                    or die ("Unable to get event list:" . sqlError());
       $first = 1;
       ?>
       <table class='registrationTable' id="table1">
       <?php
       while ($row = $eventList->fetch(PDO::FETCH_ASSOC))
       {
          $EventID   = $row['EventID'];
          $EventName = $row['EventName'];
          $ConvEvent = $row['ConvEvent'];
          $EventType = $row['EventType'];

          //-----------------------------------------------------------------
          // See how many people are in the event being reported either for
          // All churches or for a particular church
          //-----------------------------------------------------------------
          if ($AdminReport)
          {
             $select = "select count(*) as count
                        from   $RegistrationTable
                        where  EventID = '$EventID'";
          }
          else
          {
             $select = "select count(*) as count
                        from   $RegistrationTable
                        where  ChurchID = '$ChurchID'
                        and    EventID  = '$EventID'";
          }

          $cntResult = $db->query($select) or die ("Unable to get registration count for event:" . sqlError());
          $cntRow    = $cntResult->fetch(PDO::FETCH_ASSOC);
          $numEvents = $cntRow['count'];

          //-------------------------------------------------------------------
          // If there are participants in this event, report them
          //-------------------------------------------------------------------
          if ($numEvents > 0)
          {
             //----------------------------------------------------------------
             // If this is not the first row and the start of a new event, print
             // a blank row
             //-------------------------------------------------------------------
             if ($first == 0)
             {
             ?>
                <tr>
                   <td>&nbsp;</td>
                   <td>&nbsp;</td>
                   <td>&nbsp;</td>
                   <td>&nbsp;</td>
                </tr>
             <?php
             }
             else
             {
                $first = 0;
             }
// ParticipantAward($ParticipantID,$EventID)
             //----------------------------------------------------------------
             // Print the vitals for this event as a heading
             //-------------------------------------------------------------------
             if ($AdminReport)
                print "<tr><td colspan=5><div $pageBreak>&nbsp;</div></td></tr>";
                $pageBreak="style=\"page-break-before:always;\"";
             ?>
             <tr>
                <th><b><?php  print $EventName ; ?></b></th>
                <th><b><?php  print $ConvEvent; ?></b></th>
                <th><b><?php  print $EventType; ?></b></th>
                <th><b><?php  print $AdminReport ? "Church" : "Award"?></b></th>
                <th><b><?php  print $AdminReport ? "Award" : "&nbsp;"; ?></b></th>
             </tr>
             <?php
             //----------------------------------------------------------------
             // Determine the proper select statement. If it is an admin report,
             // report all churches otherwise limit to current church. If it is a
             // team or solo event these also change the Select. In all cases this
             // statement will return a list of participants associated with the
             // event.
             //-------------------------------------------------------------------
                if ($AdminReport)
                {
                   if ($EventType == 'Team')
                   {
                      $sql = "SELECT p.FirstName,
                                     p.LastName,
                                     p.ParticipantID,
                                     p.Phone,
                                     p.Email,
                                     p.Grade,
                                     c.ChurchName,
                                     t.TeamID
                              FROM   $ParticipantsTable p,
                                     $RegistrationTable r,
                                     $TeamMembersTable  t,
                                     $ChurchesTable     c
                              WHERE  p.ChurchID      = r.ChurchID
                              AND    r.ParticipantID = t.TeamID
                              AND    p.ParticipantID = t.ParticipantID
                              AND    r.EventID       = $EventID
                              AND    c.ChurchID      = r.ChurchID
                              order by t.TeamID,p.LastName";
                   }
                   else
                   {
                      $sql = "SELECT p.FirstName,
                                     p.LastName,
                                     p.ParticipantID,
                                     p.Phone,
                                     p.Email,
                                     p.Grade,
                                     c.ChurchName
                              FROM   $ParticipantsTable p,
                                     $RegistrationTable r,
                                     $ChurchesTable c
                              WHERE  p.ChurchID = r.ChurchID
                              AND    p.ChurchID = c.ChurchID
                              AND    p.ParticipantID = r.ParticipantID
                              AND    r.EventID = $EventID
                               order by p.LastName";
                  }
               }
               else
               {
                  if ($EventType == 'Team')
                  {
                     $sql = "SELECT p.FirstName,
                                    p.LastName,
                                    p.ParticipantID,
                                    p.Phone,
                                    p.Email,
                                    p.Grade,
                                    t.TeamID
                             FROM   $ParticipantsTable p,
                                    $RegistrationTable r,
                                    $TeamMembersTable  t
                             WHERE  p.ChurchID      = r.ChurchID
                             AND    r.ParticipantID = t.TeamID
                             AND    p.ParticipantID = t.ParticipantID
                             AND    r.EventID       = $EventID
                             AND    r.ChurchID      = $ChurchID
                             order by t.TeamID,p.LastName";
                  }
                  else
                  {
                     $sql = "SELECT p.FirstName,
                                    p.LastName,
                                    p.ParticipantID,
                                    p.Phone,
                                    p.Email,
                                    p.Grade
                             FROM   $ParticipantsTable p,
                                    $RegistrationTable r
                             WHERE  p.ChurchID = r.ChurchID
                             AND    p.ParticipantID = r.ParticipantID
                             AND    r.EventID = $EventID
                             AND    r.ChurchID = '$ChurchID'
                             order by p.LastName";
                  }
               }
               $members = $db->query($sql) or die ("Unable to obtain event participant list:" . sqlError());

               //--------------------------------------------------------------
               // Now print the details of each participant in the event
               //--------------------------------------------------------------
               $prevTeamID="";
               while ($row = $members->fetch(PDO::FETCH_ASSOC))
               {
                  $ParticipantID = $row['ParticipantID'];
                  $Name   = $row['LastName'].", ".$row['FirstName'];
                  $Email  = $row['Email'];
                  $Phone  = $row['Phone'];
                  $Grade  = $row['Grade'];
                  $TeamID = isset($row['TeamID']) ?  $row['TeamID'] : "";

                  if ($AdminReport)
                  {
                     $ChurchName = $row['ChurchName'];
                  }
                  if ($EventType == 'Team' and $prevTeamID != $TeamID)
                  {
                     print "<tr>";
                     print "   <td colspan=1><b>Team: $TeamID</b></td>";
                     print "   <td colspan=3>&nbsp;</td>";
                     print "</tr>";
                     $prevTeamID=$TeamID;
                  }
                  ?>
                  <tr>
                     <td><?php  print "$Name ($Grade)"; ?></td>
                     <td><?php  print $Email; ?></td>
                     <td><?php  print $Phone; ?></td>
                     <td><?php  print $AdminReport ? $ChurchName : ParticipantAward($ParticipantID,$EventID); ?></td>
                     <td><?php  print $AdminReport ? ParticipantAward($ParticipantID,$EventID) : "&nbsp;"; ?></td>
                  </tr>
                  <?php
               }
            }
         }
         ?>
         </table>

    </body>

</html>
