<?php
include 'include/RegFunctions.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <title>
          Participant Events
       </title>
    </head>

    <body bgcolor="White">
    <h1 align="center">LTC Participation</h1>
    <hr>
    <?php
         $results = mysql_query("select   ParticipantID,
                                          FirstName,
                                          LastName,
                                          Email,
                                          Phone,
                                          Grade
                                 from     $ParticipantsTable
                                 where    ChurchID = '$ChurchID'
                                 order by LastName,
                                          FirstName")
                    or die ("Not found:" . mysql_error());
         $first = 1;
         ?>
         <table border="0" width="100%" id="table1">
         <?php
         while ($row = mysql_fetch_assoc($results))
         {
            $ParticipantID = $row['ParticipantID'];
            $Name        = $row['LastName'].", ".$row['FirstName'];
            $Email       = $row['Email'];
            $Phone       = $row['Phone'];
            $Grade       = $row['Grade'];
            $EventCounts = EventCounts($ParticipantID);
            $TeamCount   = $EventCounts['Team'];
            $SoloCount   = $EventCounts['Solo'];

            if ($first == 0)
            {
               ?>
               <tr>
                  <td colspan=5>&nbsp;</td>
               </tr>
               <?php
            }
            else
            {
               $first = 0;
            }
            ?>
            <tr>
               <td bgcolor="#CCCCCC" width=25%><b><?php  print $Name; ?></b></td>
               <td bgcolor="#CCCCCC" width=25%><b><?php  print $Email; ?></b></td>
               <td bgcolor="#CCCCCC" width=15%><b><?php  print $Phone; ?></b></td>
               <td bgcolor="#CCCCCC" width=10%><b><?php  print "Grade: $Grade"; ?></b></td>
               <td bgcolor="#CCCCCC" width=25%><b>Award</b></td>
            </tr>
            <?php
            if ($SoloCount > 0)
            {
              $SoloEvents = mysql_query("SELECT   distinct r.EventID
                                         FROM     $RegistrationTable r,
                                                  $EventsTable       e
                                         WHERE    r.ChurchID    = $ChurchID
                                         AND      r.EventID     = e.EventID
                                         AND      e.TeamEvent   = 'N'
                                         AND      ParticipantID = $ParticipantID
                                         ORDER BY EventID")
                            or die ("Unable to get individual events:" . mysql_error());

              while ($row = mysql_fetch_assoc($SoloEvents))
              {
                $EventID = $row['EventID'];
                $evnt = mysql_query("select EventName,
                                            ConvEvent
                                     from   $EventsTable
                                     where  EventID = $EventID")
                        or die ("Not found:" . mysql_error());
                $row = mysql_fetch_assoc($evnt);
                $EventName = $row['EventName'];
                $ConvEvent = $row['ConvEvent'] == "C" ? "Convention" : "Preconvention";
                ?>
                <tr>
                    <td>&nbsp;</td>
                    <td><?php  print $EventName; ?></td>
                    <td><?php  print "Individual"; ?></td>
                    <td><?php  print $ConvEvent; ?></td>
                    <td><?php  print ParticipantAward($ParticipantID,$EventID); ?></td>
                </tr>
                <?php
              }
            }

            if ($TeamCount > 0)
            {
              $TeamEvents = mysql_query("select distinct
                                                t.EventID,
                                                t.TeamID
                                        from    $TeamsTable       t,
                                                $TeamMembersTable m
                                        where   t.TeamID        = m.TeamID
                                        and     t.ChurchID      = $ChurchID
                                        and     m.ParticipantID = $ParticipantID
                                        ")
                            or die ("Unable to get Team events:" . mysql_error());

              while ($row = mysql_fetch_assoc($TeamEvents))
              {
                $EventID = $row['EventID'];
                $TeamID  = $row['TeamID'];

                $evnt = mysql_query("select EventName,
                                            ConvEvent
                                     from   $EventsTable
                                     where  EventID = $EventID")
                        or die ("Not able to get events list:" . mysql_error());
                $row = mysql_fetch_assoc($evnt);
                $EventName = $row['EventName'];
                $ConvEvent = $row['ConvEvent'] == "C" ? "Convention" : "Preconvention";
                ?>
                <tr>
                    <td>&nbsp;</td>
                    <td><?php  print $EventName; ?></td>
                    <td><?php  print "Team: $TeamID"; ?></td>
                    <td><?php  print $ConvEvent; ?></td>
                    <td><?php  print ParticipantAward($ParticipantID,$EventID); ?></td>
                </tr>
                <?php
              }
            }

            if ($SoloCount ==0 and $TeamCount ==0)
            {
                ?>
                <tr>
                    <td width=30%>&nbsp;</td>
                    <td width=30% colspan="3"><?php  print "<b><i>Not signed up for any events</i></b>"; ?></td>
                </tr>
                <?php
            }
         }
         ?>
         </table>

    </body>

</html>