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
          Team Data
       </title>
    </head>

    <body>
    <?php
         print "\"TeamID\",";
         print "\"EventName\",";
         print "\"MinGrade\",";
         print "\"MaxGrade\",";
         print "\"ChurchID\",";
         print "\"Comments\"";
         print "<br>\n";
         $results = mysql_query("select   t.TeamID,
                                          e.EventName,
                                          e.MinGrade,
                                          e.MaxGrade,
                                          t.ChurchID,
                                          t.Comment
                                 from     $TeamsTable  t,
                                          $EventsTable e
                                 where    t.EventID = e.EventID
                                 order by ChurchID")
                   or die ("Unable to get team list:" . mysql_error());


         while ($row = mysql_fetch_assoc($results))
         {
            $TeamID    = $row['TeamID'];
            $EventName = $row['EventName'];
            $MinGrade  = $row['MinGrade'];
            $MaxGrade  = $row['MaxGrade'];
            $ChurchID  = $row['ChurchID'];
            $Comments  = $row['Comment'];


            print "\"$TeamID\",";
            print "\"$EventName\",";
            print "\"$MinGrade\",";
            print "\"$MaxGrade\",";
            print "\"$ChurchID\",";
            print "\"$Comments\"";
            print "<br>\n";
         }
         ?>
         </table>
    </body>
</html>