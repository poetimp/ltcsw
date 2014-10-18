<?php
include 'include/RegFunctions.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <title>
          Church Coordinatord
       </title>
    </head>

    <body bgcolor="White">
    <h1 align="center">Event Coordinators</h1>
    <hr>
    <?php
         $results = mysql_query("select   c.Name,
                                          c.Phone,
                                          c.Email,
                                          c.Address,
                                          c.City,
                                          c.State,
                                          c.Zip,
                                          e.EventName
                                 from     $EventsTable     e,
                                          $EventCoordTable c
                                 where    e.CoordID = c.CoordID
                                 order by EventName")
                    or die ("Not found:" . mysql_error());
         $first = 1;
         ?>
         <table border="0" width="100%" id="table1">
         <?php
         while ($row = mysql_fetch_assoc($results))
         {
            $CoordName    = $row['Name'];
            $CoordPhone   = $row['Phone'];
            $CoordEmail   = $row['Email'];
            $CoordAddr    = $row['Address'];
            $CoordCity    = $row['City'];
            $CoordState   = $row['State'];
            $CoordZip     = $row['Zip'];
            $EventName    = $row['EventName'];
         ?>
            <tr>
               <td><b><?php  print $EventName; ?></b></td>
               <td><?php  print $CoordName; ?></td>
            </tr>
         <?php
         }
         ?>
         </table>

    </body>

</html>