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

$result       = $db->query("select email
                            from   $UsersTable
                            where  Userid = '$Userid'")
                or die ("Unable to obtain email adress Name: " . sqlError());
$row          = $result->fetch(PDO::FETCH_ASSOC);
$Email        = $row['email'];

$ChurchName   = ChurchName($ChurchID);

$result       = $db->query("select count(*) as Count from $CharmersTable")
                or die ("Unable to obtain Charmer Count: " . sqlError());
$row          = $result->fetch(PDO::FETCH_ASSOC);
$charmerCount = $row['Count'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
   <head>
      <title>
         LTC Registration
      </title>
      <meta http-equiv="Content-Language" content="en-us">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel=stylesheet href="include/registration.css" type="text/css" />

   </head>
   <body>
      <h1 align="center">
         LTC Registration<br />for
      </h1>
      <h2 align="center">
         <?php
         print "$ChurchName";
         ?>
      </h2>

      <?php
         if ($Admin =='Y')
         {
            ?>
            <table class='registrationTable'>
               <tr>
                  <td colspan="2" align="center"><font size="+2"><b>Administration Functions</b></font></td>
               </tr>
               <tr>
                  <td width="50%" valign="top">
                     <h2>Administrative Tasks:</h2>
                     <table class='registrationTable'>
                        <tr><td><a href="ChangeChurch.php">Administer a different Church</a></td></tr>
                        <tr><td><a href="Users.php">Manage Web Site Users</a></td></tr>
                        <tr><td><a href="Events.php">Manage Events</a></td></tr>
                        <tr><td><a href="Coordinators.php">Manage Event Directors</a></td></tr>
                        <tr><td><a href="Churches.php">Manage Churches</a></td></tr>
                        <tr><td><a href="Rooms.php">Manage Event Rooms</a></td></tr>
                        <tr><td><a href="AdminSchedule.php">Manage Event Schedules</a></td></tr>
                        <tr><td><a href="LockRegistration.php">Open or Close Registration</a></td></tr>
                        <tr><td><a href="AdminMoney.php">Post funds to an account</a></td></tr>
                        <tr><td><a href="NewDatabase.php">Delete old registration data from database</a></td></tr>
                     </table>
                  <?php
                  if(!$MOBILE)
                  {
                  ?>
                  </td>
                  <td width="50%" valign="top">
                  <?php
                  }
                  ?>
                     <h2>Administrative Reports:</h2>
                     <table class='registrationTable'>
                        <tr><td><a target="_blank" href="RptWhoByEvent.php?Admin=1">Who is in what</a> (By Event, All Churches)</td></tr>
                        <tr><td><a target="_blank" href="RptTeams.php?Admin=1">Team Rosters</a> (All Churches)</td></tr>
                        <tr><td><a target="_blank" href="RptChurchCoord.php">Church Coordinators</a></td></tr>
                        <tr><td><a target="_blank" href="RptEnrollment.php">Enrollment</a></td></tr>
                        <tr><td><a target="_blank" href="RptTshirts.php">T-Shirts</a></td></tr>
                        <tr><td><a target="_blank" href="RptExpenses.php?Admin=1">Expense Report</a></td></tr>
                        <tr><td><a target="_blank" href="RptExpensesSumm.php">Expense Report Summary</a></td></tr>
                        <tr><td><a target="_blank" href="RptBalances.php?nozero">Non-Zero balance Report</a></td></tr>
                        <tr><td><a target="_blank" href="RptWhoComments.php">Participants with Comments</a></td></tr>
                        <tr><td><a target="_blank" href="RptSeniors.php">All Seniors with events</a></td></tr>
                        <tr><td><a target="_blank" href="RptWhoBySched.php?Admin=1">Roster by scheduled event (All Churches, By Event)</a></td></tr>
                        <tr><td><a target="_blank" href="RptWhoBySched.php?Admin=1&amp;byTime">Roster by scheduled event (All Churches, By Time)</a></td></tr>
                        <tr><td><a target="_blank" href="RptWhoInWhatAll.php">Participant list with events (All Churches)</a></td></tr>
                        <tr><td><a target="_blank" href="RptParticipants.php?Admin=1">Participant Lists</a></td></tr>
                        <tr><td><a target="_blank" href="RptEventParticipation.php">Event Participation</a></td></tr>
                        <tr><td><a target="_blank" href="RptCountEventBySched.php">Event Participation by time slot</a></td></tr>
                        <tr><td><a target="_blank" href="RptCoordEvents.php">Event Directors Rosters</a></td></tr>
                        <tr><td><a target="_blank" href="RptAwardCounts.php">Post Convention Award Counts</a></td></tr>
                        <tr><td><a target="_blank" href="RptJudgesAssignment.php">Judges Assigned</a></td></tr>
                        <tr><td><a target="_blank" href="RptJudges.php?Admin=Y">Judges by Congregation</a></td></tr>
                        <tr><td><a target="_blank" href="RptJudgesAssignment.php?ID=ByEvent">Judges by Event</a></td></tr>
                        <tr><td><a target="_blank" href="RptCharmers.php">List of C.H.A.R.M.E.R.S</a></td></tr>
                     </table>
                  </td>
               </tr>
            </table>
            <br />

            <table class='registrationTable'>
               <tr>
                  <td colspan="2" align="center"><font size="+2"><b>Tally Room Functions</b></font></td>
               </tr>
               <tr>
                  <td width="50%" valign="top">
                     <h2>Tally Room Tasks:</h2>
                     <table class='registrationTable'>
                        <tr><td><a href="TallyEventList.php">Select an Event to Tally</a></td></tr>
                        <tr><td><a href="DataMenu.php">Data Download Facilities</a></td></tr>
                     </table>
                  <?php
                  if(!$MOBILE)
                  {
                  ?>
                  </td>
                  <td width="50%" valign="top">
                  <?php
                  }
                  ?>
                     <h2>Tally Room Reports:</h2>
                     <table class='registrationTable'>
                        <tr><td><a target="_blank" href="TallyAllAwards.php">All Participant Awards</a></td></tr>
                        <tr><td><a target="_blank" href="RptAwards.php">Award Counts for each Church</a></td></tr>
                     </table>
                  </td>
               </tr>
            </table>
            <br />
            <?php
         }

         ?>
         <table class='registrationTable'>
            <tr>
                <td colspan="2" align="center"><font size="+2"><b>Church Coordination Functions</b></font></td>
            </tr>
            <tr>
               <td width="50%" valign="top">
                  <?php
                  if ($UserStatus != 'R')
                  {
                  ?>
                     <h2>Participant Management:</h2>
                     <table class='registrationTable'>
                        <tr><td><a href="Participants.php">Manage Participants</a></td></tr>
                        <tr><td><a href="SignupSoloEvents.php">Signup for Individual Events</a></td></tr>
                        <tr><td><a href="SignupTeamEvents.php">Signup Teams</a></td></tr>
                     </table>
                  <?php
                  }
                  ?>
                  <h2>Judging information</h2>
                  <table class='registrationTable'>
                     <tr><td><a href="Judges.php">Enter Judges Info</a></td></tr>
                     <tr><td><a href="AssignJudges.php">Assign Judges to Events</a></td></tr>
                  </table>
                  <h2>Account Management</h2>
                  <table class='registrationTable'>
                     <tr><td><a href="ChangePassword.php">Change your Password</a></td></tr>
                     <tr><td><a href="ChangeEmail.php">Change your Email Address</a></td></tr>
                  </table>
                  <?php
                  if (!preg_match("/@/",$Email))
                  {
                  ?>
                     <b><font color="red">&nbsp;&nbsp;&nbsp;&nbsp;Your email address is not set.<br />
                                          &nbsp;&nbsp;&nbsp;&nbsp;Please take a moment and set it using the link above</font></b><br /><br />
                  <?php
                  }
                  else
                  {
                     print "<b>&nbsp;&nbsp;&nbsp;&nbsp;Your email address is : $Email</b><br><br>";
                  }
                  ?>
               <?php
               if(!$MOBILE)
               {
               ?>
               </td>
               <td width="50%" valign="top">
               <?php
               }
               ?>
                  <h2>Reports:</h2>
                  <table class='registrationTable'>
                     <tr><td><a target="_blank" href="RptWhoInWhat.php">Who is in what</a> (By Participant)</td></tr>
                     <tr><td><a target="_blank" href="RptWhoByEvent.php">Who is in what</a> (By Event)</td></tr>
                     <tr><td><a target="_blank" href="RptTeams.php">Team Rosters</a></td></tr>
                     <tr><td><a target="_blank" href="RptEventCoord.php">Event Directors</a></td></tr>
                     <tr><td><a target="_blank" href="RptExpenses.php">Expense Report</a></td></tr>
                     <tr><td><a target="_blank" href="RptWhoBySched.php">Roster by scheduled event</a></td></tr>
                     <tr><td><a target="_blank" href="RptParticipants.php">Participant List</a></td></tr>
                     <tr><td><a target="_blank" href="RptJudges.php">Judges Schedule</a></td></tr>
                     <tr><td><a target="_blank" href="printing_reports.htm">Notes on printing reports</a></td></tr>
                  </table>
               </td>
            </tr>
            <?php
            if ($UserStatus != 'R')
            {
            ?>
            <tr>
               <td width="50%" valign="top">
                  <h2>Miscellaneous:</h2>
                  <?php
                  if ($charmerCount >=20)
                  {
                  ?>
                  <font color='green'><b>Wow! Thank you all! We have enough Charmers for this year!</b></font>
                  <?php
                  }
                  else
                  {
                  ?>
                  <font color='red'><b>Please consider adding some charmers.<br>We need 20 for LTC to run smoothly and currently have <?php print $charmerCount?> signed up</b></font>
                  <?php
                  }
                  ?>
                  <table class='registrationTable'>
                     <tr><td><a href="ExtraOrders.php">Order Extra T-Shirts and Meal Tickets</a></td></tr>
                     <tr><td><a href="Charmers.php">Manage C.H.A.R.M.ers</a></td></tr>
                     <tr><td><a href="/">LTCSW Home</a></td></tr>
                     <tr><td><a href="mailto:paul@lemmons.name">Feedback: Comments or Report a problem</a></td></tr>
                  </table>
               </td>
               <?php
               if (!$MOBILE)
               {
               ?>
                  <td width="50%" valign="top"><h2>&nbsp;</h2></td>
               <?php
               }
               ?>
            </tr>
            <?php
            }
            ?>
      </table>
   </body>
</html>
