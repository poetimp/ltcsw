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

if (isset($_POST['AddNew']))
{
   header("refresh: 0; URL=AdminJudges.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

<head>
<meta http-equiv="Content-Language" content="en-us" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="include/registration.css" type="text/css" />
<title>Maintain Judges</title>

</head>

<body>
<h1 align="center">Judges Maintenance </h1>
<form method="post">
      <?php
         $JudgeList = $db->query("select   FirstName,
                                            LastName,
                                            JudgeID
                                   from     $JudgesTable
                                   where    ChurchID = $ChurchID
                                   order by LastName, FirstName")
         or die ("Unable to obtain Judges List:" . sqlError());

         $count = 0;
         ?>
         <table class='registrationTable'>
            <tr>
               <th style='text-align: center' colspan="3"><h3>Action</h3></th>
               <th><h3>Assignments</h3></th>
               <th><h3>Name</h3></th>
            </tr>
         <?php
         while ($row = $JudgeList->fetch(PDO::FETCH_ASSOC))
         {
            ?>
            <tr>
               <td style='width: 5%; text-align: center'>[<a href="AdminJudges.php?action=view<?php  print "&JudgeID=".$row['JudgeID']; ?>">View</a>]</td>
               <td style='width: 5%; text-align: center'>[<a href="AdminJudges.php?action=update<?php  print "&JudgeID=".$row['JudgeID']; ?>">Update</a>]</td>
               <td style='width: 5%; text-align: center'> [<a href="DelJudge.php?action=del<?php  print "&JudgeID=".$row['JudgeID']."&Name=".urlencode($row['LastName'].", ".$row['FirstName']); ?>">Delete</a>]</td>
               <td style='width: 5%; text-align: center'>
                  <?php
                     $TimesList = $db->query("select   count(*) Count
                                             from     $JudgeAssignmentsTable
                                             where    JudgeID = " .$row['JudgeID'])
                     or die ("Unable to obtain Judges Times Count:" . sqlError());

                     $TimeRow = $TimesList->fetch(PDO::FETCH_ASSOC);
                     print $TimeRow['Count'];
                  ?>
               </td>
               <td style='width: 80%;'><?php  print $row['LastName'].", ".$row['FirstName']; ?></td>
            </tr>
         <?php
         }
         ?>
         </table>
   <p align="center"><input type="submit" value="Add New" name="AddNew" /></p>
</form>
<?php footer("","")?>

</body>

</html>