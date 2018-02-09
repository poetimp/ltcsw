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

if (isset($_POST['AddNew']))
{
   header("refresh: 0; URL=AdminUser.php");
}

if (isset($_REQUEST['sort']))
{
   $sort = $_REQUEST['sort'];
}
else
{
   $sort = "Name";
}

if (isset($_REQUEST['order']))
{
   $order = $_REQUEST['order'];
}
else
{
   $order = "asc";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

   <head>
      <meta http-equiv="Content-Language" content="en-us" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="stylesheet" href="include/registration.css" type="text/css" />

     <title>Maintain Users</title>

   </head>

<body>
<h1 align="center">User Maintenance </h1>
<form method="post" action=Users.php>
      <?php
         $results = $db->query("select   u.Userid,
                                          c.ChurchName,
                                          u.Name,
                                          u.Email,
                                          u.Admin,
                                          u.Status,
                                          u.Password,
                                          u.lastLogin,
                                          u.loginCount
                                 from     $UsersTable    u,
                                          $ChurchesTable c
                                 where    c.ChurchID=u.ChurchID
                                 Order by ".$sort." ".$order)
                    or die ("Unable to get user list:" . sqlError());

         if ($order == "asc")
            $order = "desc";
         else
            $order = "asc";

         ?>
         <table class='registrationTable'>
            <tr>
               <th style='width: 70px; text-align: center;'>View</th>
               <th style='width: 70px; text-align: center;'>Update</th>
               <th style='width: 70px; text-align: center;'>Delete</th>
               <th>
                  <a style='color: white' href="Users.php?sort=Userid&order=<?php print $order; ?>">
                     Userid
                  </a>
               </th>
               <th>
                  <a style='color: white' href="Users.php?sort=Name&order=<?php print $order; ?>">
                     User Name
                  </a>
               </th>
               <th>
                  <a style='color: white' href="Users.php?sort=Email&order=<?php print $order; ?>">
                     Email
                  </a>
               </th>
               <th>
                  <a style='color: white' href="Users.php?sort=ChurchName&order=<?php print $order; ?>">
                     Church Name
                  </a>
               </th>
               <th style='text-align: center'>
                  <a style='color: white' href="Users.php?sort=Status&order=<?php print $order; ?>">
                     Status
                  </a>
               </th>
               <th style='text-align: center'>
                  <a style='color: white' href="Users.php?sort=Admin&order=<?php print $order; ?>">
                     Administrator
                  </a>
               </th>
               <th style='text-align: center'>
                  <a style='color: white' href="Users.php?sort=lastLogin&order=<?php print $order; ?>">
                     Last Login
                  </a>
               </th>
            </tr>
         <?php
         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            $password   = $row['Password'];
            $ChurchName = $row['ChurchName'];

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
               <td style='width: 70px; text-align: center;'>[<a href="AdminUser.php?action=view<?php  print "&Userid=".$row['Userid']; ?>">View</a>]</td>
               <td style='width: 70px; text-align: center;'>[<a href="AdminUser.php?action=update<?php  print "&Userid=".$row['Userid']; ?>">Update</a>]</td>
               <td style='width: 70px; text-align: center;'>[<a href="DelUser.php?action=del<?php  print "&Userid=".$row['Userid']; ?>">Delete</a>]</td>
               <td><?php  print $row['Userid']; ?></td>
               <td><?php  print $row['Name']; ?></td>
               <td><?php  print $row['Email']; ?></td>
               <td><?php  print $ChurchName; ?></td>
               <td style='text-align: center'><?php  print $row['Status'];    ?></td>
               <td style='text-align: center'><?php  print $row['Admin'];     ?></td>
               <td style='text-align: center'><?php  print $row['loginCount'] == 0 ? 'Never' : $row['lastLogin']; ?></td>
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
