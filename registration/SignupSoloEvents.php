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
<meta http-equiv="Content-Language" content="en-us">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel=stylesheet href="include/registration.css" type="text/css" />

<title>Assign Individual Events</title>

</head>

<body>
<h1 align="center">Assign Individual Events</h1>
      <?php
         $results = $db->query("select FirstName,
                                        LastName,
                                        ParticipantID,
                                        Grade
                                 from   $ParticipantsTable
                                 where  ChurchID = $ChurchID
                                 order  by LastName
                                ")
                    or die ("Can not get Participant List:" . sqlError());

         $count = 0;
         ?>
         <table class='registrationTable' style='width: 95%'>
            <tr>
               <th width="70" align=center>Team</th>
               <th width="70" align=center>Individual</th>
               <th width="70" align=center>Grade</th>
               <th width="70" align=center>ID Number</th>
               <th align="left">Participant</th>
            </tr>
         <?php
         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            $ParticipantID = $row['ParticipantID'];
            $EventCounts   = EventCounts($ParticipantID);
            $TeamEvents    = $EventCounts['Team'];
            $IndivEvents   = $EventCounts['Solo'];

            ?>
            <tr>
               <td width="70" align="center"> <?php  print $TeamEvents?></td>
               <td width="70" align="center"> <?php  print $IndivEvents?></td>
               <td width="70" align="center"> <?php  print $row['Grade']?></td>
               <td width="70" align="center"> <?php  print $row['ParticipantID']?></td>
               <td>
                  <a href="AdminSoloEvents.php?ID=<?php  print $row['ParticipantID']; ?>" >
                     <?php  print $row['LastName'].", ".$row['FirstName']; ?>
                  </a>
               </td>
            </tr>
         <?php
         }
         ?>
         </table>

   <?php footer("","")?>

</body>

</html>
