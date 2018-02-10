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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <title>
          Church Coordinatord
       </title>
       <meta http-equiv="Content-Language" content="en-us" />
       <meta name="viewport" content="width=device-width, initial-scale=1.0" />
       <link rel="stylesheet" href="include/registration.css" type="text/css" />
    </head>

    <body>
    <h1 align="center">Event Coordinators</h1>
    <hr />
    <?php
         $results = $db->query("select   c.Name,
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
                    or die ("Not found:" . sqlError());
         $first = 1;
         ?>
         <table class='registrationTable' id="table1">
         <?php
         while ($row = $results->fetch(PDO::FETCH_ASSOC))
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
