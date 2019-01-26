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
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <title>
          Seniors
       </title>
       <meta http-equiv="Content-Language" content="en-us" />
       <meta name="viewport" content="width=device-width, initial-scale=1.0" />
       <link rel="stylesheet" href="include/registration.css" type="text/css" />
    </head>

    <body>
    <h1 align="center">Event Coordinators</h1>
    <hr />
    <?php
         $results = $db->query("SELECT c.Name,
                                       c.Address,
                                       c.City,
                                       c.State,
                                       c.Zip,
                                       c.Phone,
                                       c.Email,
                                       e.EventName
                                FROM $EventCoordTable c,
                                     $EventsTable     e
                                where e.CoordID = c.CoordID
                                order by e.EventName")
                    or die ("Not found:" . sqlError());
         $first = 1;
         ?>
         <table class='registrationTable'>
         <?php
         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            $Name      = $row['Name'];
            $Address   = $row['Address'];
            $City      = $row['City'];
            $State     = $row['State'];
            $Zip       = $row['Zip'];
            $Phone     = $row['Phone'];
            $Email     = $row['Email'];
            $EventName = $row['EventName'];

            if ($first == 0)
            {
            ?>
            <?php
            }
            else
            {
               $first = 0;
            }
            ?>
            <tr>
               <th style="width:20%"><?php print $EventName?></th>
               <td><?php print $Name?></td>
               <td><?php print "$Address $City, $State $Zip"; ?></td>
               <td><?php print $Phone?></td>
               <td><?php print $Email?></td>
            </tr>
            <?php
         }
      ?>
         </table>
    </body>
</html>
