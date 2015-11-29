<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
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
die ("Already done for 2015");

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
   $db->query("delete from $MoneyTable") or die ("Unable to clear MoneyTable " . sqlError($db->errorInfo()));
   //=========================================================================================
   // Restore non-zero balances
   //=========================================================================================
   foreach ($balanceCaryForward as $ChurchID=>$Amount)
   {
         $db->query("insert into $MoneyTable
                           (Date,
                           Amount,
                           Annotation,
                           ChurchID)
                     values(now(),
                           $Amount,
                           'Balance carried forward',
                           $ChurchID)
                  ")
         or die ("Unable to insert into Money table: " . sqlError($db->errorInfo()));
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
   $db->query("delete from $ExtraOrdersTable"     ) or die ("Unable to clear ExtraOrders "          . sqlError($db->errorInfo()));
   $db->query("delete from $NonParticipantsTable" ) or die ("Unable to clear NonParticipantsTable " . sqlError($db->errorInfo()));
   $db->query("delete from $RegistrationTable"    ) or die ("Unable to clear RegistrationTabl "     . sqlError($db->errorInfo()));
   $db->query("delete from $TeamMembersTable"     ) or die ("Unable to clear TeamMembersTable "     . sqlError($db->errorInfo()));
   $db->query("delete from $LogTable"             ) or die ("Unable to clear LogTable "             . sqlError($db->errorInfo()));
   $db->query("delete from $TeamsTable"           ) or die ("Unable to clear TeamsTable "           . sqlError($db->errorInfo()));
   $db->query("delete from $JudgeAssignmentsTable") or die ("Unable to clear JudgeAssignmentsTable" . sqlError($db->errorInfo()));

   //=========================================================================================
   // Set the team number to start at 100000 again
   //=========================================================================================
   $db->query("alter table $TeamsTable auto_increment = 100000")
         or die ("Unable to set starting Team Number ". sqlError($db->errorInfo()));

   //=========================================================================================
   // Age up or out all of the particpants
   //=========================================================================================
   $ParticipantList = $db->query("select ParticipantID,
                                          Grade
                                   from   $ParticipantsTable")
                      or die ("Unable to obtain participant List". sqlError($db->errorInfo()));
   while ($row = $ParticipantList->fetch(PDO::FETCH_ASSOC))
   {
      $grade = $row['Grade'] + 1;
      $PID   = $row['ParticipantID'];
      if ($grade > 12)
      {
         $db->query("delete from $ParticipantsTable where ParticipantID=$PID")
               or die  ("Unable to delete aged out participant: $PID: ". sqlError($db->errorInfo()));
      }
      else
      {
         $db->query("update $ParticipantsTable set Grade=$grade,Comments='' where ParticipantID=$PID")
               or die  ("Unable to age up participant: $PID: ". sqlError($db->errorInfo()));
      }
   }

   //=========================================================================================
   // Record what we did in the log
   //=========================================================================================
   WriteToLog("Database reinitalized");
   ?>
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
