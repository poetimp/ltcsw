<?php
//----------------------------------------------------------------------------
// This software is licensed under the MIT license. Use as you wish but give
// and take credit where due.
//
// Author: Paul Lemmons
//----------------------------------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<?php
include 'include/RegFunctions.php';

$ChurchName  = "";
$ChurchAddr  = "";
$ChurchCity  = "";
$ChurchState = "";
$ChurchZip   = "";
$ChurchPhone = "";
$ChurchEmail = "";

$CoordName  = "";
$CoordAddr  = "";
$CoordCity  = "";
$CoordState = "";
$CoordZip   = "";
$CoordPhone = "";
$CoordEmail = "";

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update')
{
   $mode = 'update';
   $ChurchID = $_REQUEST['ChurchID'];
}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')
{
   $mode = 'view';
   $ChurchID = $_REQUEST['ChurchID'];
}
else //if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add')
{
   $mode = 'add';
}

$ErrorMsg = "";

if ($mode == 'update' || $mode == 'view')
{
   $result = $db->query("select *
                          from   $ChurchesTable
                          where  ChurchID ='$ChurchID'
                         ")
             or die ("Unable to get church information: ".sqlError());
   $row = $result->fetch(PDO::FETCH_ASSOC);

   $ChurchName  = isset($row['ChurchName'])  ? $row['ChurchName']  : "";
   $ChurchAddr  = isset($row['ChurchAddr'])  ? $row['ChurchAddr']  : "";
   $ChurchCity  = isset($row['ChurchCity'])  ? $row['ChurchCity']  : "";
   $ChurchState = isset($row['ChurchState']) ? $row['ChurchState'] : "";
   $ChurchZip   = isset($row['ChurchZip'])   ? $row['ChurchZip']   : "";
   $ChurchPhone = isset($row['ChurchPhone']) ? $row['ChurchPhone'] : "";
   $ChurchEmail = isset($row['ChurchEmail']) ? $row['ChurchEmail'] : "";

   $CoordName  = isset($row['CoordName'])  ? $row['CoordName']  : "";
   $CoordAddr  = isset($row['CoordAddr'])  ? $row['CoordAddr']  : "";
   $CoordCity  = isset($row['CoordCity'])  ? $row['CoordCity']  : "";
   $CoordState = isset($row['CoordState']) ? $row['CoordState'] : "";
   $CoordZip   = isset($row['CoordZip'])   ? $row['CoordZip']   : "";
   $CoordPhone = isset($row['CoordPhone']) ? $row['CoordPhone'] : "";
   $CoordEmail = isset($row['CoordEmail']) ? $row['CoordEmail'] : "";
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

   $ChurchID    = isset($_POST['ChurchID'])    ? $_POST['ChurchID']    : "";
   $ChurchName  = isset($_POST['ChurchName'])  ? $_POST['ChurchName']  : "";
   $ChurchAddr  = isset($_POST['ChurchAddr'])  ? $_POST['ChurchAddr']  : "";
   $ChurchCity  = isset($_POST['ChurchCity'])  ? $_POST['ChurchCity']  : "";
   $ChurchState = isset($_POST['ChurchState']) ? $_POST['ChurchState'] : "";
   $ChurchZip   = isset($_POST['ChurchZip'])   ? $_POST['ChurchZip']   : "";
   $ChurchPhone = isset($_POST['ChurchPhone']) ? $_POST['ChurchPhone'] : "";
   $ChurchEmail = isset($_POST['ChurchEmail']) ? $_POST['ChurchEmail'] : "";

   $CoordName  = isset($_POST['CoordName'])  ? $_POST['CoordName']  : "";
   $CoordAddr  = isset($_POST['CoordAddr'])  ? $_POST['CoordAddr']  : "";
   $CoordCity  = isset($_POST['CoordCity'])  ? $_POST['CoordCity']  : "";
   $CoordState = isset($_POST['CoordState']) ? $_POST['CoordState'] : "";
   $CoordZip   = isset($_POST['CoordZip'])   ? $_POST['CoordZip']   : "";
   $CoordPhone = isset($_POST['CoordPhone']) ? $_POST['CoordPhone'] : "";
   $CoordEmail = isset($_POST['CoordEmail']) ? $_POST['CoordEmail'] : "";

   if ($ChurchName == "")
   {
      $ErrorMsg = "Please enter the required field: Church Name";
   }
   else if ($ChurchAddr == "")
   {
      $ErrorMsg = "Please enter the required field: Church Address";
   }
   else if ($ChurchCity == "")
   {
      $ErrorMsg = "Please enter the required field: Church City";
   }
   else if ($ChurchState == "")
   {
      $ErrorMsg = "Please enter the required field: Church State";
   }
   else if ($ChurchZip == "")
   {
      $ErrorMsg = "Please enter the required field: Church Zipcode";
   }
   else if ($ChurchPhone == "")
   {
      $ErrorMsg = "Please enter the required field: Church Phone Number";
   }
   else if ($CoordName == "")
   {
      $ErrorMsg = "Please enter the required field: Coordinator Name";
   }
   else if ($CoordAddr == "")
   {
      $ErrorMsg = "Please enter the required field: Coordinator Address";
   }
   else if ($CoordCity == "")
   {
      $ErrorMsg = "Please enter the required field: Coordinator City";
   }
   else if ($CoordState == "")
   {
      $ErrorMsg = "Please enter the required field: Coordinator State";
   }
   else if ($CoordZip == "")
   {
      $ErrorMsg = "Please enter the required field: Coordinator Zipcode";
   }
   else if ($CoordPhone == "")
   {
      $ErrorMsg = "Please enter the required field: Coordinator Phone Number";
   }
   else if ($CoordEmail == "")
   {
      $ErrorMsg = "Please enter the required field: Coordinator Email Address";
   }
   else if (!preg_match("/^[0-9]{5}$/",$ChurchZip) and !preg_match("/^[0-9]{5}-[0-9]{4}$/",$ChurchZip))
   {
      $ErrorMsg = "Invalid Zip Church Code specified. Must be in the format: ##### or #####-####";
   }
   else if (!preg_match("/^\([0-9]{3}\) [0-9]{3}-[0-9]{4}$/",$ChurchPhone))
   {
      $ErrorMsg = "Invalid Church Phone number specified. Must be in the format: (###) ###-####";
   }
   else if (!preg_match("/^[0-9]{5}$/",$CoordZip) and !preg_match("/^[0-9]{5}-[0-9]{4}$/",$CoordZip))
   {
      $ErrorMsg = "Invalid Zip Coordinator Code specified. Must be in the format: ##### or #####-####";
   }
   else if (!preg_match("/^\([0-9]{3}\) [0-9]{3}-[0-9]{4}$/",$CoordPhone))
   {
      $ErrorMsg = "Invalid Coordinator Phone number specified. Must be in the format: (###) ###-####";
   }

   if ($ErrorMsg == "")
   {

      if ($mode == 'update')
      {
         $sql = "update $ChurchesTable
                    set ChurchName  = ".$db->quote($ChurchName)."  ,
                        ChurchAddr  = ".$db->quote($ChurchAddr)."  ,
                        ChurchCity  = ".$db->quote($ChurchCity)."  ,
                        ChurchState = ".$db->quote($ChurchState)." ,
                        ChurchZip   = ".$db->quote($ChurchZip)."   ,
                        ChurchPhone = ".$db->quote($ChurchPhone)." ,
                        ChurchEmail = ".$db->quote($ChurchEmail)." ,
                        CoordName   = ".$db->quote($CoordName)."   ,
                        CoordAddr   = ".$db->quote($CoordAddr)."   ,
                        CoordCity   = ".$db->quote($CoordCity)."   ,
                        CoordState  = ".$db->quote($CoordState)."  ,
                        CoordZip    = ".$db->quote($CoordZip)."    ,
                        CoordPhone  = ".$db->quote($CoordPhone)."  ,
                        CoordEmail  = ".$db->quote($CoordEmail)."
                  where ChurchId    = $ChurchID";
      }
      else
      {
         $sql = "insert into $ChurchesTable
                        (ChurchName  ,
                         ChurchAddr  ,
                         ChurchCity  ,
                         ChurchState ,
                         ChurchZip   ,
                         ChurchPhone ,
                         ChurchEmail ,
                         CoordName   ,
                         CoordAddr   ,
                         CoordCity   ,
                         CoordState  ,
                         CoordZip    ,
                         CoordPhone  ,
                         CoordEmail)
                 values (".$db->quote($ChurchName)."  ,
                         ".$db->quote($ChurchAddr)."  ,
                         ".$db->quote($ChurchCity)."  ,
                         ".$db->quote($ChurchState)." ,
                         ".$db->quote($ChurchZip)."   ,
                         ".$db->quote($ChurchPhone)." ,
                         ".$db->quote($ChurchEmail)." ,
                         ".$db->quote($CoordName)."   ,
                         ".$db->quote($CoordAddr)."   ,
                         ".$db->quote($CoordCity)."   ,
                         ".$db->quote($CoordState)."  ,
                         ".$db->quote($CoordZip)."    ,
                         ".$db->quote($CoordPhone)."  ,
                         ".$db->quote($CoordEmail).
                       ")";
      }

      $results = $db->query($sql) or die ("Unable to process update: " . sqlError());
   ?>
         <body>
         <?php
              if ($mode == 'update')
              {
                ?>
                  <h1 align="center">
                     Church <br />"<?php  print $ChurchName; ?>"<br />Updated!
                  </h1>
                <?php
              }
              else
              {
                ?>
                  <h1 align="center">
                     Church<br />"<?php  print $ChurchName; ?>"<br />Added!
                  </h1>
                <?php
              }

         ?>
            <center><a href="Churches.php">Return to Church List</a></center>
         </body>
      </html>

      <?php
   }
}

if ((!isset($_POST['add']) and !isset($_POST['update'])) or $ErrorMsg != "")
{
   ?>
   <head>
      <meta http-equiv="Content-Language" content="en-us" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel=stylesheet href="include/registration.css" type="text/css" />

   <?php
      if ($mode == 'update')
      {
      ?>
         <title>Update Church Record</title>
      <?php
      }
      else if ($mode == 'add')
      {
      ?>
         <title>Add a new Church</title>
      <?php
      }
      else
      {
      ?>
         <title>Church</title>
      <?php
      }
   ?>
   </head>

   <body>
   <?php
      if ($mode == 'update')
      {
      ?>
         <h1 align="center">Update Church Record</h1>
      <?php
      }
      else if ($mode == 'add')
      {
      ?>
         <h1 align="center">Add a new Church</h1>
      <?php
      }
      else
      {
      ?>
         <h1 align="center">Church</h1>
      <?php
      }

      if ($ErrorMsg != "")
      {
         print "<center><font color=\"FF0000\"><b>" . $ErrorMsg . "</b></font></center><br>";
      }
   ?>

   <form method="post" action="AdminChurch.php">
      <table class='registrationTable' id="table1">
         <tr>
            <th colspan="4" align="center">Church Information</th>
         </tr>
         <tr>
            <td width="12%">Name</td>
            <td width="85%" colspan="3">
            <?php
            if ($mode == "view")
            {
               print $ChurchName;
            }
            else
            {
            ?>
               <input type="text" name="ChurchName" size="36" <?php  print ($ChurchName != "") ? "value=\"" . $ChurchName . "\"" : ""; ?>="<?php  print ($ChurchName != "") ? "value=\"" . $ChurchName . "\"" : ""; ?>" /></td>
            <?php
            }
            ?>
         </tr>
         <tr>
            <td width="12%">Address</td>
            <td width="85%" colspan="3">
            <?php
            if ($mode == "view")
            {
               print $ChurchAddr;
            }
            else
            {
            ?>
               <input type="text" name="ChurchAddr" size="36" <?php  print ($ChurchAddr != "") ? "value=\"" . $ChurchAddr . "\"" : ""; ?>="<?php  print ($ChurchAddr != "") ? "value=\"" . $ChurchAddr . "\"" : ""; ?>" /></td>
            <?php
            }
            ?>
         </tr>
         <tr>
            <td width="12%">City</td>
            <td width="36%">
            <?php
            if ($mode == "view")
            {
               print $ChurchCity;
            }
            else
            {
            ?>
               <input type="text" name="ChurchCity" size="36" <?php  print ($ChurchCity != "") ? "value=\"" . $ChurchCity . "\"" : ""; ?>="<?php  print ($ChurchCity != "") ? "value=\"" . $ChurchCity . "\"" : ""; ?>" />
            <?php
            }
            ?>
            </td>
            <td width="19%">State</td>
            <td width="30%">
            <?php
            if ($mode == "view")
            {
               print $ChurchState;
            }
            else
            {
               if (!isset($ChurchState) or $ChurchState == "")
               {
                  $ChurchState = "AZ";
               }
            ?>
               <select size="1" name="ChurchState">
                  <option <?php  print ($ChurchState == "AK") ? "selected" : "" ?>="<?php  print ($ChurchState == "AK") ? "selected" : "" ?>">AK</option>
                  <option <?php  print ($ChurchState == "AL") ? "selected" : "" ?>="<?php  print ($ChurchState == "AL") ? "selected" : "" ?>">AL</option>
                  <option <?php  print ($ChurchState == "AK") ? "selected" : "" ?>="<?php  print ($ChurchState == "AK") ? "selected" : "" ?>">AR</option>
                  <option <?php  print ($ChurchState == "AS") ? "selected" : "" ?>="<?php  print ($ChurchState == "AS") ? "selected" : "" ?>">AS</option>
                  <option <?php  print ($ChurchState == "AZ") ? "selected" : "" ?>="<?php  print ($ChurchState == "AZ") ? "selected" : "" ?>">AZ</option>
                  <option <?php  print ($ChurchState == "CA") ? "selected" : "" ?>="<?php  print ($ChurchState == "CA") ? "selected" : "" ?>">CA</option>
                  <option <?php  print ($ChurchState == "CO") ? "selected" : "" ?>="<?php  print ($ChurchState == "CO") ? "selected" : "" ?>">CO</option>
                  <option <?php  print ($ChurchState == "CT") ? "selected" : "" ?>="<?php  print ($ChurchState == "CT") ? "selected" : "" ?>">CT</option>
                  <option <?php  print ($ChurchState == "DC") ? "selected" : "" ?>="<?php  print ($ChurchState == "DC") ? "selected" : "" ?>">DC</option>
                  <option <?php  print ($ChurchState == "DE") ? "selected" : "" ?>="<?php  print ($ChurchState == "DE") ? "selected" : "" ?>">DE</option>
                  <option <?php  print ($ChurchState == "FL") ? "selected" : "" ?>="<?php  print ($ChurchState == "FL") ? "selected" : "" ?>">FL</option>
                  <option <?php  print ($ChurchState == "FM") ? "selected" : "" ?>="<?php  print ($ChurchState == "FM") ? "selected" : "" ?>">FM</option>
                  <option <?php  print ($ChurchState == "GA") ? "selected" : "" ?>="<?php  print ($ChurchState == "GA") ? "selected" : "" ?>">GA</option>
                  <option <?php  print ($ChurchState == "GU") ? "selected" : "" ?>="<?php  print ($ChurchState == "GU") ? "selected" : "" ?>">GU</option>
                  <option <?php  print ($ChurchState == "HI") ? "selected" : "" ?>="<?php  print ($ChurchState == "HI") ? "selected" : "" ?>">HI</option>
                  <option <?php  print ($ChurchState == "IA") ? "selected" : "" ?>="<?php  print ($ChurchState == "IA") ? "selected" : "" ?>">IA</option>
                  <option <?php  print ($ChurchState == "ID") ? "selected" : "" ?>="<?php  print ($ChurchState == "ID") ? "selected" : "" ?>">ID</option>
                  <option <?php  print ($ChurchState == "IL") ? "selected" : "" ?>="<?php  print ($ChurchState == "IL") ? "selected" : "" ?>">IL</option>
                  <option <?php  print ($ChurchState == "IN") ? "selected" : "" ?>="<?php  print ($ChurchState == "IN") ? "selected" : "" ?>">IN</option>
                  <option <?php  print ($ChurchState == "KS") ? "selected" : "" ?>="<?php  print ($ChurchState == "KS") ? "selected" : "" ?>">KS</option>
                  <option <?php  print ($ChurchState == "KY") ? "selected" : "" ?>="<?php  print ($ChurchState == "KY") ? "selected" : "" ?>">KY</option>
                  <option <?php  print ($ChurchState == "LS") ? "selected" : "" ?>="<?php  print ($ChurchState == "LS") ? "selected" : "" ?>">LA</option>
                  <option <?php  print ($ChurchState == "MA") ? "selected" : "" ?>="<?php  print ($ChurchState == "MA") ? "selected" : "" ?>">MA</option>
                  <option <?php  print ($ChurchState == "MD") ? "selected" : "" ?>="<?php  print ($ChurchState == "MD") ? "selected" : "" ?>">MD</option>
                  <option <?php  print ($ChurchState == "ME") ? "selected" : "" ?>="<?php  print ($ChurchState == "ME") ? "selected" : "" ?>">ME</option>
                  <option <?php  print ($ChurchState == "MH") ? "selected" : "" ?>="<?php  print ($ChurchState == "MH") ? "selected" : "" ?>">MH</option>
                  <option <?php  print ($ChurchState == "MI") ? "selected" : "" ?>="<?php  print ($ChurchState == "MI") ? "selected" : "" ?>">MI</option>
                  <option <?php  print ($ChurchState == "MN") ? "selected" : "" ?>="<?php  print ($ChurchState == "MN") ? "selected" : "" ?>">MN</option>
                  <option <?php  print ($ChurchState == "MO") ? "selected" : "" ?>="<?php  print ($ChurchState == "MO") ? "selected" : "" ?>">MO</option>
                  <option <?php  print ($ChurchState == "MP") ? "selected" : "" ?>="<?php  print ($ChurchState == "MP") ? "selected" : "" ?>">MP</option>
                  <option <?php  print ($ChurchState == "MS") ? "selected" : "" ?>="<?php  print ($ChurchState == "MS") ? "selected" : "" ?>">MS</option>
                  <option <?php  print ($ChurchState == "MT") ? "selected" : "" ?>="<?php  print ($ChurchState == "MT") ? "selected" : "" ?>">MT</option>
                  <option <?php  print ($ChurchState == "NC") ? "selected" : "" ?>="<?php  print ($ChurchState == "NC") ? "selected" : "" ?>">NC</option>
                  <option <?php  print ($ChurchState == "ND") ? "selected" : "" ?>="<?php  print ($ChurchState == "ND") ? "selected" : "" ?>">ND</option>
                  <option <?php  print ($ChurchState == "NE") ? "selected" : "" ?>="<?php  print ($ChurchState == "NE") ? "selected" : "" ?>">NE</option>
                  <option <?php  print ($ChurchState == "NH") ? "selected" : "" ?>="<?php  print ($ChurchState == "NH") ? "selected" : "" ?>">NH</option>
                  <option <?php  print ($ChurchState == "NJ") ? "selected" : "" ?>="<?php  print ($ChurchState == "NJ") ? "selected" : "" ?>">NJ</option>
                  <option <?php  print ($ChurchState == "NM") ? "selected" : "" ?>="<?php  print ($ChurchState == "NM") ? "selected" : "" ?>">NM</option>
                  <option <?php  print ($ChurchState == "NV") ? "selected" : "" ?>="<?php  print ($ChurchState == "NV") ? "selected" : "" ?>">NV</option>
                  <option <?php  print ($ChurchState == "NY") ? "selected" : "" ?>="<?php  print ($ChurchState == "NY") ? "selected" : "" ?>">NY</option>
                  <option <?php  print ($ChurchState == "OH") ? "selected" : "" ?>="<?php  print ($ChurchState == "OH") ? "selected" : "" ?>">OH</option>
                  <option <?php  print ($ChurchState == "OK") ? "selected" : "" ?>="<?php  print ($ChurchState == "OK") ? "selected" : "" ?>">OK</option>
                  <option <?php  print ($ChurchState == "OR") ? "selected" : "" ?>="<?php  print ($ChurchState == "OR") ? "selected" : "" ?>">OR</option>
                  <option <?php  print ($ChurchState == "PA") ? "selected" : "" ?>="<?php  print ($ChurchState == "PA") ? "selected" : "" ?>">PA</option>
                  <option <?php  print ($ChurchState == "PR") ? "selected" : "" ?>="<?php  print ($ChurchState == "PR") ? "selected" : "" ?>">PR</option>
                  <option <?php  print ($ChurchState == "PW") ? "selected" : "" ?>="<?php  print ($ChurchState == "PW") ? "selected" : "" ?>">PW</option>
                  <option <?php  print ($ChurchState == "RI") ? "selected" : "" ?>="<?php  print ($ChurchState == "RI") ? "selected" : "" ?>">RI</option>
                  <option <?php  print ($ChurchState == "SC") ? "selected" : "" ?>="<?php  print ($ChurchState == "SC") ? "selected" : "" ?>">SC</option>
                  <option <?php  print ($ChurchState == "SD") ? "selected" : "" ?>="<?php  print ($ChurchState == "SD") ? "selected" : "" ?>">SD</option>
                  <option <?php  print ($ChurchState == "TN") ? "selected" : "" ?>="<?php  print ($ChurchState == "TN") ? "selected" : "" ?>">TN</option>
                  <option <?php  print ($ChurchState == "TX") ? "selected" : "" ?>="<?php  print ($ChurchState == "TX") ? "selected" : "" ?>">TX</option>
                  <option <?php  print ($ChurchState == "UT") ? "selected" : "" ?>="<?php  print ($ChurchState == "UT") ? "selected" : "" ?>">UT</option>
                  <option <?php  print ($ChurchState == "VA") ? "selected" : "" ?>="<?php  print ($ChurchState == "VA") ? "selected" : "" ?>">VA</option>
                  <option <?php  print ($ChurchState == "VI") ? "selected" : "" ?>="<?php  print ($ChurchState == "VI") ? "selected" : "" ?>">VI</option>
                  <option <?php  print ($ChurchState == "VT") ? "selected" : "" ?>="<?php  print ($ChurchState == "VT") ? "selected" : "" ?>">VT</option>
                  <option <?php  print ($ChurchState == "WA") ? "selected" : "" ?>="<?php  print ($ChurchState == "WA") ? "selected" : "" ?>">WA</option>
                  <option <?php  print ($ChurchState == "WI") ? "selected" : "" ?>="<?php  print ($ChurchState == "WI") ? "selected" : "" ?>">WI</option>
                  <option <?php  print ($ChurchState == "WV") ? "selected" : "" ?>="<?php  print ($ChurchState == "WV") ? "selected" : "" ?>">WV</option>
                  <option <?php  print ($ChurchState == "WY") ? "selected" : "" ?>="<?php  print ($ChurchState == "WY") ? "selected" : "" ?>">WY</option>
               </select>
            <?php
            }
            ?>
            </td>
         </tr>
         <tr>
            <td width="12%">&nbsp;</td>
            <td width="36%">&nbsp;</td>
            <td width="19%">Zip</td>
            <td width="30%">
            <?php
            if ($mode == "view")
            {
               print $ChurchZip;
            }
            else
            {
            ?>
               <input type="text" name="ChurchZip" size="20" <?php  print ($ChurchZip != "") ? "value=\"" . $ChurchZip . "\"" : ""; ?>="<?php  print ($ChurchZip != "") ? "value=\"" . $ChurchZip . "\"" : ""; ?>" />
            <?php
            }
            ?>
            </td>
         </tr>
         <tr>
            <td width="12%">Email</td>
            <td width="85%" colspan="3">
            <?php
            if ($mode == "view")
            {
               print "$ChurchEmail &nbsp;";
            }
            else
            {
            ?>
               <input type="text" name="ChurchEmail" size="36" <?php  print ($ChurchEmail != "") ? "value=\"" . $ChurchEmail . "\"" : ""; ?>="<?php  print ($ChurchEmail != "") ? "value=\"" . $ChurchEmail . "\"" : ""; ?>" />
            <?php
            }
            ?>
            </td>
         </tr>
         <tr>
            <td width="12%">Phone</td>
            <td width="85%" colspan="3">
            <?php
            if ($mode == "view")
            {
               print $ChurchPhone;
            }
            else
            {
            ?>
               <input type="text" name="ChurchPhone" size="36" <?php  print ($ChurchPhone != "") ? "value=\"" . $ChurchPhone . "\"" : ""; ?>="<?php  print ($ChurchPhone != "") ? "value=\"" . $ChurchPhone . "\"" : ""; ?>" /> (xxx) xxx-xxxx
            <?php
            }
            ?>
            </td>
         </tr>
         <tr>
            <th colspan="4" align="center">Coordinator Information</th>
         </tr>
         <tr>
            <td width="12%">Name</td>
            <td width="85%" colspan="3">
            <?php
            if ($mode == "view")
            {
               print $CoordName;
            }
            else
            {
            ?>
               <input type="text" name="CoordName" size="36" <?php  print ($CoordName != "") ? "value=\"" . $CoordName . "\"" : ""; ?>="<?php  print ($CoordName != "") ? "value=\"" . $CoordName . "\"" : ""; ?>" />
            <?php
            }
            ?>
            </td>
         </tr>
         <tr>
            <td width="12%">Address</td>
            <td width="85%" colspan="3">
            <?php
            if ($mode == "view")
            {
               print $CoordAddr;
            }
            else
            {
            ?>
               <input type="text" name="CoordAddr" size="36" <?php  print ($CoordAddr != "") ? "value=\"" . $CoordAddr . "\"" : ""; ?>="<?php  print ($CoordAddr != "") ? "value=\"" . $CoordAddr . "\"" : ""; ?>" />
            <?php
            }
            ?>
            </td>
         </tr>
         <tr>
            <td width="12%">City</td>
            <td width="36%">
            <?php
            if ($mode == "view")
            {
               print $CoordCity;
            }
            else
            {
            ?>
               <input type="text" name="CoordCity" size="36" <?php  print ($CoordCity != "") ? "value=\"" . $CoordCity . "\"" : ""; ?>="<?php  print ($CoordCity != "") ? "value=\"" . $CoordCity . "\"" : ""; ?>" />
            <?php
            }
            ?>
            </td>
            <td width="19%">State</td>
            <td width="30%">
            <?php
            if ($mode == "view")
            {
               print $CoordState;
            }
            else
            {
               if (!isset($CoordState) or $CoordState == "")
               {
                  $CoordState = "AZ";
               }
            ?>
               <select size="1" name="CoordState">
                  <option <?php  print ($CoordState == "AK") ? "selected" : "" ?>="<?php  print ($CoordState == "AK") ? "selected" : "" ?>">AK</option>
                  <option <?php  print ($CoordState == "AL") ? "selected" : "" ?>="<?php  print ($CoordState == "AL") ? "selected" : "" ?>">AL</option>
                  <option <?php  print ($CoordState == "AK") ? "selected" : "" ?>="<?php  print ($CoordState == "AK") ? "selected" : "" ?>">AR</option>
                  <option <?php  print ($CoordState == "AS") ? "selected" : "" ?>="<?php  print ($CoordState == "AS") ? "selected" : "" ?>">AS</option>
                  <option <?php  print ($CoordState == "AZ") ? "selected" : "" ?>="<?php  print ($CoordState == "AZ") ? "selected" : "" ?>">AZ</option>
                  <option <?php  print ($CoordState == "CA") ? "selected" : "" ?>="<?php  print ($CoordState == "CA") ? "selected" : "" ?>">CA</option>
                  <option <?php  print ($CoordState == "CO") ? "selected" : "" ?>="<?php  print ($CoordState == "CO") ? "selected" : "" ?>">CO</option>
                  <option <?php  print ($CoordState == "CT") ? "selected" : "" ?>="<?php  print ($CoordState == "CT") ? "selected" : "" ?>">CT</option>
                  <option <?php  print ($CoordState == "DC") ? "selected" : "" ?>="<?php  print ($CoordState == "DC") ? "selected" : "" ?>">DC</option>
                  <option <?php  print ($CoordState == "DE") ? "selected" : "" ?>="<?php  print ($CoordState == "DE") ? "selected" : "" ?>">DE</option>
                  <option <?php  print ($CoordState == "FL") ? "selected" : "" ?>="<?php  print ($CoordState == "FL") ? "selected" : "" ?>">FL</option>
                  <option <?php  print ($CoordState == "FM") ? "selected" : "" ?>="<?php  print ($CoordState == "FM") ? "selected" : "" ?>">FM</option>
                  <option <?php  print ($CoordState == "GA") ? "selected" : "" ?>="<?php  print ($CoordState == "GA") ? "selected" : "" ?>">GA</option>
                  <option <?php  print ($CoordState == "GU") ? "selected" : "" ?>="<?php  print ($CoordState == "GU") ? "selected" : "" ?>">GU</option>
                  <option <?php  print ($CoordState == "HI") ? "selected" : "" ?>="<?php  print ($CoordState == "HI") ? "selected" : "" ?>">HI</option>
                  <option <?php  print ($CoordState == "IA") ? "selected" : "" ?>="<?php  print ($CoordState == "IA") ? "selected" : "" ?>">IA</option>
                  <option <?php  print ($CoordState == "ID") ? "selected" : "" ?>="<?php  print ($CoordState == "ID") ? "selected" : "" ?>">ID</option>
                  <option <?php  print ($CoordState == "IL") ? "selected" : "" ?>="<?php  print ($CoordState == "IL") ? "selected" : "" ?>">IL</option>
                  <option <?php  print ($CoordState == "IN") ? "selected" : "" ?>="<?php  print ($CoordState == "IN") ? "selected" : "" ?>">IN</option>
                  <option <?php  print ($CoordState == "KS") ? "selected" : "" ?>="<?php  print ($CoordState == "KS") ? "selected" : "" ?>">KS</option>
                  <option <?php  print ($CoordState == "KY") ? "selected" : "" ?>="<?php  print ($CoordState == "KY") ? "selected" : "" ?>">KY</option>
                  <option <?php  print ($CoordState == "LS") ? "selected" : "" ?>="<?php  print ($CoordState == "LS") ? "selected" : "" ?>">LA</option>
                  <option <?php  print ($CoordState == "MA") ? "selected" : "" ?>="<?php  print ($CoordState == "MA") ? "selected" : "" ?>">MA</option>
                  <option <?php  print ($CoordState == "MD") ? "selected" : "" ?>="<?php  print ($CoordState == "MD") ? "selected" : "" ?>">MD</option>
                  <option <?php  print ($CoordState == "ME") ? "selected" : "" ?>="<?php  print ($CoordState == "ME") ? "selected" : "" ?>">ME</option>
                  <option <?php  print ($CoordState == "MH") ? "selected" : "" ?>="<?php  print ($CoordState == "MH") ? "selected" : "" ?>">MH</option>
                  <option <?php  print ($CoordState == "MI") ? "selected" : "" ?>="<?php  print ($CoordState == "MI") ? "selected" : "" ?>">MI</option>
                  <option <?php  print ($CoordState == "MN") ? "selected" : "" ?>="<?php  print ($CoordState == "MN") ? "selected" : "" ?>">MN</option>
                  <option <?php  print ($CoordState == "MO") ? "selected" : "" ?>="<?php  print ($CoordState == "MO") ? "selected" : "" ?>">MO</option>
                  <option <?php  print ($CoordState == "MP") ? "selected" : "" ?>="<?php  print ($CoordState == "MP") ? "selected" : "" ?>">MP</option>
                  <option <?php  print ($CoordState == "MS") ? "selected" : "" ?>="<?php  print ($CoordState == "MS") ? "selected" : "" ?>">MS</option>
                  <option <?php  print ($CoordState == "MT") ? "selected" : "" ?>="<?php  print ($CoordState == "MT") ? "selected" : "" ?>">MT</option>
                  <option <?php  print ($CoordState == "NC") ? "selected" : "" ?>="<?php  print ($CoordState == "NC") ? "selected" : "" ?>">NC</option>
                  <option <?php  print ($CoordState == "ND") ? "selected" : "" ?>="<?php  print ($CoordState == "ND") ? "selected" : "" ?>">ND</option>
                  <option <?php  print ($CoordState == "NE") ? "selected" : "" ?>="<?php  print ($CoordState == "NE") ? "selected" : "" ?>">NE</option>
                  <option <?php  print ($CoordState == "NH") ? "selected" : "" ?>="<?php  print ($CoordState == "NH") ? "selected" : "" ?>">NH</option>
                  <option <?php  print ($CoordState == "NJ") ? "selected" : "" ?>="<?php  print ($CoordState == "NJ") ? "selected" : "" ?>">NJ</option>
                  <option <?php  print ($CoordState == "NM") ? "selected" : "" ?>="<?php  print ($CoordState == "NM") ? "selected" : "" ?>">NM</option>
                  <option <?php  print ($CoordState == "NV") ? "selected" : "" ?>="<?php  print ($CoordState == "NV") ? "selected" : "" ?>">NV</option>
                  <option <?php  print ($CoordState == "NY") ? "selected" : "" ?>="<?php  print ($CoordState == "NY") ? "selected" : "" ?>">NY</option>
                  <option <?php  print ($CoordState == "OH") ? "selected" : "" ?>="<?php  print ($CoordState == "OH") ? "selected" : "" ?>">OH</option>
                  <option <?php  print ($CoordState == "OK") ? "selected" : "" ?>="<?php  print ($CoordState == "OK") ? "selected" : "" ?>">OK</option>
                  <option <?php  print ($CoordState == "OR") ? "selected" : "" ?>="<?php  print ($CoordState == "OR") ? "selected" : "" ?>">OR</option>
                  <option <?php  print ($CoordState == "PA") ? "selected" : "" ?>="<?php  print ($CoordState == "PA") ? "selected" : "" ?>">PA</option>
                  <option <?php  print ($CoordState == "PR") ? "selected" : "" ?>="<?php  print ($CoordState == "PR") ? "selected" : "" ?>">PR</option>
                  <option <?php  print ($CoordState == "PW") ? "selected" : "" ?>="<?php  print ($CoordState == "PW") ? "selected" : "" ?>">PW</option>
                  <option <?php  print ($CoordState == "RI") ? "selected" : "" ?>="<?php  print ($CoordState == "RI") ? "selected" : "" ?>">RI</option>
                  <option <?php  print ($CoordState == "SC") ? "selected" : "" ?>="<?php  print ($CoordState == "SC") ? "selected" : "" ?>">SC</option>
                  <option <?php  print ($CoordState == "SD") ? "selected" : "" ?>="<?php  print ($CoordState == "SD") ? "selected" : "" ?>">SD</option>
                  <option <?php  print ($CoordState == "TN") ? "selected" : "" ?>="<?php  print ($CoordState == "TN") ? "selected" : "" ?>">TN</option>
                  <option <?php  print ($CoordState == "TX") ? "selected" : "" ?>="<?php  print ($CoordState == "TX") ? "selected" : "" ?>">TX</option>
                  <option <?php  print ($CoordState == "UT") ? "selected" : "" ?>="<?php  print ($CoordState == "UT") ? "selected" : "" ?>">UT</option>
                  <option <?php  print ($CoordState == "VA") ? "selected" : "" ?>="<?php  print ($CoordState == "VA") ? "selected" : "" ?>">VA</option>
                  <option <?php  print ($CoordState == "VI") ? "selected" : "" ?>="<?php  print ($CoordState == "VI") ? "selected" : "" ?>">VI</option>
                  <option <?php  print ($CoordState == "VT") ? "selected" : "" ?>="<?php  print ($CoordState == "VT") ? "selected" : "" ?>">VT</option>
                  <option <?php  print ($CoordState == "WA") ? "selected" : "" ?>="<?php  print ($CoordState == "WA") ? "selected" : "" ?>">WA</option>
                  <option <?php  print ($CoordState == "WI") ? "selected" : "" ?>="<?php  print ($CoordState == "WI") ? "selected" : "" ?>">WI</option>
                  <option <?php  print ($CoordState == "WV") ? "selected" : "" ?>="<?php  print ($CoordState == "WV") ? "selected" : "" ?>">WV</option>
                  <option <?php  print ($CoordState == "WY") ? "selected" : "" ?>="<?php  print ($CoordState == "WY") ? "selected" : "" ?>">WY</option>
               </select>
            <?php
            }
            ?>
            </td>
         </tr>
         <tr>
            <td width="12%">&nbsp;</td>
            <td width="36%">&nbsp;</td>
            <td width="19%">Zip</td>
            <td width="30%">
            <?php
            if ($mode == "view")
            {
               print $CoordZip;
            }
            else
            {
            ?>
               <input type="text" name="CoordZip" size="20" <?php  print ($CoordZip != "") ? "value=\"" . $CoordZip . "\"" : ""; ?>="<?php  print ($CoordZip != "") ? "value=\"" . $CoordZip . "\"" : ""; ?>" />
            <?php
            }
            ?>
            </td>
         </tr>
         <tr>
            <td width="12%">Email</td>
            <td width="85%" colspan="3">
            <?php
            if ($mode == "view")
            {
               print $CoordEmail;
            }
            else
            {
            ?>
               <input type="text" name="CoordEmail" size="36" <?php  print ($CoordEmail != "") ? "value=\"" . $CoordEmail . "\"" : ""; ?>="<?php  print ($CoordEmail != "") ? "value=\"" . $CoordEmail . "\"" : ""; ?>" />
            <?php
            }
            ?>
            </td>
         </tr>
         <tr>
            <td width="12%">Phone</td>
            <td width="85%" colspan="3">
            <?php
            if ($mode == "view")
            {
               print $CoordPhone;
            }
            else
            {
            ?>
               <input type="text" name="CoordPhone" size="36" <?php  print ($CoordPhone != "") ? "value=\"" . $CoordPhone . "\"" : ""; ?>="<?php  print ($CoordPhone != "") ? "value=\"" . $CoordPhone . "\"" : ""; ?>" /> (xxx) xxx-xxxx
            <?php
            }
            ?>
            </td>
         </tr>
      </table>
      <p align="center">
         <?php
            if ($mode == 'update')
            {?>
               <input type="submit" value="Update" name="update" />
               <input type="hidden" value="<?php  print $ChurchID;?>" name="ChurchID" />
               <input type="hidden" value="update" name="action" />
             <?php
            }
            else if ($mode == 'add')
            {?>
               <input type="submit" value="Add" name="add" />
               <input type="hidden" value="add" name="action" />
             <?php
            }
            else if ($mode == 'view')
            {?>
               <input type="hidden" value="update" name="action" />
             <?php
            }
         ?>
         <br />
      </p>
   </form>
   <?php footer("Return to Church List","Churches.php")?>
   &nbsp;
   </body>

   <html></html>
<?php
}
