<?php
//    header("refresh: 5; URL=Admin.php");
//    print ("Can not clear database. It has already been done.");
//    die();

include 'include/RegFunctions.php';

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}
die ("Already done for 2014");

if (isset($_POST['Confirm']))
{
   //=========================================================================================
   // Go see of any churches have a balance from a previous year. If so hold on to it.
   //=========================================================================================
   $ChuchList = ChurchesDefined();
   if (count($ChuchList) > 0)
   {
      $balanceCaryForward = array();
      foreach ($ChuchList as $ChurchID=>$ChurchName)
      {
         $costDetail = ChurchExpenses($ChurchID);
         print "<hr><pre>Churchid = $ChurchID<br>ChurchName = $ChurchName<br>";print_r ($costDetail);print "</pre>";
         if ($costDetail["Balance"] != 0)
            $balanceCaryForward[$ChurchID] = $costDetail["Balance"];
      }
   }

   //=========================================================================================
   // Clear all accounting from last year
   //=========================================================================================
   mysql_query("delete from $MoneyTable") or die ("Unable to clear MoneyTable " . mysql_error());
   //=========================================================================================
   // Restore non-zero balances
   //=========================================================================================
   foreach ($balanceCaryForward as $ChurchID=>$Amount)
   {
         mysql_query("insert into $MoneyTable
                           (Date,
                           Amount,
                           Annotation,
                           ChurchID)
                     values(now(),
                           $Amount,
                           'Balance carried forward',
                           $ChurchID)
                  ")
         or die ("Unable to insert into Money table: " . mysql_error());
         /*print "insert into $MoneyTable
                           (Date,
                           Amount,
                           Annotation,
                           ChurchID)
                     values(now(),
                           $Amount,
                           'Balance carried forward',
                           $ChurchID)
                  <br>"; */
   }
   //die ("Exiting");
   //=========================================================================================
   // First clear out various registration tables that contain only information for a given year
   //=========================================================================================
   mysql_query("delete from $ExtraOrdersTable"     ) or die ("Unable to clear ExtraOrders "          . mysql_error());
   mysql_query("delete from $NonParticipantsTable" ) or die ("Unable to clear NonParticipantsTable " . mysql_error());
   mysql_query("delete from $RegistrationTable"    ) or die ("Unable to clear RegistrationTabl "     . mysql_error());
   mysql_query("delete from $TeamMembersTable"     ) or die ("Unable to clear TeamMembersTable "     . mysql_error());
   mysql_query("delete from $LogTable"             ) or die ("Unable to clear LogTable "             . mysql_error());
   mysql_query("delete from $TeamsTable"           ) or die ("Unable to clear TeamsTable "           . mysql_error());
   mysql_query("delete from $JudgeAssignmentsTable") or die ("Unable to clear JudgeAssignmentsTable" . mysql_error());

   //=========================================================================================
   // Set the team number to start at 100000 again
   //=========================================================================================
   mysql_query("alter table $TeamsTable auto_increment = 100000")
         or die ("Unable to set starting Team Number ". mysql_error());

   //=========================================================================================
   // Age up or out all of the particpants
   //=========================================================================================
   $ParticipantList = mysql_query("select ParticipantID,
                                          Grade
                                   from   $ParticipantsTable")
                      or die ("Unable to obtain participant List". mysql_error());
   while ($row = mysql_fetch_assoc($ParticipantList))
   {
      $grade = $row['Grade'] + 1;
      $PID   = $row['ParticipantID'];
      if ($grade > 12)
      {
         mysql_query("delete from $ParticipantsTable where ParticipantID=$PID")
               or die  ("Unable to delete aged out participant: $PID: ". mysql_error());
      }
      else
      {
         mysql_query("update $ParticipantsTable set Grade=$grade,Comments='' where ParticipantID=$PID")
               or die  ("Unable to age up participant: $PID: ". mysql_error());
      }
   }

   //=========================================================================================
   // Record what we did in the log
   //=========================================================================================
   WriteToLog("Database reinitalized");
   ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
      <head>
         <title>
            Database prepared
         </title>
      </head>
      <body style="background-color: rgb(217, 217, 255);">
         <h1 align=center>
            Database has been cleared of previous year's data
         </h1>
         <?php footer("","")?>
      </body>
   </html>
<?php
}
else if (isset($_POST['Cancel']))
{
   header("refresh: 0; url=Admin.php");
}
else
{
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

       <head>
          <title>
             Prepare database for new year
          </title>
       </head>

       <body style="background-color: rgb(217, 217, 255);">
          <form method="post" action="NewDatabase.php">
             <center>
                <h1>
                   Prepare database for new year
                </h1>
             </center>
             <p align="center">
             <table width=60%>
                <tr>
                   <td>
                   <b><p align=center><font color=red><h2>Warning!!!</h2></font></p></b>
                   <p align=left>
                         If you confirm this action you will be removing all registration and accounting
                         data from the database and will age-up all participants. You should only do this
                         one time just prior to opening registration for a new year. If you are absolutely
                         sure you want to do this at this time press confirm. If you have the slightest doubt
                         press cancel.
                      </p>
                   </td>
                </tr>
             </table>
             <br>
             <input type="submit" value="Confirm" name="Confirm">
             <font size="5"><br>
             or</font><br>
             <input type="submit" value="Cancel" name="Cancel">
             </p>
          </form>
       </body>

   </html>
<?php
}
?>
