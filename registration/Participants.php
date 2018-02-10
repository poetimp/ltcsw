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

if ($UserStatus == 'O' and isset($_POST['AddNew']))
{
   header("refresh: 0; URL=AdminParticipant.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

   <head>
      <meta http-equiv="Content-Language" content="en-us" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="stylesheet" href="include/registration.css" type="text/css" />

      <title>Maintain Participants</title>

   </head>

   <body>
   <h1 align="center">Participant Maintenance </h1>
   <form method="post">
         <?php
            $results = $db->query("select   FirstName,
                                             LastName,
                                             ParticipantID
                                    from     $ParticipantsTable
                                    where    ChurchID = '$ChurchID'
                                    order by LastName,
                                             FirstName")
                       or die ("Participant Not found:" . sqlError());

            $count = 0;
            ?>
            <table class='registrationTable'>
               <tr>
                  <th style='width: 70px; text-align: center;'colspan='3'>Action</th>
                  <th style='width: 100px; text-align: center;'>ID Number</th>
                  <th style='text-align: left'>Participant Name</th>
               </tr>
            <?php
            while ($row = $results->fetch(PDO::FETCH_ASSOC))
            {
               ?>
               <tr>
                  <td style='width: 70px; text-align: center;'>[<a href="AdminParticipant.php?action=view<?php  print "&ParticipantID=".$row['ParticipantID']; ?>">View</a>]</td>
                  <td style='width: 70px; text-align: center;'>[<a href="AdminParticipant.php?action=update<?php  print "&ParticipantID=".$row['ParticipantID']; ?>">Update</a>]</td>
                  <td style='width: 70px; text-align: center;'> [<a href="DelParticipant.php?action=del<?php  print "&ParticipantID=".$row['ParticipantID']."&Name=".urlencode($row['LastName'].", ".$row['FirstName']); ?>">Delete</a>]</td>
                  <td style='width: 100px; text-align: center;'><?php  print $row['ParticipantID'];?></td>
                  <td><?php  print $row['LastName'].", ".$row['FirstName']; ?></td>
               </tr>
            <?php
            }
            ?>
            </table>
      <?php
      if ($UserStatus == 'O')
      {
      ?>
      <p align="center"><input type="submit" value="Add New" name="AddNew" /></p>
      <?php
      }
      ?>
   </form>
   <?php footer("","")?>

   </body>

</html>