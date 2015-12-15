<?php
include 'include/RegFunctions.php';
if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
//SELECT P.ParticipantID, P.FirstName, P.LastName, P.Address, P.City, P.State,
//P.Zip, P.Grade, P.Gender, P.Comments, P.ChurchID FROM Participants P;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <meta http-equiv="Content-Language" content="en-us">
       <title>
          Participant Data
       </title>
    </head>

    <body>
    <?php
         print "\"ParticipantID\",";
         print "\"FirstName\",";
         print "\"LastName\",";
         print "\"Address\",";
         print "\"City\",";
         print "\"State\",";
         print "\"Zip\",";
         print "\"Grade\",";
         print "\"Gender\",";
         print "\"Comments\",";
         print "\"ChurchID\",";
         print "\"MealTicket\"";
         print "<br>\n";

         $church_list = ChurchesRegistered();
         foreach ($church_list as $ChurchID=>$ChurchName)
         {
            $ParticipantIDs = ActiveParticipants($ChurchID);
            foreach ($ParticipantIDs as $ParticipantID=>$ParticipantName)
            {
               $results = $db->query("select   ParticipantID,
                                                FirstName,
                                                LastName,
                                                Address,
                                                City,
                                                State,
                                                Zip,
                                                Grade,
                                                Gender,
                                                Comments,
                                                ChurchID,
                                                Case MealTicket
                                                   When '3' Then '3 Meals'
                                                   When '5' Then '5 Meals'
                                                   When 'N' Then 'No Meals'
                                                   else          '<error>'
                                                end
                                                MealTicket
                                       from     $ParticipantsTable
                                       where    ParticipantID = $ParticipantID
                                      ")
                        or die ("Unable to get Participant Info:" . sqlError());

               $row = $results->fetch(PDO::FETCH_ASSOC);

               $ParticipantID = $row['ParticipantID'];
               $FirstName     = $row['FirstName'];
               $LastName      = $row['LastName'];
               $Address       = $row['Address'];
               $City          = $row['City'];
               $State         = $row['State'];
               $Zip           = $row['Zip'];
               $Grade         = $row['Grade'];
               $Gender        = $row['Gender'];
               $Comments      = $row['Comments'];
               $ChurchID      = $row['ChurchID'];
               $MealTicket    = $row['MealTicket'];


               print "\"$ParticipantID\",";
               print "\"$FirstName\",";
               print "\"$LastName\",";
               print "\"$Address\",";
               print "\"$City\",";
               print "\"$State\",";
               print "\"$Zip\",";
               print "\"$Grade\",";
               print "\"$Gender\",";
               print "\"$Comments\",";
               print "\"$ChurchID\",";
               print "\"$MealTicket\"";
               print "<br>\n";
            }
         }
         ?>
         </table>
    </body>
</html>