<?php
require 'include/RegFunctions.php';

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

$ConvCode    = $_SESSION['ConvCode'];

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update')
{
   $mode = 'update';
   $NewUserid = $_REQUEST['Userid'];
}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')
{
   $mode = 'view';
   $NewUserid = $_REQUEST['Userid'];
}
else #if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add')
{
   $mode = 'add';
   $Name        = "";
   $Password    = "";
   $IsAdmin     = "";
   $Status      = "";
   $NewUserid   = "";
   $NewChurchID = "";
}

$ErrorMsg = "";

if ($mode == 'update' || $mode == 'view')
{
   $result = mysql_query("select *
                          from   $UsersTable
                          where  Userid='$NewUserid'
                         ")
             or die ("Unable to get User information: ".mysql_error());
   $row = mysql_fetch_assoc($result);

   $NewUserID    = isset($row['Userid'])    ? $row['Userid']    : "";
   $NewChurchID  = isset($row['ChurchID'])  ? $row['ChurchID']  : "";
   $Name         = isset($row['Name'])      ? $row['Name']      : "";
   $Password     = isset($row['Password'])  ? $row['Password']  : "";
   $IsAdmin      = isset($row['Admin'])     ? $row['Admin']     : "";
   $Status       = isset($row['Status'])    ? $row['Status']    : "";

}

if (isset($_POST['add']) or isset($_POST['update']))
{
   if (isset($_POST['add']))
   {
      $mode = 'add';
   }
   else
   {
      $mode = 'update';
   }

   $NewUserid    = isset($_POST['Userid'])    ? $_POST['Userid']    : "";
   $NewChurchID  = isset($_POST['ChurchID'])  ? $_POST['ChurchID']  : "";
   $Name         = isset($_POST['Name'])      ? $_POST['Name']      : "";
   $Password     = isset($_POST['Password'])  ? $_POST['Password']  : "";
   $IsAdmin      = isset($_POST['Admin'])     ? $_POST['Admin']     : "";
   $Status       = isset($_POST['Status'])    ? $_POST['Status']    : "";

   if ($NewUserid == "")
   {
      $ErrorMsg = "Please enter the required field: Userid";
   }
   else if ($Name == "")
   {
      $ErrorMsg = "Please enter the required field: Name";
   }
   else if ($Password == "")
   {
      $ErrorMsg = "Please enter the required field: Password";
   }
   else if ($NewChurchID == "" or $NewChurchID == '0')
   {
      $ErrorMsg = "Please enter the required field: Church";
   }
   else if ($IsAdmin == "")
   {
      $ErrorMsg = "Please Indicate if person is an  Administrator or not";
   }
   else if ($Status == "")
   {
      $ErrorMsg = "Please Indicate the status of the account";
   }

   if ($ErrorMsg == "")
   {
      ereg_replace("'","''",$Name);
      ereg_replace("'","''",$Password);

      if ($mode == 'update')
      {
         $results = mysql_query("update $UsersTable
                                 set    ChurchID  = '$NewChurchID',
                                        Name      = '$Name',
                                        Password  = '$Password',
                                        Admin     = '$IsAdmin',
                                        Status    = '$Status'
                                 where  Userid    = '$NewUserid'
                                ")
                    or die ("Unable to process update: " . mysql_error());
      }
      else
      {
         $results = mysql_query("select count(*) as count
                                 from   $UsersTable
                                 where  Userid = '$NewUserid'
                                ")
                    or die ("Unable to process update: " . mysql_error());
         $row = mysql_fetch_assoc($results);
         $count = $row['count'];
         if ($count == 0)
         {
            mysql_query("insert into $UsersTable
                                    (Userid   ,
                                     ChurchID ,
                                     Name     ,
                                     Password ,
                                     Admin    ,
                                     Status   ,
                                     ConvCode)
                             values ('$NewUserid'  ,
                                     '$NewChurchID',
                                     '$Name'       ,
                                     '$Password'   ,
                                     '$IsAdmin'    ,
                                     '$Status'     ,
                                     '$ConvCode'
                                   )")
             or die ("Unable to process insert: " . mysql_error());
          }
          else
          {
             $ErrorMsg = "Userid already exists";
          }
      }

      if ($ErrorMsg == "")
      {
      ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
         <body style="background-color: rgb(217, 217, 255);">
         <?php
              if ($mode == 'update')
              {
                ?>
                  <h1 align=center>
                     User <br>"<?php  print $Name; ?>"<br>Updated!
                  </h1>
                <?php
              }
              else
              {
                ?>
                  <h1 align=center>
                     User<br>"<?php  print $Name; ?>"<br>Added!
                  </h1>
                <?php
              }

         ?>
            <center><a href="Users.php">Return to User List</a></center>
         </body>
      </html>

      <?php
      }
   }
}

if ((!isset($_POST['add']) and !isset($_POST['update'])) or $ErrorMsg != "")
{
   ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

   <head>
   <meta http-equiv="Content-Language" content="en-us">
   <?php
      if ($mode == 'update')
      {
      ?>
         <title>Update User Record</title>
      <?php
      }
      else if ($mode == 'add')
      {
      ?>
         <title>Add a new User</title>
      <?php
      }
      else
      {
      ?>
         <title>User</title>
      <?php
      }
   ?>
   </head>

   <body style="background-color: rgb(217, 217, 255);">
   <?php
      if ($mode == 'update')
      {
      ?>
         <h1 align="center">Update User Record</h1>
      <?php
      }
      else if ($mode == 'add')
      {
      ?>
         <h1 align="center">Add a new User</h1>
      <?php
      }
      else
      {
      ?>
         <h1 align="center">User</h1>
      <?php
      }

      if ($ErrorMsg != "")
      {
         print "<center><font color=\"#FF0000\"><b>" . $ErrorMsg . "</b></font></center><br>";
      }
   ?>

      <form method="post" action=AdminUser.php>
         <table border="1" width="100%" id="table1">
            <tr>
               <td colspan="4" bgcolor="#000000">
               <p align="center"><font color="#FFFF00">
               <span style="background-color: #000000">User Information</span></font></td>
            </tr>
            <tr>
               <td width="12%">Userid</td>
               <td width="85%" colspan="3">
               <?php
               if ($mode == 'view' or $mode == 'update')
               {
                  print $NewUserid;
               }
               else
               {
                  ?>
                  <input type="text" name="Userid" size="36" <?php  print ($NewUserid != "") ? "value=\"" . $NewUserid . "\"" : ""; ?>></td>
                  <?php
               }
               ?>
            </tr>
            <tr>
               <td width="12%">Name</td>
               <td width="85%" colspan="3">
               <?php
               if ($mode == 'view')
               {
                  print $Name;
               }
               else
               {
                  ?>
                  <input type="text" name="Name" size="36" <?php  print ($Name != "") ? "value=\"" . $Name . "\"" : ""; ?>></td>
                  <?php
               }
               ?>
            </tr>
            <tr>
               <td width="12%">Password</td>
               <td width="85%" colspan="3">
               <?php
               if ($mode == 'view')
               {
                  print $Password;
               }
               else
               {
                  ?>
                  <input type="text" name="Password" size="36" <?php  print ($Password != "") ? "value=\"" . $Password . "\"" : ""; ?>></td>
                  <?php
               }
               ?>
            </tr>
            <tr>
               <td width="12%">Church</td>
               <td width="85%" colspan="3">
               <?php
               if ($mode == 'view')
               {
                  $chResult = mysql_query("select ChurchName
                                          from   $ChurchesTable
                                          where  ChurchID = ".$row['ChurchID']
                                       )
                              or die ("Not found:" . mysql_error());
                  $chRow = mysql_fetch_assoc($chResult);
                  $ChurchName = $chRow['ChurchName'];
                  print $ChurchName;
               }
               else
               {
                  ?>
                  <select size="1" name="ChurchID">
                  <option value="0">Select Church</option>
                  <?php
                     $results = mysql_query("select   ChurchName,
                                                      ChurchID
                                             from     $ChurchesTable
                                             order by ChurchName
                                          ")
                              or die ("Not found:" . mysql_error());
                     while ($row = mysql_fetch_assoc($results))
                     {
                        $selected = ($NewChurchID == $row['ChurchID']) ? "selected" : "";
                        print "<option value=\"".$row['ChurchID']."\" ".$selected.">".$row['ChurchName']."</option>";
                     }
                     ?>
                  </select>
                  <?php
               }
               ?>
               </td>
            </tr>
            <tr>
               <td width="12%">Status</td>
               <?php
               if ($mode == 'view')
               {
                  if ($Status == 'O')
                  {
                     $Status = 'Open';
                  }
                  else if ($Status == 'C')
                  {
                     $Status = 'Closed';
                  }
                  else if ($Status == 'L')
                  {
                     $Status = 'Locked';
                  }
                  else if ($Status == 'R')
                  {
                     $Status = 'Report Only';
                  }
                  else
                  {
                     $Status = 'Unknown';
                  }
                  print "<td width=8%>$Status</td>";
                  print "<td width=78%>&nbsp;</td>";
               }
               else
               {
                  ?>
                  <td width="8%" colspan=3>
                     <select size="1" name="Status">
                        <option value="0" <?php  print $Status == ''  ? "selected" : "";?>>--- Choose ---</option>
                        <option value="C" <?php  print $Status == 'C' ? "selected" : "";?>>Closed</option>
                        <option value="L" <?php  print $Status == 'L' ? "selected" : "";?>>Locked</option>
                        <option value="O" <?php  print $Status == 'O' ? "selected" : "";?>>Open</option>
                        <option value="R" <?php  print $Status == 'R' ? "selected" : "";?>>Report Only</option>
                     </select>
                  </td>
                  <?php
               }
               ?>
            </tr>
            <tr>
               <td width="12%">Administrator</td>
               <?php
               if ($mode == 'view')
               {
                  print "<td width=8%>" . (($IsAdmin == 'Y') ? "Yes" : "No") . "</td>";
                  print "<td width=78%>&nbsp;</td>";
               }
               else
               {
                  ?>
                  <td width="8%"> <input type="radio" name="Admin" value="Y" <?php  print ($IsAdmin == 'Y') ? "checked" : ""; ?>>Yes</td>
                  <td width="8%"> <input type="radio" name="Admin" value="N" <?php  print ($IsAdmin == 'N') ? "checked" : ""; ?>>No</td>
                  <td width="70%">&nbsp;</td>
                  <?php
               }
               ?>
            </tr>
            </table>
         <p align="center">
            <?php
               if ($mode == 'update')
               {?>
                  <input type="submit" value="Update" name="update">
                  <input type="hidden" value="<?php  print $NewUserid; ?>" name=Userid>
                  <input type="hidden" value="update" name=action>
               <?php
               }
               else if ($mode == 'add')
               {?>
                  <input type="submit" value="Add" name="add">
                  <input type="hidden" value="add" name=action>
               <?php
               }
               else if ($mode == 'view')
               {?>
                  <input type="hidden" value="update" name=action>
               <?php
               }
            ?>
            <br>
         </p>
      </form>
      <?php footer("Return to User List","Users.php")?>
   </body>

   </html>
<?php
}
