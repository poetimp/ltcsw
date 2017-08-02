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

if (isset($_POST['Reports']))
{
   $db->query("update $UsersTable
                set    Status  = 'R'
                where  Admin   = 'N'
                and    Status != 'L'
                ")
   or die ("Unable to lock users".sqlError());
}
else if (isset($_POST['Lock']))
{
   $db->query("update $UsersTable
                set    Status = 'L'
                where  Admin  = 'N'
                ")
   or die ("Unable to lock users".sqlError());
}
else if (isset($_POST['Unlock']))
{
   $db->query("update $UsersTable
                set    Status = 'O'
                where  Admin  = 'N'
                ")
   or die ("Unable to lock users".sqlError());
}
else if (isset($_POST['Open']))
{
   $db->query("update $UsersTable
                set    Status  = 'O'
                where  Admin   = 'N'
                and    Status != 'L'
               ")
   or die ("Unable to lock users".sqlError());
}
else if (isset($_POST['Close']))
{
   $db->query("update $UsersTable
                set    Status  = 'C'
                where  Admin   = 'N'
                and    Status != 'L'
                ")
   or die ("Unable to lock users".sqlError());
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

<head>
   <meta http-equiv="Content-Language" content="en-us">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel=stylesheet href="include/registration.css" type="text/css" />
   <title>Lock or Unlock Registration</title>
</head>

<body>
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
         $results = $db->query("select   Userid,
                                          ChurchID,
                                          Name,
                                          Admin,
                                          Status
                                 from     $UsersTable
                                 Order by ChurchID,
                                          Userid")
                    or die ("Unable to get user list:" . sqlError());

         ?>
         <table class='registrationTable' style='width: 95%'>
            <tr>
               <th style='text-align:left'>Userid</th>
               <th>User Name</th>
               <th>Church Name</th>
               <th>Administrator</th>
               <th>Status</th>
            </tr>
         <?php
         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            $chResult = $db->query("select ChurchName
                                     from   $ChurchesTable
                                     where  ChurchID = ".$row['ChurchID']
                                    )
                        or die ("Unable to get Church Name:" . sqlError());
            $chRow      = $chResult->fetch(PDO::FETCH_ASSOC);
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
               <td style='text-align:left'><?php  print $row['Userid']; ?></td>
               <td><?php  print $row['Name']; ?></td>
               <td><?php  print $ChurchName; ?></td>
               <td align='center'><?php  print $row['Admin']; ?></td>
               <td align='center'><?php  print $row['Status']; ?></td>
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