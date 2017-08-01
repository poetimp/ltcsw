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
          Participants with Events
       </title>
    </head>

    <body bgcolor="White">
    <h1 align="center">LTC Participation</h1>
    <hr>
    <?php
    //=========================================================================
    // This is the driver select. It will select all of the participants
    //=========================================================================
         $results = $db->query("select   ParticipantID,
                                          FirstName,
                                          LastName,
                                          Grade,
                                          ChurchID
                                 from     $ParticipantsTable
                                 order by LastName,
                                          FirstName")
                    or die ("Unable to get participant List:" . sqlError());
         $first = 1;
         ?>
         <table class='registrationTable' border="0" width="100%">
         <?php
       //======================================================================
       // For each participant in LTC ...
       //======================================================================
         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            $ParticipantID = $row['ParticipantID'];
            $Name          = $row['LastName'].", ".$row['FirstName'];
            $Grade         = $row['Grade'];
            $ChurchID      = $row['ChurchID'];
            $ChurchName    = ChurchName($ChurchID);
            $EventCounts   = EventCounts($ParticipantID);
            $TeamCount     = $EventCounts['Team'];
            $SoloCount     = $EventCounts['Solo'];

          //===================================================================
          // Avoid printing a blank line at the very top but do print a blank
          // line between participants
          //======================================================================
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
               <td width=30%><b><?php  print $Name;           ?></b></td>
               <td width=30%><b><?php  print $ChurchName;     ?></b></td>
               <td width=30%><b><?php  print "Grade: $Grade"; ?></b></td>
               <td width=10%><b><?php  print "&nbsp;";        ?></b></td>
            </tr>
            <?php
            //======================================================================
            // Get the list of individual events participant is in
            //======================================================================
            if ($SoloCount > 0)
            {
               $SoloEvents = $db->query("SELECT   distinct r.EventID
                                          FROM     $RegistrationTable r,
                                                   $EventsTable       e
                                          WHERE    r.ChurchID    = $ChurchID
                                          AND      r.EventID     = e.EventID
                                          AND      e.TeamEvent   = 'N'
                                          AND      ParticipantID = $ParticipantID
                                          ORDER BY EventID")
               or die ("Unable to get individual events:" . sqlError());

               //======================================================================
               // List the individual events participant is in
               //======================================================================
               while ($row = $SoloEvents->fetch(PDO::FETCH_ASSOC))
               {
                 $EventID = $row['EventID'];
                 $evnt = $db->query("select EventName,
                                             ConvEvent
                                      from   $EventsTable
                                      where  EventID = $EventID
                                     ")
                         or die ("Unable to get Solo Event Name:" . sqlError());
                 $row = $evnt->fetch(PDO::FETCH_ASSOC);
                 $EventName = $row['EventName'];
                 $ConvEvent = $row['ConvEvent'] == "C" ? "Convention" : "Preconvention";
                 ?>
                 <tr>
                     <td width=30%>&nbsp;</td>
                     <td width=30%><?php  print $EventName; ?></td>
                     <td width=30%><?php  print "Individual"; ?></td>
                     <td width=10%><?php  print $ConvEvent; ?></td>
                 </tr>
                 <?php
               }
            }
            //======================================================================
            // Get the list of team events participant is in
            //======================================================================
            if ($TeamCount > 0)
            {
                $TeamEvents = $db->query("select distinct
                                                  t.EventID,
                                                  t.TeamID
                                          from   $TeamsTable       t,
                                                 $TeamMembersTable m
                                          where  t.TeamID        = m.TeamID
                                          and    t.ChurchID      = $ChurchID
                                          and    m.ParticipantID = $ParticipantID
                                          ")
                              or die ("Unable to get Team events:" . sqlError());

                //======================================================================
                // List the Team events participant is in
                //======================================================================
                while ($row = $TeamEvents->fetch(PDO::FETCH_ASSOC))
                {
                  $EventID = $row['EventID'];
                  $TeamID  = $row['TeamID'];

                  $evnt = $db->query("select EventName,
                                              ConvEvent
                                        from  $EventsTable
                                        where EventID = $EventID
                                      ")
                          or die ("Unable to get Team Event Name:" . sqlError());
                  $row = $evnt->fetch(PDO::FETCH_ASSOC);
                  $EventName = $row['EventName'];
                  $ConvEvent = $row['ConvEvent'] == "C" ? "Convention" : "Preconvention";
                  ?>
                  <tr>
                      <td width=30%>&nbsp;</td>
                      <td width=30%><?php  print $EventName; ?></td>
                      <td width=30%><?php  print "Team: $TeamID"; ?></td>
                      <td width=10%><?php  print $ConvEvent; ?></td>
                  </tr>
                  <?php
                }
            }
            //======================================================================
            // If they are not in any events... say so
            //======================================================================
            if ($SoloCount == 0 and $TeamCount == 0)
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