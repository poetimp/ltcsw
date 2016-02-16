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

$MaxRows = 20;
$ErrorMsg = "";

$result = $db->query("select Name,
                              Phone,
                              Email
                       from   $NonParticipantsTable
                       where  ChurchID=$ChurchID")
          or die ("Unable to read NonParticipants table: ".sqlError());

$i = 0;
while ($row = $result->fetch(PDO::FETCH_ASSOC))
{
   $name[$i]  = $row['Name'];
   $phone[$i] = $row['Phone'];
   $email[$i] = $row['Email'];
   $i++;
}

if (isset($_POST['Update']))
{
   for ($i=0; $i<$MaxRows; $i++)
   {
      $name[$i]  = isset($_POST['Name'.$i])  ? $_POST['Name'.$i]  : "";
      $phone[$i] = isset($_POST['Phone'.$i]) ? $_POST['Phone'.$i] : "";
      $email[$i] = isset($_POST['Email'.$i]) ? $_POST['Email'.$i] : "";
   }

   $ErrorMsg="";
   for ($i=0; $i<$MaxRows and $ErrorMsg == ""; $i++)
   {
      if ($name[$i] != "")
      {
         if ($phone[$i] == "")
         {
            $ErrorMsg = "Phone number is required for: ".$name[$i];
         }
         else if ($email[$i] == "")
         {
            $ErrorMsg = "Please enter email address for: ".$name[$i]." or enter \"None\"";;
         }
         else if ($phone[$i] != "" and !ereg("^\([0-9]{3}\) [0-9]{3}-[0-9]{4}$",$phone[$i]))
         {
            $ErrorMsg = "Invalid Phone number format. Should be: (###) ###-####";
         }
      }
   }

   if ($ErrorMsg == "")
   {
      $db->query("delete from $NonParticipantsTable where ChurchID=$ChurchID")
            or die ("Unable to clear NonParticipants table: ".sqlError());
      for ($i=0; $i<$MaxRows and $ErrorMsg == ""; $i++)
      {
         if ($name[$i] != "")
         {
            $Name  = $name[$i];
            $Phone = $phone[$i];
            $Email = $email[$i];

            $db->query("insert into $NonParticipantsTable
                                (ChurchID,
                                 Name,
                                 Phone,
                                 Email)
                         values ('$ChurchID',
                                 '$Name',
                                 '$Phone',
                                 '$Email'
                                 )
                         ") or die ("Unable to insert into NonParticipants table: ".sqlError());
         }
      }
   }

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

<head>
<title>Non Participants</title>
</head>

<body  style="background-color: rgb(217, 217, 255);">
<h1 align="center">Non-Participant Signup</h1>
<?php
if ($ErrorMsg != "")
{
   print "<center><font color=\"FF0000\"><b>" . $ErrorMsg . "</b></font></center><br>";
}
else if (isset($_POST['Update']))
{
   print "<center><font color=\"FF0000\"><b>Updated</b></font></center><br>";
}
?>
<p>Every year the LTCSW Board depends upon many volunteers to make the
convention run smoothly. This includes a wide variety of jobs:</p>
<ul>
   <li>Tally Room Runners</li>
   <li>Hall Monitors</li>
   <li>Event Coordinator Assistants</li>
   <li> plus other jobs, too.</li>
</ul>
<p>These jobs are very important to a successful convention. If you have a few
people that are willing to help then please add their names below.</p>
<table border="1" width="100%" id="table1" bordercolor="#000000">
   <tr>
      <td><b>Note: </b>This is a perfect job for someone who may have recently graduated from the LTC program and is having withdrawal pains and feeling a little left out of all
the LTC excitement. Please add below the names of anyone willing to volunteer
      their time at the convention. </td>
   </tr>
</table>
<p>This is only open to people that have completed
high school and are no longer eligible to participate in the LTC events.
Depending upon how many names are submitted we can not guarantee that everyone will
be used.&nbsp; </p>
<form method="post" action=NonParticipants.php>
   <table border="1" width="100%" id="table2">
      <tr>
         <td width="33%" bgcolor="#000000"><font color="#FFFF00">Name</font></td>
         <td width="33%" bgcolor="#000000"><font color="#FFFF00">Phone Number
         </font></td>
         <td width="33%" bgcolor="#000000"><font color="#FFFF00">Email Address</font></td>
      </tr>
      <?php
      for ($i=0; $i<=$MaxRows; $i++)
      {
      ?>
         <tr>
            <td width="33%"><input type="text" name="Name<?php print $i;?>"  size="30" <?php  print "value=\"".(isset($name[$i])  ? $name[$i]  : "")."\"";?>></td>
            <td width="33%"><input type="text" name="Phone<?php print $i;?>" size="30" <?php  print "value=\"".(isset($phone[$i]) ? $phone[$i] : "")."\"";?>></td>
            <td width="33%"><input type="text" name="Email<?php print $i;?>" size="30" <?php  print "value=\"".(isset($email[$i]) ? $email[$i] : "")."\"";?>></td>
         </tr>
      <?php
      }
      ?>
   </table>
   <p align="center"><input type="submit" value="Update" name="Update"><br>
   Be sure to <a href="ExtraOrders.php">order these people a meal ticket</a> because they will be hungry!</p>

</form>

<?php footer("","")?>

</body>

</html>
