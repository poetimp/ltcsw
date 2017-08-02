<?php
//----------------------------------------------------------------------------
// This software is licensed under the MIT license. Use as you wish but give
// and take credit where due.
//
// Author: Paul Lemmons
//----------------------------------------------------------------------------
// TODO Collect Shirt Size
// TODO Collect Room need
// TODO Collect Church
// TODO Collect Availability
// TODO Push on main page
?>
<?php
include 'include/RegFunctions.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

   <head>
      <meta http-equiv="Content-Language" content="en-us">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel=stylesheet href="include/registration.css" type="text/css" />

      <title>Charmers</title>
   </head>

   <body >
      <h1 align="center">Manage Charmers</h1>
      <p>Every year the LTCSW Board depends upon many volunteers to make the
         convention run smoothly. This includes a wide variety of jobs:
      </p>
      <ul>
         <li>Tally Room Runners</li>
         <li>Hall Monitors</li>
         <li>Event Coordinator Assistants</li>
         <li>And many more!</li>
      </ul>
      <p>These jobs are very important to a successful convention. If you have a few
         people that are willing to help then please add their names below.
      </p>

      <form method="post" action=AdminCharmers.php>
      <?php
         $results = $db->query("select    charmerID,
                                          charmerName,
                                          charmerSex,
                                          charmerTshirtSize,
                                          charmerTshirtNeeded,
                                          charmerNeedRoom,
                                          charmerAvailibility,
                                          charmerEmail,
                                          charmerPhone
                                 from     $CharmersTable
                                 where    ChurchID=$ChurchID
                                 Order by charmerName")
                    or die ("Unable to get charmer list:" . sqlError());

         ?>
               <table class='registrationTable' style='width: 95%'>
                  <tr>
                     <th width="70" align="center">View</th>
                     <th width="70" align="center">Update</th>
                     <th width="70" align="center">Delete</th>
                     <th>Name</th>
                     <th>Sex</th>
                     <th>Need Room</th>
                     <th>Shirt Needed</th>
                     <th>Shirt Size</th>
                     <th>Phone</th>
                     <th>Email</th>
                     <th>Availability</th>
                  </tr>
               <?php
               while ($row = $results->fetch(PDO::FETCH_ASSOC))
               {
                  ?>
                  <tr>
                     <td width="70" align="center">[<a href="AdminCharmers.php?action=view<?php  print "&id=".$row['charmerID']; ?>">View</a>]</td>
                     <td width="70" align="center">[<a href="AdminCharmers.php?action=update<?php  print "&id=".$row['charmerID']; ?>">Update</a>]</td>
                     <td width="70" align="center">[<a href="DelCharmer.php?<?php  print "id=".$row['charmerID']."&name=".$row['charmerName']; ?>">Delete</a>]</td>
                     <td>               <?php  print $row['charmerName'];                                      ?></td>
                     <td align="center"><?php  print $row['charmerSex'];                                       ?></td>
                     <td align="center"><?php  print $row['charmerNeedRoom']     == 'on' ? "Yes" : "No";       ?></td>
                     <td align="center"><?php  print $row['charmerTshirtNeeded'] == 'on' ? "Yes" : "No";       ?></td>
                     <td>               <?php  print $row['charmerTshirtSize'];                                ?></td>
                     <td>               <?php  print $row['charmerPhone'];                                     ?></td>
                     <td>               <?php  print $row['charmerEmail'];                                     ?></td>
                     <td>               <?php  print preg_replace("/\n/","<br>\n",$row['charmerAvailibility']);?></td>
                  </tr>
               <?php
               }
               ?>
               </table>
         <p align="center"><input type="submit" value="Add New" name="AddNew"><br>
         <b>Don't forget to order extra meals for your charmer!</b>
         </p>
      </form>

   <?php footer("","")?>

   </body>

</html>
