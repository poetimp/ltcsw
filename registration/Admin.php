<?php
include 'include/RegFunctions.php';

$result     = mysql_query("select ChurchName
                           from   $ChurchesTable
                           where  ChurchID = $ChurchID")
              or die ("Unable to obtain Church Name: " . mysql_error());
$row        = mysql_fetch_assoc($result);
$ChurchName = $row['ChurchName'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
   <head>
      <title>
         LTC Registration
      </title>
   </head>
   <body style="background-color: rgb(217, 217, 255);">
      <h1 align=center>
         LTC Registration<br>for
      </h1>
      <h2 align=center>
         <?php
         print "$ChurchName";
         ?>
      </h2>

      <?php
         if ($Admin =='Y')
         {
            ?>
            <table border="1" width="100%">
               <tr>
                  <TD colspan="2" align="center" bgcolor="#C0C0C0"><font size="+2"><b>Administration Functions</b></font></TD>
               </tr>
               <tr>
                  <td width=50% valign=top>
                     <h2>Administrative Tasks:</h2>
                     <ul>
                        <li><a href="ChangeChurch.php">Administer a different Church</a></li>
                        <li><a href="Users.php">Manage Web Site Users</a></li>
                        <li><a href="Events.php">Manage Events</a></li>
                        <li><a href="Coordinators.php">Manage Event Directors</a></li>
                        <li><a href="Churches.php">Manage Churches</a></li>
                        <li><a href="Rooms.php">Manage Event Rooms</a></li>
                        <li><a href="AdminSchedule.php">Manage Event Schedules</a></li>
                        <li><a href="LockRegistration.php">Open or Close Registration</a></li>
                        <li><a href="AdminMoney.php">Post funds to an account</a></li>
                        <li><a href="Conventions.php">Add, delete or update conventions</a></li>
                        <li><a href="NewDatabase.php">Delete old registration data from database</a></li>
                     </ul>
                  </td>
                  <td width=50% valign=top>
                     <h2>Administrative Reports:</h2>
                     <ul>
                        <li><a target="_blank" href="RptWhoByEvent.php?Admin=1">Who is in what</a> (By Event, All Churches)</li>
                        <li><a target="_blank" href="RptTeams.php?Admin=1">Team Rosters</a> (All Churches)</li>
                        <li><a target="_blank" href="RptChurchCoord.php">Church Coordinators</a></li>
                        <li><a target="_blank" href="RptEnrollment.php">Enrollment</a></li>
                        <li><a target="_blank" href="RptTshirts.php">T-Shirts</a></li>
                        <li><a target="_blank" href="RptExpenses.php?Admin=1">Expense Report</a></li>
                        <li><a target="_blank" href="RptExpensesSumm.php">Expense Report Summary</a></li>
                        <li><a target="_blank" href="RptBalances.php?nozero">Non-Zero balance Report</a></li>
                        <li><a target="_blank" href="RptWhoComments.php">Participants with Comments</a></li>
                        <li><a target="_blank" href="RptSeniors.php">All Seniors with events</a></li>
                        <li><a target="_blank" href="RptWhoBySched.php?Admin=1">Roster by scheduled event (All Churches, By Event)</a></li>
                        <li><a target="_blank" href="RptWhoBySched.php?Admin=1&byTime">Roster by scheduled event (All Churches, By Time)</a></li>
                        <li><a target="_blank" href="RptWhoInWhatAll.php">Participant list with events (All Churches)</a></li>
                        <li><a target="_blank" href="RptParticipants.php?Admin=1">Participant Lists</a></li>
                        <li><a target="_blank" href="RptCountEventBySched.php">Event Participation</a></li>
                        <li><a target="_blank" href="RptCoordEvents.php">Event Directors Rosters</a></li>
                        <li><a target="_blank" href="RptAwardCounts.php">Post Convention Award Counts</a></li>
                        <li><a target="_blank" href="RptJudgesAssignment.php">Judges Assigned</a></li>
                        <li><a target="_blank" href="RptJudges.php?Admin=Y">Judges by Congregation</a></li>
                     </ul>
                  </td>
               </tr>
            </table>
            <br>

            <table border="1" width="100%">
               <tr>
                  <TD colspan="2" align="center" bgcolor="#C0C0C0"><font size="+2"><b>Tally Room Functions</b></font></TD>
               </tr>
               <tr>
                  <td width=50% valign=top>
                     <h2>Tally Room Tasks:</h2>
                     <ul>
                        <li><a href="TallyEventList.php">Select an Event to Tally</a></li>
                        <li><a href="DataMenu.php">Data Download Facilities</a></li>
                     </ul>
                  </td>
                  <td width=50% valign=top>
                     <h2>Tally Room Reports:</h2>
                     <ul>
                        <li><a target="_blank" href="TallyAllAwards.php">All Participant Awards</a></li>
                        <li><a target="_blank" href="RptAwards.php">Award Counts for each Church</a></li>
                     </ul>
                  </td>
               </tr>
            </table>
            <br>
            <?php
         }

         ?>
         <table border="1" width="100%">
            <tr>
                <TD colspan="2" align="center" bgcolor="#C0C0C0"><font size="+2"><b>Church Coordination Functions</b></font></TD>
            </tr>
            <tr>
              <td width="50%" valign=top>
                  <table width="100%">
                     <tr>
                        <td width="100%">
                           <?php
                           if ($UserStatus != 'R')
                           {
                           ?>
                              <h2>Participant Management:</h2>
                              <ul>
                                 <li><a href="Participants.php">Manage Participants</a></li>
                                 <li><a href="SignupSoloEvents.php">Signup for Individual Events</a></li>
                                 <li><a href="SignupTeamEvents.php">Signup Teams</a></li>
                              </ul>
                           <?php
                           }
                           ?>
                           <h2>Judging information</h2>
                           <ul>
                              <li><a href="Judges.php">Enter Judges Info</a></li>
                              <li>
                                 <a href="AssignJudges.php">
                                    Assign Judges to Events
                                 </a>
                              </li>
                           </ul>
                           <?php
                           $result     = mysql_query("SELECT  distinct
                                                              count(*) Count
                                                      FROM    $JudgeAssignmentsTable a,
                                                              $EventScheduleTable    s,
                                                              $EventsTable           e
                                                      WHERE   s.SchedID  = a.SchedID
                                                      and     s.EventID  = e.EventID
                                                      and     instr(s.RoomID,a.RoomID) > 0
                                                      and     a.ChurchID = $ChurchID
                                                      and     e.TeamEvent = 'N'
                                                      ")
                                         or die  ("Unable to obtain individule Judge Assignments: " . mysql_error());
                           $row        = mysql_fetch_assoc($result);
                           $SoloJudgesAssigned = $row['Count'];

                           $result     = mysql_query("SELECT  distinct
                                                              count(*) Count
                                                      FROM    $JudgeAssignmentsTable a,
                                                              $EventScheduleTable    s,
                                                              $EventsTable           e
                                                      WHERE   s.SchedID  = a.SchedID
                                                      and     s.EventID  = e.EventID
                                                      and     instr(s.RoomID,a.RoomID) > 0
                                                      and     a.ChurchID = $ChurchID
                                                      and     e.TeamEvent = 'Y'
                                                      ")
                                         or die  ("Unable to obtain Team Judge Assignments: " . mysql_error());
                           $row        = mysql_fetch_assoc($result);
                           $TeamJudgesAssigned = $row['Count'];


                           $result     = mysql_query("select distinct
                                                             s.SchedID,
                                                             e.EventID
                                                      from   $TeamsTable         t,
                                                             $EventsTable        e,
                                                             $EventScheduleTable s
                                                      where  ChurchID = $ChurchID
                                                      and    t.EventID = e.EventID
                                                      and    e.ConvEvent = 'C'
                                                      and    s.EventID = e.EventID
                                                      ")
                                         or die  ("Unable to obtain Judged events Count: " . mysql_error());
                           $TeamJudgesNeeded = mysql_num_rows($result);

                           $result     = mysql_query("select distinct
                                                             s.EventID,
                                                             s.SchedID
                                                      from   $RegistrationTable  r,
                                                             $EventScheduleTable s,
                                                             $EventsTable        e
                                                      where  r.ChurchID = $ChurchID
                                                      and    r.EventID  = e.EventID
                                                      and    s.EventID  = e.EventID
                                                      ")
                                         or die  ("Unable to obtain Individual events Count: " . mysql_error());
                           $SoloJudgesNeeded = intval((mysql_num_rows($result)+3)/4);


                           print "<table width=100%>\n";
                           print "   <tr>\n";
                           print "      <td width=5%>&nbsp;</td>\n";
                           print "      <td width=5% align=right>
                                           <b>$SoloJudgesAssigned</b>
                                        </td>\n";
                           if ($SoloJudgesAssigned < $SoloJudgesNeeded)
                              print "      <td width=90%>
                                              <font color=red>
                                                 Solo events judged out of
                                              </font>
                                              <b>$SoloJudgesNeeded</b>
                                              <font color=red>
                                                 needed
                                              </font>
                                           </td>\n";
                           else
                              print "      <td width=90%>Solo events judged out of $SoloJudgesNeeded needed</td>\n";
                           print "   </tr>\n";
                           print "   <tr>\n";
                           print "      <td>&nbsp;</td>\n";
                           print "      <td align=right>
                                           <b>$TeamJudgesAssigned</b>
                                        </td>\n";
                           if ($TeamJudgesAssigned < $TeamJudgesNeeded)
                              print "      <td width=90%>
                                              <font color=red>
                                                 Team events judged out of
                                              </font>
                                              <b>$TeamJudgesNeeded</b>
                                              <font color=red>
                                                 needed
                                              </font>
                                           </td>\n";
                           else
                              print "      <td>Team events judged out of $TeamJudgesNeeded needed</td>\n";
                           print "   </tr>\n";
                           print "</table>\n";
                           ?>
                        </td>
                     </tr>
                  </table>
               </td>

               <td width=50% valign=top>
                  <h2>Reports:</h2>
                  <ul>
                     <li><a target="_blank" href="RptWhoInWhat.php">Who is in what</a> (By Participant)</li>
                     <li><a target="_blank" href="RptWhoByEvent.php">Who is in what</a> (By Event)</li>
                     <li><a target="_blank" href="RptTeams.php">Team Rosters</a></li>
                     <li><a target="_blank" href="RptEventCoord.php">Event Directors</a></li>
                     <li><a target="_blank" href="RptExpenses.php">Expense Report</a></li>
                     <li><a target="_blank" href="RptWhoBySched.php">Roster by scheduled event</a></li>
                     <li><a target="_blank" href="RptParticipants.php">Participant List</a></li>
                     <li><a target="_blank" href="RptJudges.php">Judges Schedule</a></li>
                  </ul>
                  <blockquote>
                     <p><a href="printing_reports.htm">Notes on printing reports</a></p>
                  </blockquote>
               </td>
            </tr>
            <?php
            if ($UserStatus != 'R')
            {
            ?>
            <tr>
               <td width=50% valign=top>
                  <h2>Miscellaneous:</h2>
                  <ul>
                     <li><a href="ExtraOrders.php">Order Extra T-Shirts and Meal Tickets</a></li>
                     <li><a href="NonParticipants.php">Add C.H.A.R.M.ers</a></li>
                     <li><a href="/">LTCSW Home</a></li>
                     <li><a href="mailto:paul@lemmons.name">Feedback: Comments or Report a problem</a></li>
                  </ul>
               </td>
               <td width=50% valign=top><h2>&nbsp;</h2></td>
            </tr>
            <?php
            }
            ?>
      </table>
   </body>
</html>
