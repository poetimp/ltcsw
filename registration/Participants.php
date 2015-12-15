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
<meta http-equiv="Content-Language" content="en-us">
<title>Maintain Participants</title>

</head>

<body style="background-color: rgb(217, 217, 255);">
<h1 align="center">Participant Maintenance </h1>
<form method="post" action=Participants.php>
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
         <table border="1" width="100%">
            <tr>
               <td width="70" align="center" bgcolor="#000000" colspan=3>
				      <span style="background-color: #000000">
				         <font color="#FFFF00">
				            Action
				         </font>
				      </span>
				   </td>
               <td width="100" align="center" bgcolor="#000000">
				      <span style="background-color: #000000">
				         <font color="#FFFF00">
				            ID Number
				         </font>
				      </span>
				   </td>

               <td align="left" bgcolor="#000000">
				      <span style="background-color: #000000">
				         <font color="#FFFF00">
				            Participant Name
				         </font>
				      </span>
				   </td>
            </tr>
         <?php
         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            ?>
            <tr>
               <td width="70" align="center">[<a href="AdminParticipant.php?action=view<?php  print "&ParticipantID=".$row['ParticipantID']; ?>">View</a>]</td>
               <td width="70" align="center">[<a href="AdminParticipant.php?action=update<?php  print "&ParticipantID=".$row['ParticipantID']; ?>">Update</a>]</td>
               <td width="70" align="center"> [<a href="DelParticipant.php?action=del<?php  print "&ParticipantID=".$row['ParticipantID']."&Name=".urlencode($row['LastName'].", ".$row['FirstName']); ?>">Delete</a>]</td>
               <td width="100" align="center"><?php  print $row['ParticipantID'];?></td>
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
   <p align="center"><input type="submit" value="Add New" name="AddNew"></p>
   <?php
   }
   ?>
</form>
<?php footer("","")?>

</body>

</html>