<?php
include 'include/RegFunctions.php';
if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
// JudgeID,FirstName, LastName, Address, City, State, Zip, Phone, ChurchID, Gender, MaxEvents, Comments, Email
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <meta http-equiv="Content-Language" content="en-us">
       <title>
          Judges Data
       </title>
    </head>

    <body>
    <?php
         print "\"JudgeID\",";
         print "\"FirstName\",";
         print "\"LastName\",";
         print "\"Address\",";
         print "\"City\",";
         print "\"State\",";
         print "\"Zip\",";
         print "\"Phone\",";
         print "\"ChurchID\",";
         print "\"Email\"";
         print "<br>\n";
         $results = mysql_query("select   JudgeID,
                                          FirstName,
                                          LastName,
                                          Address,
                                          City,
                                          State,
                                          Zip,
                                          Phone,
                                          ChurchID,
                                          Email
                                 from     $JudgesTable
                                 order by JudgeID")
                   or die ("Unable to get Judge list:" . mysql_error());


         while ($row = mysql_fetch_assoc($results))
         {
            $JudgeID    = $row['JudgeID'];
            $FirstName  = $row['FirstName'];
            $LastName   = $row['LastName'];
            $Address    = $row['Address'];
            $City       = $row['City'];
            $State      = $row['State'];
            $Zip        = $row['Zip'];
            $Phone      = $row['Phone'];
            $ChurchID   = $row['ChurchID'];
            $Email      = $row['Email'];


            print "\"$JudgeID\",";
            print "\"$FirstName\",";
            print "\"$LastName\",";
            print "\"$Address\",";
            print "\"$City\",";
            print "\"$State\",";
            print "\"$Zip\",";
            print "\"$Phone\",";
            print "\"$ChurchID\",";
            print "\"$Email\"";
            print "<br>\n";
         }
         ?>
         </table>
    </body>
</html>