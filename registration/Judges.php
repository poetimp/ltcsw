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
<meta http-equiv="Content-Language" content="en-us">
<title>Maintain Judges</title>

</head>

<body style="background-color: rgb(217, 217, 255);">
<h1 align="center">Judges Maintenance </h1>
<form method="post" action=Judges.php>
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
         <table border="1" width="100%">
            <tr>
               <TD align="center" colspan="3" bgcolor="Black"><font color="Yellow"><b>Action</b></font></TD>
               <TD bgcolor="Black"><font color="Yellow"><b>Assignments</b></font></TD>
               <TD bgcolor="Black"><font color="Yellow"><b>[ID]: Name</b></font></TD>
            </tr>
         <?php
         while ($row = $JudgeList->fetch(PDO::FETCH_ASSOC))
         {
            ?>
            <tr>
               <td width="5%" align="center">[<a href="AdminJudges.php?action=view<?php  print "&JudgeID=".$row['JudgeID']; ?>">View</a>]</td>
               <td width="5%" align="center">[<a href="AdminJudges.php?action=update<?php  print "&JudgeID=".$row['JudgeID']; ?>">Update</a>]</td>
               <td width="5%" align="center"> [<a href="DelJudge.php?action=del<?php  print "&JudgeID=".$row['JudgeID']."&Name=".urlencode($row['LastName'].", ".$row['FirstName']); ?>">Delete</a>]</td>
               <td width="5%" align="center">
                  <?php
                     $TimesList = $db->query("select   count(*) Count
                                             from     $JudgeAssignmentsTable
                                             where    JudgeID = " .$row['JudgeID'])
                     or die ("Unable to obtain Judges Times Count:" . sqlError());

                     $TimeRow = $TimesList->fetch(PDO::FETCH_ASSOC);
                     print $TimeRow['Count'];
                  ?>
               </td>
               <td width="80%"><?php  print "[".$row['JudgeID']."]: ".$row['LastName'].", ".$row['FirstName']; ?></td>
            </tr>
         <?php
         }
         ?>
         </table>
   <p align="center"><input type="submit" value="Add New" name="AddNew"></p>
</form>
<?php footer("","")?>

</body>

</html>