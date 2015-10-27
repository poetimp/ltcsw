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
<meta http-equiv="Content-Language" content="en-us">
<title>Maintain Users</title>

</head>

<body style="background-color: rgb(217, 217, 255);">
<h1 align="center">User Maintenance </h1>
<form method="post" action=Users.php>
      <?php
         $results = mysql_query("select   u.Userid,
                                          c.ChurchName,
                                          u.Name,
                                          u.Email,
                                          u.Admin,
                                          u.Status,
                                          u.Password,
                                          u.lastLogin
                                 from     $UsersTable    u,
                                          $ChurchesTable c
                                 where    c.ChurchID=u.ChurchID
                                 Order by ".$sort." ".$order)
                    or die ("Unable to get user list:" . mysql_error());

         if ($order == "asc")
            $order = "desc";
         else
            $order = "asc";

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
                  <a href="Users.php?sort=Userid&order=<?php print $order; ?>">
                     <font color="#FFFF00">Userid</font>
                  </a>
               </td>
               <td bgcolor="#000000">
                  <a href="Users.php?sort=Name&order=<?php print $order; ?>">
                     <font color="#FFFF00">User Name</font>
                  </a>
               </td>
               <td bgcolor="#000000">
                  <a href="Users.php?sort=Email&order=<?php print $order; ?>">
                     <font color="#FFFF00">Email</font>
                  </a>
               </td>
               <td bgcolor="#000000">
                  <a href="Users.php?sort=ChurchName&order=<?php print $order; ?>">
                     <font color="#FFFF00">Church Name</font>
                  </a>
               </td>
               <td bgcolor="#000000" align="center">
                  <a href="Users.php?sort=Status&order=<?php print $order; ?>">
                     <font color="#FFFF00">Status</font>
                  </a>
               </td>
               <td bgcolor="#000000" align="center">
                  <a href="Users.php?sort=Admin&order=<?php print $order; ?>">
                     <font color="#FFFF00">Administrator</font>
                  </a>
               </td>
               <td bgcolor="#000000" align="center">
                  <a href="Users.php?sort=lastLogin&order=<?php print $order; ?>">
                     <font color="#FFFF00">Last Login</font>
                  </a>
               </td>
            </tr>
         <?php
         while ($row = mysql_fetch_assoc($results))
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
               <td width="70" align="center">[<a href="AdminUser.php?action=view<?php  print "&Userid=".$row['Userid']; ?>">View</a>]</td>
               <td width="70" align="center">[<a href="AdminUser.php?action=update<?php  print "&Userid=".$row['Userid']; ?>">Update</a>]</td>
               <td width="70" align="center">[<a href="DelUser.php?action=del<?php  print "&Userid=".$row['Userid']; ?>">Delete</a>]</td>
               <td><?php  print $row['Userid']; ?></td>
               <td><?php  print $row['Name']; ?></td>
               <td><?php  print $row['Email']; ?></td>
               <td><?php  print $ChurchName; ?></td>
               <td align="center"><?php  print $row['Status'];    ?></td>
               <td align="center"><?php  print $row['Admin'];     ?></td>
               <td align="center"><?php  print $row['lastLogin'] == '0000-00-00 00:00:00' ? 'Never' : $row['lastLogin']; ?></td>
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
