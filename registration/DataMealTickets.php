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
// "Number","Description","FirstName","LastName","Tickets"
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <meta http-equiv="Content-Language" content="en-us">
       <title>
          Meal Ticket Data
       </title>
    </head>

    <body>
    <?php
         print "\"ParticipantID\",";
         print "\"Description\",";
         print "\"FirstName\",";
         print "\"LastName\",";
         print "\"MealTicket\"";
         print "<br>\n";


         $church_list = ChurchesRegistered();
         foreach ($church_list as $ChurchID=>$ChurchName)
         {
            $ParticipantIDs = ActiveParticipants($ChurchID);
            foreach ($ParticipantIDs as $ParticipantID=>$ParticipantName)
            {
               $results = $db->query("select   p.ParticipantID,
                                                p.FirstName,
                                                p.LastName,
                                                Case p.MealTicket
                                                   When '3' Then '3 Meals'
                                                   When '5' Then '5 Meals'
                                                   When 'N' Then 'No Meals'
                                                   else          '<error>'
                                                end
                                                MealTicket
                                       from     $ParticipantsTable p
                                       where    p.ParticipantID=$ParticipantID
                                      ")
                        or die ("Unable to get Participant info:" . sqlError());


               $row = $results->fetch(PDO::FETCH_ASSOC);

               $ParticipantID = $row['ParticipantID'];
               $FirstName     = $row['FirstName'];
               $LastName      = $row['LastName'];
               $MealTicket    = $row['MealTicket'];

               print "\"$ParticipantID\",";
               print "\"$ChurchName\",";
               print "\"$FirstName\",";
               print "\"$LastName\",";
               print "\"$MealTicket\"";
               print "<br>\n";
            }
         }

         $results = $db->query("select   distinct
                                          e.CoordName
                                 from     $EventsTable e
                                 order by CoordName")
                   or die ("Unable to get Coordinator list:" . sqlError());


         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            $CoordName = $row['CoordName'];

            if (str_word_count($CoordName) > 1)
               list($CoordFirstName,$CoordLastName) = split(" ",$CoordName,2);
            else
            {
               $CoordFirstName = $CoordName;
               $CoordLastName  = "";
            }

            print "\"\",";
            print "\"Event Director\",";
            print "\"$CoordFirstName\",";
            print "\"$CoordLastName\",";
            print "\"\"";
            print "<br>\n";
         }
         ?>
         </table>
    </body>
</html>