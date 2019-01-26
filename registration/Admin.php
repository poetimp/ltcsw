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
      <meta http-equiv="Content-Language" content="en-us" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="stylesheet" href="include/registration.css" type="text/css" />

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
                  <td colspan="2" style="text-align: center"><h2>Administration Functions</h2></td>
               </tr>
               <tr>
                  <td style='width: 50%;vertical-align: top'>
                     <h2 align='center'>Administrative Tasks:</h2>
                     <table class='registrationTable'>
                        <tr><td style='padding-left: 5%'><a href="ChangeChurch.php">     Administer a different Church             </a></td></tr>
                        <tr><td style='padding-left: 5%'><a href="Users.php">            User Maintenance                          </a></td></tr>
                        <tr><td style='padding-left: 5%'><a href="Events.php">           Event Maintenance                         </a></td></tr>
                        <tr><td style='padding-left: 5%'><a href="Coordinators.php">     Director Maintenance                      </a></td></tr>
                        <tr><td style='padding-left: 5%'><a href="Churches.php">         Church Maintenance                        </a></td></tr>
                        <tr><td style='padding-left: 5%'><a href="Rooms.php">            Room Maintenance                          </a></td></tr>
                        <tr><td style='padding-left: 5%'><a href="AdminSchedule.php">    Schedule Maintenance                      </a></td></tr>
                        <tr><td style='padding-left: 5%'><a href="LockRegistration.php"> Set Registration Status                   </a></td></tr>
                        <tr><td style='padding-left: 5%'><a href="AdminMoney.php">       Accounting                                </a></td></tr>
                        <tr><td style='padding-left: 5%'><a href="NewDatabase.php">      Prepare database for new LTC year         </a></td></tr>
                     </table>
                  <?php
                  if(!$MOBILE)
                  {
                  ?>
                  </td>
                  <td style='width: 50%;vertical-align: top'>
                  <?php
                  }
                  ?>
                     <h2 align='center'>Administrative Reports:</h2>
                     <table class='registrationTable'>
                        <tr><td style='padding-left: 5%'>Accounting Reports</td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptExpensesSumm.php">                  Expense Report: Summary                             </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptBalances.php?nozero">               Expense Report: Churches with Non-Zero balances     </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptExpenses.php?Admin=1">              Expense Report: Details for each church             </a></td></tr>

                        <tr><td style='padding-left: 5%'>Rosters</td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptWhoInWhatAll.php">                  Participant List by Participant with event details  </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptParticipants.php?Admin=1">          Participant List by Church with Shirt, Meal &amp; Grade </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptWhoByEvent.php?Admin=1">            All Events Rosters                                  </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptTeams.php?Admin=1">                 Team Rosters by Church                              </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptWhoBySched.php?Admin=1">            Scheduled events roster by event                    </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptWhoBySched.php?Admin=1&amp;byTime"> Scheduled events roster by time of event            </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptWhoComments.php">                   Participants with Comments                          </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptSeniors.php">                       All Seniors with events this year                   </a></td></tr>

                        <tr><td style='padding-left: 5%'>Administrative</td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptChurchCoord.php">                   Church Coordinators                                 </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptEnrollment.php">                    Church List with Enrollment                         </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptTshirts.php">                       T-Shirts: Sizes and quantities                      </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptAwardCounts.php">                   Award Counts                                        </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptEventDirectors.php">                Event Directors                                     </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptEventParticipation.php">            Event Participation                                 </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptCountEventBySched.php">             Event Participation by time slot                    </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptCoordEvents.php">                   Event Directors Rosters                             </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptCoordEventsShort.php">              Event Directors Rosters (Less Detail)               </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptCharmers.php">                      List of C.H.A.R.M.E.R.S                             </a></td></tr>
                        <tr><td style='padding-left: 5%'>Judges</td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptJudgesAssignment.php">              Judges Assigned                                     </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptJudges.php?Admin=Y">                Judges by Congregation                              </a></td></tr>
                        <tr><td style='padding-left: 10%'><a target="_blank" href="RptJudgesAssignment.php?ID=ByEvent">   Judges by Event                                     </a></td></tr>

                     </table>
                  </td>
               </tr>
            </table>
            <br />

            <table class='registrationTable'>
               <tr>
                  <td colspan="2" style='text-align: center'><font size="+2"><b>Tally Room Functions</b></font></td>
               </tr>
               <tr>
                  <td style='width: 50%;vertical-align: top'>
                     <h2 align='center'>Tally Room Tasks:</h2>
                     <table class='registrationTable'>
                        <tr><td><a href="TallyEventList.php">Select an Event to Tally</a></td></tr>
                        <tr><td><a href="DataMenu.php">Data Download Facilities</a></td></tr>
                     </table>
                  <?php
                  if(!$MOBILE)
                  {
                  ?>
                  </td>
                  <td style='width: 50%;vertical-align: top'>
                  <?php
                  }
                  ?>
                     <h2 align='center'>Tally Room Reports:</h2>
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
                <td colspan="2" style='text-align: center'><font size="+2"><b>Church Coordination Functions</b></font></td>
            </tr>
            <tr>
               <td style='width: 50%;vertical-align: top'>
                  <?php
                  if ($UserStatus != 'R')
                  {
                  ?>
                     <h2 align='center'>Participant Management:</h2>
                     <table class='registrationTable'>
                        <tr><td><a href="Participants.php">Manage Participants</a></td></tr>
                        <tr><td><a href="SignupSoloEvents.php">Signup for Individual Events</a></td></tr>
                        <tr><td><a href="SignupTeamEvents.php">Signup Teams</a></td></tr>
                     </table>
                  <?php
                  }
                  ?>
                  <h2 align='center'>Judging information</h2>
                  <table class='registrationTable'>
                     <tr><td><a href="Judges.php">Enter Judges Info</a></td></tr>
                     <tr><td><a href="AssignJudges.php">Assign Judges to Events</a></td></tr>
                  </table>
                  <h2 align='center'>Account Management</h2>
                  <table class='registrationTable'>
                     <tr><td><a href="ChangePassword.php">Change your Password</a></td></tr>
                     <tr><td>
                            <a href="ChangeEmail.php">Change your Email Address</a>
                            <?php
                            if (!preg_match("/@/",$Email))
                            {
                            ?>
                               <b><font color="red"><br />&nbsp;&nbsp;&nbsp;&nbsp;Your email address is not set.
                                                    <br />&nbsp;&nbsp;&nbsp;&nbsp;Please take a moment and set it using the link above</font></b>
                            <?php
                            }
                            else
                            {
                               print "<b>&nbsp;&nbsp;&nbsp;&nbsp;Your email address is : $Email</b><br /><br />";
                            }
                            ?>
                         </td>
                     </tr>
                  </table>
               <?php
               if(!$MOBILE)
               {
               ?>
               </td>
               <td style='width: 50%;vertical-align: top'>
               <?php
               }
               ?>
                  <h2 align='center'>Reports:</h2>
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
               <td style='width: 50%;vertical-align: top'>
                  <h2 align='center'>Miscellaneous:</h2>
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
                  <font color='red'><b>Please consider adding some charmers.<br />We need 20 for LTC to run smoothly and currently have <?php print $charmerCount?> signed up</b></font>
                  <?php
                  }
                  ?>
                  <table class='registrationTable'>
                     <tr><td><a href="ExtraOrders.php">Order Extra T-Shirts</a></td></tr>
                     <tr><td><a href="Charmers.php">Manage C.H.A.R.M.ers</a></td></tr>
                     <tr><td><a href="/">LTCSW Home</a></td></tr>
                     <tr><td><a href="mailto:paul@lemmons.name">Feedback: Comments or Report a problem</a></td></tr>
                  </table>
               </td>
               <?php
               if (!$MOBILE)
               {
               ?>
                  <td style='width: 50%;vertical-align: top'><h2>&nbsp;</h2></td>
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
