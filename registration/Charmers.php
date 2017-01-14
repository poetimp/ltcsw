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
      <title>Charmers</title>
   </head>

   <body  style="background-color: rgb(217, 217, 255);">
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
               <table border="1" width="100%">
                  <tr>
                     <td width="70" align="center" bgcolor="#000000">
                        <font color="#FFFF00">View</font>
                     </td>
                     <td width="70" align="center" bgcolor="#000000">
                        <font color="#FFFF00">Update</font>
                     </td>
                     <td width="70" align="center" bgcolor="#000000">
                        <font color="#FFFF00">Delete</font>
                     </td>
                     <td bgcolor="#000000">
                        <font color="#FFFF00">Name</font>
                     </td>
                     <td bgcolor="#000000">
                        <font color="#FFFF00">Sex</font>
                     </td>
                     <td bgcolor="#000000">
                        <font color="#FFFF00">Need Room</font>
                     </td>
                     <td bgcolor="#000000">
                        <font color="#FFFF00">Shirt Needed</font>
                     </td>
                     <td bgcolor="#000000">
                        <font color="#FFFF00">Shirt Size</font>
                     </td>
                     <td bgcolor="#000000"">
                        <font color="#FFFF00">Phone</font>
                     </td>
                     <td bgcolor="#000000">
                        <font color="#FFFF00">Email</font>
                     </td>
                     <td bgcolor="#000000">
                        <font color="#FFFF00">Availability</font>
                     </td>
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
