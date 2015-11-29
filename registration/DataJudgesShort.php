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
         print "\"Name\",";
         print "\"Church\",";
         print "\"Location\"<br>";
         $results = $db->query("
                                  SELECT DISTINCT
                                          j.LastName,
                                          j.FirstName,
                                          c.ChurchName,
                                          c.ChurchState,
                                          c.ChurchCity
                                  FROM    $ChurchesTable         c,
                                          $JudgesTable           j,
                                          $JudgeAssignmentsTable a
                                  where    c.ChurchId=j.ChurchID
                                  and      j.JudgeID=a.JudgeID
                                  order by j.LastName
                                  ")
                   or die ("Unable to get Judge list:" . sqlError($db->errorInfo()));


         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            $FirstName  = $row['FirstName'];
            $LastName   = $row['LastName'];
            $ChurchName = $row['ChurchName'];
            $City       = $row['ChurchCity'];
            $State      = $row['ChurchState'];


            print "\"$FirstName $LastName\",";
            print "\"$ChurchName\",";
            print "\"$City, $State\"";
            print "<br>\n";
         }
         ?>
         </table>
    </body>
</html>