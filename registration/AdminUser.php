<?php
require 'include/RegFunctions.php';

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

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
   $mode        = 'add';
   $Name        = "";
   $Password    = "";
   $IsAdmin     = "";
   $Status      = "";
   $NewUserid   = "";
   $NewChurchID = "";
   $NewEmail    = "";
}

//if (isset($_POST[$controlID]) and $_POST[$controlID] == 'ON')

$ErrorMsg = "";

if ($mode == 'update' || $mode == 'view')
{
   $result = mysql_query("select *
                          from   $UsersTable
                          where  Userid='$NewUserid'
                         ")
             or die ("Unable to get User information: ".mysql_error());
   $row = mysql_fetch_assoc($result);

   $NewUserID    = isset($row['Userid'])            ? $row['Userid']            : "";
   $NewChurchID  = isset($row['ChurchID'])          ? $row['ChurchID']          : "";
   $NewEmail     = isset($row['email'])             ? $row['email']             : "";
   $Name         = isset($row['Name'])              ? $row['Name']              : "";
   $IsAdmin      = isset($row['Admin'])             ? $row['Admin']             : "";
   $Status       = isset($row['Status'])            ? $row['Status']            : "";
   $loginCount   = isset($row['loginCount'])        ? $row['loginCount']        : "";
   $lastLogin    = isset($row['lastLogin'])         ? $row['lastLogin']         : "";
   $loginFalures = isset($row['failedLoginCount'])  ? $row['failedLoginCount']  : "";

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
   $NewEmail     = isset($_POST['Email'])     ? $_POST['Email']     : "";
   $Name         = isset($_POST['Name'])      ? $_POST['Name']      : "";
   $Password     = isset($_POST['Password'])  ? $_POST['Password']  : "";
   $IsAdmin      = isset($_POST['Admin'])     ? $_POST['Admin']     : "";
   $Status       = isset($_POST['Status'])    ? $_POST['Status']    : "";

   if ($NewUserid == "")
      $ErrorMsg = "Please enter the required field: Userid";

   else if ($Name == "")
      $ErrorMsg = "Please enter the required field: Name";

   elseif ($Password == "" and ((isset($_POST['updPwd']) and $_POST['updPwd'] == 'on') or $mode=='add'))
         $ErrorMsg = "Please enter the required field: Password";

   elseif ($Password != "" and !verifyPasswordFormat($Password))
      $ErrorMsg="Sorry, chosen password is too easily hacked.<br>Must be:<br>7 or more characters long<br>Mixed Case<br>Include at least 1 number<br>Include at least 1 special Character";

   elseif ($NewChurchID == "" or $NewChurchID == '0')
      $ErrorMsg = "Please enter the required field: Church";

   elseif ($IsAdmin == "")
      $ErrorMsg = "Please Indicate if person is an  Administrator or not";

   elseif ($NewEmail == "" and ((isset($_POST['updEmail']) and $_POST['updEmail'] == 'on') or $mode=='add'))
         $ErrorMsg = "Please Enter Email address";

   elseif ($NewEmail != "" and !filter_var($NewEmail, FILTER_VALIDATE_EMAIL))
      $ErrorMsg="Sorry, That does not appear to be a valid email address";

   elseif ($Status == "")
      $ErrorMsg = "Please Indicate the status of the account";


   if ($ErrorMsg == "")
   {
      ereg_replace("'","''",$Name);
      ereg_replace("'","''",$Password);

      $newPassword = password_hash($Password,PASSWORD_DEFAULT);
      if ($mode == 'update')
      {
         if (isset($_POST['updPwd']) and $_POST['updPwd'] == 'on')
            $pwdSet = "Password  = '$newPassword',\n";
         else
            $pwdSet = '';

         if (isset($_POST['updEmail']) and $_POST['updEmail'] == 'on')
            $emailSet = "Email     = '$NewEmail',\n";
         else
            $emailSet = '';

         $results = mysql_query("update $UsersTable
                                 set    ChurchID  = '$NewChurchID',
                                        $pwdSet
                                        $emailSet
                                        Name      = '$Name',
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
                                     Email,
                                     Name     ,
                                     Password ,
                                     Admin    ,
                                     Status)
                             values ('$NewUserid'  ,
                                     '$NewChurchID',
                                     '$NewEmail',
                                     '$Name'       ,
                                     '$newPassword'   ,
                                     '$IsAdmin'    ,
                                     '$Status'
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
                  <h1 align="center">
                     User <br>"<?php  print $Name; ?>"<br>Updated!
                  </h1>
                <?php
              }
              else
              {
                ?>
                  <h1 align="center">
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

      <?php
         $requestString='';
         if (isset($_REQUEST['action']))
            $requestString="?action=".$_REQUEST['action'];
         if (isset($_REQUEST['Userid']) and isset($_REQUEST['Userid']))
            $requestString.="&Userid=".$_REQUEST['Userid'];
         elseif (isset($_REQUEST['Userid']))
            $requestString.="?Userid=".$_REQUEST['Userid'];
         ?>
      <form method="post" action=AdminUser.php<?php print $requestString?>>
         <table border="1" width="100%" id="table1">
            <tr>
               <td colspan="6" bgcolor="#000000">
               <p align="center"><font color="#FFFF00">
               <span style="background-color: #000000">User Information</span></font></td>
            </tr>
            <tr>
               <td width="12%">Userid</td>
               <td width="85%" colspan="5">
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
               <td width="85%" colspan="5">
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
            <?php
            if ($mode != 'view')
            {?>
            <tr>
               <td width="12%">Password</td>
               <td width="85%" colspan="5">
               <input type="text" name="Password" size="36">
               <input type="checkbox" name="updPwd" <?php if (isset($_POST['updPwd']) and $_POST['updPwd'] == 'on') print 'checked'?>> Update Password</td>
            </tr>
            <?php
            }
            ?>
            <tr>
               <td width="12%">Email</td>
               <td width="85%" colspan="5">
               <?php
               if ($mode == 'view')
               {
                  print $NewEmail;
               }
               else
               {
                  ?>
                  <input type="text" name="Email" size="36" <?php  print ($NewEmail != "") ? "value=\"" . $NewEmail . "\"" : ""; ?>>
                  <input type="checkbox" name="updEmail" <?php if (isset($_POST['updEmail']) and $_POST['updEmail'] == 'on') print 'checked'?>> Update Email</td>
                  <?php
               }
               ?>
               </td>
            </tr>
            <tr>
               <td width="12%">Church</td>
               <td width="85%" colspan="5">
               <?php
               if ($mode == 'view')
               {
                  $ChurchName = ChurchName($row['ChurchID']);
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
                  print "<td width=10%>$Status</td>";
                  print "<td width=10%>Login Count</td>";
                  print "<td width=5%>$loginCount</td>";
                  print "<td width=10%>Last Login</td>";
                  print "<td width=53%>$lastLogin</td>";
               }
               else
               {
                  ?>
                  <td width="8%" colspan=5>
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
                  print "<td>" . (($IsAdmin == 'Y') ? "Yes" : "No") . "</td>";
                  print "<td>Bad Logins</td>";
                  print "<td>$loginFalures</td>";
                  print "<td>&nbsp;</td>";
                  print "<td>&nbsp;</td>";
               }
               else
               {
                  ?>
                  <td width="8%"> <input type="radio" name="Admin" value="Y" <?php  print ($IsAdmin == 'Y') ? "checked" : ""; ?>>Yes</td>
                  <td width="8%"> <input type="radio" name="Admin" value="N" <?php  print ($IsAdmin == 'N') ? "checked" : ""; ?>>No</td>
                  <td width="70%" colspan="4">&nbsp;</td>
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
