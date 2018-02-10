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
require 'include/RegFunctions.php';

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update')
{
   $mode = 'update';
   $charmerID = $_REQUEST['id'];
}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')
{
   $mode = 'view';
   $charmerID = $_REQUEST['id'];
}
else #if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add')
{
   $mode        = 'add';
   $charmerName         = '';
   $charmerSex         = '';
   $charmerTshirtSize   = '';
   $charmerTshirtNeeded = '';
   $charmerNeedRoom     = '';
   $charmerChurchID     = '';
   $charmerAvailibility = '';
   $charmerEmail        = '';
   $charmerPhone        = '';
}

$ErrorMsg = "";

if ($mode == 'update' || $mode == 'view')
{
   $result = $db->query("select    charmerName,
                                   charmerSex,
                                   charmerTshirtSize,
                                   charmerTshirtNeeded,
                                   charmerNeedRoom,
                                   charmerAvailibility,
                                   charmerEmail,
                                   charmerPhone
                          from     $CharmersTable
                          where    charmerID=$charmerID
                          and      ChurchID=$ChurchID
                         ")
             or die ("Unable to get Charmer information: ".sqlError());
   $row = $result->fetch(PDO::FETCH_ASSOC);

   $charmerName         = isset($row['charmerName'])          ? $row['charmerName']         : "";
   $charmerSex          = isset($row['charmerSex'])           ? $row['charmerSex']          : "";
   $charmerTshirtSize   = isset($row['charmerTshirtSize'])    ? $row['charmerTshirtSize']   : "";
   $charmerTshirtNeeded = isset($row['charmerTshirtNeeded'])  ? $row['charmerTshirtNeeded'] : "";
   $charmerNeedRoom     = isset($row['charmerNeedRoom'])      ? $row['charmerNeedRoom']     : "";
   $charmerAvailibility = isset($row['charmerAvailibility'])  ? $row['charmerAvailibility'] : "";
   $charmerEmail        = isset($row['charmerEmail'])         ? $row['charmerEmail']        : "";
   $charmerPhone        = isset($row['charmerPhone'])         ? $row['charmerPhone']        : "";
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

   $charmerName         = isset($_POST['charmerName'])          ? $_POST['charmerName']         : "";
   $charmerSex          = isset($_POST['charmerSex'])           ? $_POST['charmerSex']          : "";
   $charmerTshirtSize   = isset($_POST['charmerTshirtSize'])    ? $_POST['charmerTshirtSize']   : "";
   $charmerTshirtNeeded = isset($_POST['charmerTshirtNeeded'])  ? $_POST['charmerTshirtNeeded'] : "";
   $charmerNeedRoom     = isset($_POST['charmerNeedRoom'])      ? $_POST['charmerNeedRoom']     : "";
   $charmerAvailibility = isset($_POST['charmerAvailibility'])  ? $_POST['charmerAvailibility'] : "";
   $charmerEmail        = isset($_POST['charmerEmail'])         ? $_POST['charmerEmail']        : "";
   $charmerPhone        = isset($_POST['charmerPhone'])         ? $_POST['charmerPhone']        : "";


   if ($charmerName == "")
      $ErrorMsg = "Please enter the required field: Name";

   else if ($charmerSex == "")
      $ErrorMsg = "Please indicate the sex of the Charmer";

   else if ($charmerTshirtSize == "" or $charmerTshirtSize == '0')
      $ErrorMsg = "Please enter the required field: TShirt Size";

   elseif ($charmerEmail == "")
         $ErrorMsg = "Please Enter Email address";

   elseif ($charmerEmail != "" and !filter_var($charmerEmail, FILTER_VALIDATE_EMAIL))
      $ErrorMsg="Sorry, That does not appear to be a valid email address";

   elseif ($charmerPhone == "")
      $ErrorMsg = "Please Supply a phone number that can be used to reach them";


   if ($ErrorMsg == "")
   {
      if ($mode == 'update')
      {
         $results = $db->query("update $CharmersTable
                                 set    charmerName         = ".$db->quote($charmerName).",
                                        charmerSex          = ".$db->quote($charmerSex).",
                                        charmerTshirtSize   = ".$db->quote($charmerTshirtSize).",
                                        charmerTshirtNeeded = ".$db->quote($charmerTshirtNeeded).",
                                        charmerNeedRoom     = ".$db->quote($charmerNeedRoom).",
                                        charmerAvailibility = ".$db->quote($charmerAvailibility).",
                                        charmerEmail        = ".$db->quote($charmerEmail).",
                                        charmerPhone        = ".$db->quote($charmerPhone)."
                                 where  charmerID           = $charmerID
                                ")
                    or die ("Unable to process update: " . sqlError());
      }
      else
      {
         $sql = "insert into $CharmersTable
                                 (ChurchID,
                                  charmerName,
                                  charmerSex,
                                  charmerTshirtSize,
                                  charmerTshirtNeeded,
                                  charmerNeedRoom,
                                  charmerAvailibility,
                                  charmerEmail,
                                  charmerPhone)
                          values ($ChurchID                            ,
                                  ".$db->quote($charmerName)          .",
                                  ".$db->quote($charmerSex)           .",
                                  ".$db->quote($charmerTshirtSize)    .",
                                  ".$db->quote($charmerTshirtNeeded)  .",
                                  ".$db->quote($charmerNeedRoom)      .",
                                  ".$db->quote($charmerAvailibility)  .",
                                  ".$db->quote($charmerEmail)         .",
                                  ".$db->quote($charmerPhone)         ."
                          )
                 ";

         $db->query($sql) or die ("Unable to process insert: " . sqlError());
      }

      if ($ErrorMsg == "")
      {
      ?>
         <body>
         <?php
              if ($mode == 'update')
              {
                ?>
                  <h1 align="center">
                     Charmer <br />"<?php  print $charmerName; ?>"<br />Updated!
                  </h1>
                <?php
              }
              else
              {
                ?>
                  <h1 align="center">
                     Charmer<br />"<?php  print $charmerName; ?>"<br />Added!
                  </h1>
                <?php
              }

         ?>
            <div style="text-align: center"><a href="Charmers.php">Return to Charmer List</a></div>
         </body>
      </html>

      <?php
      }
   }
}

if ((!isset($_POST['add']) and !isset($_POST['update'])) or $ErrorMsg != "")
{
   ?>
   <head>
      <meta http-equiv="Content-Language" content="en-us" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="stylesheet" href="include/registration.css" type="text/css" />

   <?php
      if ($mode == 'update')
      {
      ?>
         <title>Update Charmer Record</title>
      <?php
      }
      else if ($mode == 'add')
      {
      ?>
         <title>Add a new Charmer</title>
      <?php
      }
      else
      {
      ?>
         <title>Charmer</title>
      <?php
      }
   ?>
   </head>

   <body>
   <?php
      if ($mode == 'update')
      {
      ?>
         <h1 align="center">Update Charmer Record</h1>
      <?php
      }
      else if ($mode == 'add')
      {
      ?>
         <h1 align="center">Add a new Charmer</h1>
      <?php
      }
      else
      {
      ?>
         <h1 align="center">Charmer</h1>
      <?php
      }

      if ($ErrorMsg != "")
      {
         print "<div style='text-align: center'><font color=\"#FF0000\"><b>" . $ErrorMsg . "</b></font></div><br />";
      }
   ?>

      <?php
         $requestString='';
         if (isset($_REQUEST['action']))
            $requestString="?action=".$_REQUEST['action'];
         if (isset($_REQUEST['id']) and isset($_REQUEST['id']))
            $requestString.="&id=".$_REQUEST['id'];

         ?>
      <form method="post" action="AdminCharmers.php<?php print $requestString?>">
         <table class='registrationTable' id="table1">
            <tr>
               <th colspan="2" style='text-align: center'><h2>Charmer Information</h2></th>
            </tr>
            <tr>
               <th style='width: 15%'>Charmer</th>
               <td style='width: 85%'>
                  <?php
                  if ($mode == 'view' or $mode == 'update')
                  {
                     print $charmerName;
                  ?>
                     <input type="hidden" name="charmerName" value="<?php print $charmerName?>"/>
                  <?php
                  }
                  else
                  {
                  ?>
                     <input type="text" name="charmerName" size="36" <?php  print ($charmerName != "") ? "value=\"" . $charmerName . "\"" : ""; ?> /></td>
                  <?php
                  }
               ?>
            </tr>
            <tr>
               <th>Phone</th>
               <td>
               <?php
                  if ($mode == 'view')
                  {
                     print $charmerPhone;
                  }
                  else
                  {
                  ?>
                     <input type="text" name="charmerPhone" size="36" <?php  print ($charmerPhone != "") ? "value=\"" . $charmerPhone . "\"" : ""; ?> />
                  <?php
                  }
               ?>
               </td>
            </tr>
            <tr>
               <th>Email</th>
               <td>
                  <?php
                  if ($mode == 'view')
                  {
                     print $charmerEmail;
                  }
                  else
                  {
                  ?>
                     <input type="text" name="charmerEmail" size="36" <?php  print ($charmerEmail != "") ? "value=\"" . $charmerEmail . "\"" : ""; ?> />
                  <?php
                  }
                  ?>
               </td>
            </tr>
            <tr>
               <th>Sex</th>
               <td>
               <?php
                  if ($mode == 'view')
                  {
                     print $charmerSex;
                  }
                  else
                  {
                  ?>
                     <input type="radio" name="charmerSex" value="F" <?php  print $charmerSex == "F" ? "checked" : ""; ?> /> Female<br />
                     <input type="radio" name="charmerSex" value="M" <?php  print $charmerSex == "M" ? "checked" : ""; ?> /> Male
                  <?php
                  }
               ?>
               </td>
            </tr>
            <tr>
               <th>Need Room</th>
               <td>
                  <?php
                  if ($mode == 'view')
                  {
                     print $charmerNeedRoom == 'on' ? 'Yes' : 'No';
                  }
                  else
                  {
                  ?>
                     <input type="checkbox" name="charmerNeedRoom" <?php if ($charmerNeedRoom == 'on') print 'checked'?> />
                  <?php
                  }
                  ?>
               </td>
            </tr>
            <tr>
               <th>Need Shirt</th>
               <td>
                  <?php
                  if ($mode == 'view')
                  {
                     print $charmerTshirtNeeded == 'on' ? 'Yes' : 'No';
                  }
                  else
                  {
                  ?>
                     <input type="checkbox" name="charmerTshirtNeeded" <?php if ($charmerTshirtNeeded == 'on') print 'checked'?> />
                  <?php
                  }
                  ?>
               </td>
            </tr>
            <tr>
               <th>TShirt Size</th>
               <td>
                  <?php
                  if ($mode == 'view')
                  {
                     print $charmerTshirtSize;
                  }
                  else
                  {
                  ?>
                     <select size="1" name="charmerTshirtSize">
                        <option value="0">Select Shirt Size</option>
                        <option value="N/A"    <?php print $charmerTshirtSize == 'N/A'    ? 'Selected':''?>>N/A    </option>
                        <option value="Small"  <?php print $charmerTshirtSize == 'Small'  ? 'Selected':''?>>Small  </option>
                        <option value="Medium" <?php print $charmerTshirtSize == 'Medium' ? 'Selected':''?>>Medium </option>
                        <option value="Large"  <?php print $charmerTshirtSize == 'Large'  ? 'Selected':''?>>Large  </option>
                        <option value="XL"     <?php print $charmerTshirtSize == 'XL'     ? 'Selected':''?>>XL     </option>
                        <option value="XXL"    <?php print $charmerTshirtSize == 'XXL'    ? 'Selected':''?>>XXL    </option>
                        <option value="XXXl"   <?php print $charmerTshirtSize == 'XXXL'   ? 'Selected':''?>>XXXL   </option>
                     </select>
                  <?php
                  }
                  ?>
               </td>
            </tr>
            <tr>
               <th style='vertical-align: middle'>Availabiity and comments</th>
               <td style='vertical-align: middle'>
               <?php
               if ($mode == 'view')
               {
                  print preg_replace("/\n/","<br />\n",$charmerAvailibility);
               }
               else
               {
               ?>
                  <textarea name="charmerAvailibility" rows="2" cols="65"><?php print $charmerAvailibility?></textarea><br />
                  Please supply when available and any specific information that would be helpful
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
      <?php footer("Return to Charmer List","Charmers.php")?>
   </body>

   </html>
<?php
}
