<?php
include 'include/RegFunctions.php';
if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

$ConvName   = "";
$ConvCode  = "";

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update')
{
   $mode = 'update';
   $ConvCode = $_REQUEST['ConvCode'];
}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')
{
   $mode = 'view';
   $ConvCode = $_REQUEST['ConvCode'];
}
else //if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add')
{
   $mode = 'add';
}

$ErrorMsg = "";

if ($mode == 'update' || $mode == 'view')
{
   $result = mysql_query("select  ConvName,
                                  ConvCode
                          from    $ConventionsTable
                          where   ConvCode = '$ConvCode'")
             or die ("Unable to get Convention information: ".mysql_error());
   $row = mysql_fetch_assoc($result);

   $ConvName = $row['ConvName'];
   $ConvCode = $row['ConvCode'];
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

   $ConvName = isset($_POST['ConvName']) ? $_POST['ConvName'] : "";
   $ConvCode = isset($_POST['ConvCode']) ? strtoupper($_POST['ConvCode']) : "";


   if ($ConvName == "")
   {
      $ErrorMsg = "Please enter the required field: Convention Name";
   }
   else if ($ConvCode == "")
   {
      $ErrorMsg = "Please enter the required field: Convention Code";
   }
   else if (!preg_match("/^[A-Z]{3}$/",$ConvCode))
   {
      $ErrorMsg = "Convention code must be exactly 3 character long and completely alphabetic";
   }

   if ($ErrorMsg == "")
   {
      ereg_replace("'","''",$ConvName);

      if ($mode == 'update')
      {
         $sql = "update $ConventionsTable
                 set    ConvName = '$ConvName',
                 where  ConvCode   = '$ConvCode'
                ";
      }
      else
      {
         $sql = "insert into $ConventionsTable
                        (ConvName,
                         ConvCode
                        )
                 values ('$ConvName',
                         '$ConvCode'
                         )";
      }

      $results = mysql_query($sql) or die ("Unable to process update: " . mysql_error());
   ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
         <body style="background-color: rgb(217, 217, 255);">
         <?php
              if ($mode == 'update')
              {
                ?>
                  <h1 align=center>
                     Convention<br>"<?php  print $ConvName; ?>"<br>Updated!
                  </h1>
                <?php
              }
              else
              {
                ?>
                  <h1 align=center>
                     Convention<br>"<?php  print $ConvName; ?>"<br>Added!
                  </h1>
                <?php
              }

         ?>
            <center><a href="Conventions.php">Return to Convention List</a></center>
         </body>
      </html>

      <?php
   }
}

if ((!isset($_POST['add']) and !isset($_POST['update'])) or $ErrorMsg != "")
{
   ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

   <head>
   <?php
      if ($mode == 'update')
      {
      ?>
         <title>Update Convention Record</title>
      <?php
      }
      else if ($mode == 'add')
      {
      ?>
         <title>Add a new Convention</title>
      <?php
      }
      else
      {
      ?>
         <title>Conventions</title>
      <?php
      }
   ?>
   </head>

   <body style="background-color: rgb(217, 217, 255);">
   <?php
      if ($mode == 'update')
      {
      ?>
         <h1 align="center">Update Convention Record</h1>
      <?php
      }
      else if ($mode == 'add')
      {
      ?>
         <h1 align="center">Add a new Convention</h1>
      <?php
      }
      else
      {
      ?>
         <h1 align="center">Convention</h1>
      <?php
      }

      if ($ErrorMsg != "")
      {
         print "<center><font color=\"FF0000\"><b>" . $ErrorMsg . "</b></font></center><br>";
      }
   ?>

   <form method="post" action=AdminConventions.php>
      <table border="1" width="100%">
         <tr>
            <td colspan="4" bgcolor="#000000">
            <p align="center"><font color="#FFFF00">
            <span style="background-color: #000000">Convention Information</span></font></td>
         </tr>
         <tr>
            <td width="12%">Convention Name</td>
            <td width="85%" colspan="3">
            <?php
            if ($mode == "view")
            {
               print $ConvName;
            }
            else
            {
            ?>
               <input type="text" name="ConvName" size="36" <?php  print ($ConvName != "") ? "value=\"" . $ConvName . "\"" : ""; ?>></td>
            <?php
            }
            ?>
         </tr>
         <tr>
            <td width="12%">Convention Code</td>
            <td width="85%" colspan="3">
            <?php
            if ($mode == "view")
            {
               print $ConvCode;
            }
            else
            {
            ?>
               <input type="text" name="ConvCode" size="36" <?php  print ($ConvCode != "") ? "value=\"" . $ConvCode . "\"" : ""; ?>></td>
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
             <?php
            }
            else if ($mode == 'add')
            {?>
               <input type="submit" value="Add" name="add">
             <?php
            }
         ?>
         <br>

      </p>
   </form>
   <?php footer("Return to Convention List","Conventions.php")?>
   </body>

   </html>
<?php
}