<?php
include 'include/RegFunctions.php';

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

if (isset($_POST['Reports']))
{
   mysql_query("update $UsersTable
                set    Status  = 'R'
                where  Admin   = 'N'
                and    Status != 'L'
                ")
   or die ("Unable to lock users".mysql_error());
}
else if (isset($_POST['Lock']))
{
   mysql_query("update $UsersTable
                set    Status = 'L'
                where  Admin  = 'N'
                ")
   or die ("Unable to lock users".mysql_error());
}
else if (isset($_POST['Unlock']))
{
   mysql_query("update $UsersTable
                set    Status = 'O'
                where  Admin  = 'N'
                ")
   or die ("Unable to lock users".mysql_error());
}
else if (isset($_POST['Open']))
{
   mysql_query("update $UsersTable
                set    Status  = 'O'
                where  Admin   = 'N'
                and    Status != 'L'
               ")
   or die ("Unable to lock users".mysql_error());
}
else if (isset($_POST['Close']))
{
   mysql_query("update $UsersTable
                set    Status  = 'C'
                where  Admin   = 'N'
                and    Status != 'L'
                ")
   or die ("Unable to lock users".mysql_error());
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

<head>
<title>Lock or Unlock Registration</title>

</head>

<body style="background-color: rgb(217, 217, 255);">
<h1 align="center">Open or Close Registration </h1>
<?php
if (isset($_POST['Lock']))
{
   print "<h3 align=center><font color=\"#FF0000\">All non-administrative users have been locked</font></h3>";
}
else if (isset($_POST['Unlock']))
{
   print "<h3 align=center><font color=\"#FF0000\">All non-administrative users have been unlocked</font></h3>";
}
else if (isset($_POST['Open']))
{
   print "<h3 align=center><font color=\"#FF0000\">Registration is opened</font></h3>";
   print "<h3 align=center><font color=\"#FF0000\">All unlocked users may now register new participants</font></h3>";
}
else if (isset($_POST['Close']))
{
   print "<h3 align=center><font color=\"#FF0000\">Registration is closed</font></h3>";
   print "<h3 align=center><font color=\"#FF0000\">Only Administrative users may now add new participants</font></h3>";
}
?>
<form method="post" action=LockRegistration.php>
      <?php
         $results = mysql_query("select   Userid,
                                          ChurchID,
                                          Name,
                                          Admin,
                                          Status
                                 from     $UsersTable
                                 Order by ChurchID,
                                          Userid")
                    or die ("Unable to get user list:" . mysql_error());

         ?>
         <table border="1" width="100%">
            <tr>
               <td bgcolor="#000000"><font color="#FFFF00">Userid</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">User Name</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Church Name</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Administrator</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Status</font></td>
            </tr>
         <?php
         while ($row = mysql_fetch_assoc($results))
         {
            $chResult = mysql_query("select ChurchName
                                     from   $ChurchesTable
                                     where  ChurchID = ".$row['ChurchID']
                                    )
                        or die ("Unable to get Church Name:" . mysql_error());
            $chRow      = mysql_fetch_assoc($chResult);
            $ChurchName = $chRow['ChurchName'];

            if ($row['Status'] == 'O')
            {
               $row['Status'] = 'Open';
            }
            else if ($row['Status'] == 'C')
            {
               $row['Status'] = 'Closed';
            }
            else if ($row['Status'] == 'L')
            {
               $row['Status'] = 'Locked';
            }
            else if ($row['Status'] == 'R')
            {
               $row['Status'] = 'Report Only';
            }
            else
            {
               $row['Status'] = 'Unknown';
            }
            ?>
            <tr>
               <td><?php  print $row['Userid']; ?></td>
               <td><?php  print $row['Name']; ?></td>
               <td><?php  print $ChurchName; ?></td>
               <td align=center><?php  print $row['Admin']; ?></td>
               <td align=center><?php  print $row['Status']; ?></td>
            </tr>
         <?php
         }
         ?>
         </table>
   <p align="center">
      <input type="submit" value="Close Registration" name="Close">
      <input type="submit" value="Open Registration" name="Open">
   </p>
   <p align="center">
      <input type="submit" value="Lock Users" name="Lock">
      <input type="submit" value="Unlock Users" name="Unlock">
   </p>
   <p align="center">
      <input type="submit" value="Reports Only" name="Reports">
   </p>
</form>
<?php footer("","")?>

</body>

</html>