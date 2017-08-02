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

$JudgeID       = "";
$FirstName     = "";
$LastName      = "";
$Address       = "";
$City          = "";
$State         = "";
$Zip           = "";
$Phone         = "";
$Email         = "";

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update')
{
   $mode = 'update';
   $JudgeID = $_REQUEST['JudgeID'];
}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')
{
   $mode = 'view';
   $JudgeID = $_REQUEST['JudgeID'];
}
else //if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add')
{
   $mode = 'add';
}

$ErrorMsg = "";

if ($mode == 'update' || $mode == 'view')
{
   $result = $db->query("select *
                          from   $JudgesTable
                          where  JudgeID = $JudgeID
                          and    ChurchID = $ChurchID")
             or die ("Unable to get Judge information: ".sqlError());
   $row = $result->fetch(PDO::FETCH_ASSOC);

   $JudgeID       = isset($row['JudgeID'])       ? $row['JudgeID']       : "";
   $FirstName     = isset($row['FirstName'])     ? $row['FirstName']     : "";
   $LastName      = isset($row['LastName'])      ? $row['LastName']      : "";
   $Address       = isset($row['Address'])       ? $row['Address']       : "";
   $City          = isset($row['City'])          ? $row['City']          : "";
   $State         = isset($row['State'])         ? $row['State']         : "";
   $Zip           = isset($row['Zip'])           ? $row['Zip']           : "";
   $Phone         = isset($row['Phone'])         ? $row['Phone']         : "";
   $Email         = isset($row['Email'])         ? $row['Email']         : "";
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


   $JudgeID       = isset($_POST['JudgeID'])       ? $_POST['JudgeID']       : "";
   $FirstName     = isset($_POST['FirstName'])     ? $_POST['FirstName']     : "";
   $LastName      = isset($_POST['LastName'])      ? $_POST['LastName']      : "";
   $Address       = isset($_POST['Address'])       ? $_POST['Address']       : "";
   $City          = isset($_POST['City'])          ? $_POST['City']          : "";
   $State         = isset($_POST['State'])         ? $_POST['State']         : "";
   $Zip           = isset($_POST['Zip'])           ? $_POST['Zip']           : "";
   $Phone         = isset($_POST['Phone'])         ? $_POST['Phone']         : "";
   $Email         = isset($_POST['Email'])         ? $_POST['Email']         : "";

   if ($FirstName == "")
   {
      $ErrorMsg = "Please enter the required field: First Name";
   }
   else if ($LastName == "")
   {
      $ErrorMsg = "Please enter the required field: Last Name";
   }
   else if ($Address == "")
   {
      $ErrorMsg = "Please enter the required field: Address";
   }
   else if ($City == "")
   {
      $ErrorMsg = "Please enter the required field: City";
   }
   else if ($State == "0" or $State == "")
   {
      $ErrorMsg = "Please enter the required field: State";
   }
   else if ($Zip == "")
   {
      $ErrorMsg = "Please enter the required field: Zipcode";
   }
   else if ($Phone == "")
   {
      $ErrorMsg = "Please enter the required field: Phone Number";
   }
   else if (!preg_match("/^[0-9]{5}$/",$Zip) and !preg_match("/^[0-9]{5}-[0-9]{4}$/",$Zip))
   {
      $ErrorMsg = "Invalid Zip Code specified. Must be in the format: ##### or #####-####";
   }
   else if (!preg_match("/^\([0-9]{3}\) [0-9]{3}-[0-9]{4}$/",$Phone))
   {
      $ErrorMsg = "Invalid Phone number specified. Must be in the format: (###) ###-####";
   }

   if ($Email == "")
   {
      $Email = "None";
   }

   if ($ErrorMsg == "")
   {

      if ($mode == 'update')
      {
         $sql = "update $JudgesTable
                    set FirstName      = ".$db->quote($FirstName).",
                        LastName       = ".$db->quote($LastName).",
                        Address        = ".$db->quote($Address).",
                        City           = ".$db->quote($City).",
                        State          = ".$db->quote($State).",
                        Zip            = ".$db->quote($Zip).",
                        Email          = ".$db->quote($Email).",
                        Phone          = ".$db->quote($Phone)."
                  where JudgeId        =  $JudgeID
                  and   ChurchId       =  $ChurchID";
      }
      else
      {
         $sql = "insert into $JudgesTable
                        (FirstName   ,
                         LastName    ,
                         Address     ,
                         City        ,
                         State       ,
                         Zip         ,
                         Email       ,
                         Phone       ,
                         ChurchID)
                 values (".$db->quote($FirstName).",
                         ".$db->quote($LastName)." ,
                         ".$db->quote($Address)."  ,
                         ".$db->quote($City)."     ,
                         ".$db->quote($State)."    ,
                         ".$db->quote($Zip)."      ,
                         ".$db->quote($Email)."    ,
                         ".$db->quote($Phone)."    ,
                         $ChurchID)";

      }

      $results = $db->query($sql) or die ("Unable to process update: " . sqlError());

      if ($mode != 'update')
      {
         $JudgeID = $db->lastInsertId();
      }

      ?>
         <body>
         <?php
              if ($mode == 'update')
              {
                ?>
                  <h1 align=center>
                     Judge <br>"<?php  print $LastName . ", " . $FirstName; ?>"<br>Updated!
                  </h1>
                <?php
              }
              else
              {
                ?>
                  <h1 align=center>
                     Judge<br>"<?php  print $LastName . ", " . $FirstName; ?>"<br>Added!
                  </h1>
                <?php
              }

         ?>
            <center><a href="Judges.php">Return to Judge List</a></center>
         </body>
      </html>

      <?php
   }
}

if ((!isset($_POST['add']) and !isset($_POST['update'])) or $ErrorMsg != "")
{
   ?>
   <head>
      <meta http-equiv="Content-Language" content="en-us">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel=stylesheet href="include/registration.css" type="text/css" />

   <?php
      if ($mode == 'update')
      {
      ?>
         <title>Update Judge Record</title>
      <?php
      }
      else if ($mode == 'add')
      {
      ?>
         <title>Add a new Judge</title>
      <?php
      }
      else
      {
      ?>
         <title>Judge</title>
      <?php
      }
   ?>
   </head>

   <body>
   <?php
      if ($mode == 'update')
      {
      ?>
         <h1 align="center">Update Judge Record</h1>
      <?php
      }
      else if ($mode == 'add')
      {
      ?>
         <h1 align="center">Add a new Judge</h1>
      <?php
      }
      else
      {
      ?>
         <h1 align="center">Judge</h1>
      <?php
      }

      if ($ErrorMsg != "")
      {
         print "<center><font color=\"FF0000\"><b>" . $ErrorMsg . "</b></font></center><br>";
      }
   ?>

   <form method="post" action=AdminJudges.php>
      <table class='registrationTable' id="table1">
         <tr>
            <th colspan="5"  align="center">Judge Information</th>
         </tr>
         <tr>
            <th style='width: 15%;'>First Name</td>
            <td style='width: 30%;'>
            <?php
            if ($mode == "view")
            {
               print $FirstName;
            }
            else
            {
            ?>
               <input type="text" name="FirstName" size="36" <?php  print ($FirstName != "") ? "value=\"" . $FirstName . "\"" : ""; ?>>
            <?php
            }
            ?>
            </td>
         </tr>
         <tr>
            <td>Last Name</td>
            <td>
               <?php
               if ($mode == "view")
               {
                  print $LastName;
               }
               else
               {
               ?>
                  <input type="text" name="LastName" size="36" <?php  print ($LastName != "") ? "value=\"" . $LastName . "\"" : ""; ?>>
               <?php
               }
               ?>
            </td>
         </tr>
         <tr>
            <td>Address</td>
            <td>
               <?php
               if ($mode == "view")
               {
                  print $Address;
               }
               else
               {
               ?>
                  <input type="text" name="Address" size="36" <?php  print ($Address != "") ? "value=\"" . $Address . "\"" : ""; ?>>
               <?php
               }
               ?>
            </td>
         </tr>
         <tr>
            <td>City</td>
            <td>
               <?php
               if ($mode == "view")
               {
                  print $City;
               }
               else
               {
               ?>
                  <input type="text" name="City" size="36" <?php  print ($City != "") ? "value=\"" . $City . "\"" : ""; ?>>
               <?php
               }
               ?>
            </td>
         </tr>
         <tr>
            <td>State</td>
            <td>
               <?php
               if ($mode == "view")
               {
                  print $State;
               }
               else
               {
               ?>
                  <select size="1" name="State">
                     <option <?php  print ($State == "0")  ? "selected" : "" ?> value="0">Please Select</option>
                     <option <?php  print ($State == "AK") ? "selected" : "" ?>>AK</option>
                     <option <?php  print ($State == "AL") ? "selected" : "" ?>>AL</option>
                     <option <?php  print ($State == "AK") ? "selected" : "" ?>>AR</option>
                     <option <?php  print ($State == "AS") ? "selected" : "" ?>>AS</option>
                     <option <?php  print ($State == "AZ") ? "selected" : "" ?>>AZ</option>
                     <option <?php  print ($State == "CA") ? "selected" : "" ?>>CA</option>
                     <option <?php  print ($State == "CO") ? "selected" : "" ?>>CO</option>
                     <option <?php  print ($State == "CT") ? "selected" : "" ?>>CT</option>
                     <option <?php  print ($State == "DC") ? "selected" : "" ?>>DC</option>
                     <option <?php  print ($State == "DE") ? "selected" : "" ?>>DE</option>
                     <option <?php  print ($State == "FL") ? "selected" : "" ?>>FL</option>
                     <option <?php  print ($State == "FM") ? "selected" : "" ?>>FM</option>
                     <option <?php  print ($State == "GA") ? "selected" : "" ?>>GA</option>
                     <option <?php  print ($State == "GU") ? "selected" : "" ?>>GU</option>
                     <option <?php  print ($State == "HI") ? "selected" : "" ?>>HI</option>
                     <option <?php  print ($State == "IA") ? "selected" : "" ?>>IA</option>
                     <option <?php  print ($State == "ID") ? "selected" : "" ?>>ID</option>
                     <option <?php  print ($State == "IL") ? "selected" : "" ?>>IL</option>
                     <option <?php  print ($State == "IN") ? "selected" : "" ?>>IN</option>
                     <option <?php  print ($State == "KS") ? "selected" : "" ?>>KS</option>
                     <option <?php  print ($State == "KY") ? "selected" : "" ?>>KY</option>
                     <option <?php  print ($State == "LS") ? "selected" : "" ?>>LA</option>
                     <option <?php  print ($State == "MA") ? "selected" : "" ?>>MA</option>
                     <option <?php  print ($State == "MD") ? "selected" : "" ?>>MD</option>
                     <option <?php  print ($State == "ME") ? "selected" : "" ?>>ME</option>
                     <option <?php  print ($State == "MH") ? "selected" : "" ?>>MH</option>
                     <option <?php  print ($State == "MI") ? "selected" : "" ?>>MI</option>
                     <option <?php  print ($State == "MN") ? "selected" : "" ?>>MN</option>
                     <option <?php  print ($State == "MO") ? "selected" : "" ?>>MO</option>
                     <option <?php  print ($State == "MP") ? "selected" : "" ?>>MP</option>
                     <option <?php  print ($State == "MS") ? "selected" : "" ?>>MS</option>
                     <option <?php  print ($State == "MT") ? "selected" : "" ?>>MT</option>
                     <option <?php  print ($State == "NC") ? "selected" : "" ?>>NC</option>
                     <option <?php  print ($State == "ND") ? "selected" : "" ?>>ND</option>
                     <option <?php  print ($State == "NE") ? "selected" : "" ?>>NE</option>
                     <option <?php  print ($State == "NH") ? "selected" : "" ?>>NH</option>
                     <option <?php  print ($State == "NJ") ? "selected" : "" ?>>NJ</option>
                     <option <?php  print ($State == "NM") ? "selected" : "" ?>>NM</option>
                     <option <?php  print ($State == "NV") ? "selected" : "" ?>>NV</option>
                     <option <?php  print ($State == "NY") ? "selected" : "" ?>>NY</option>
                     <option <?php  print ($State == "OH") ? "selected" : "" ?>>OH</option>
                     <option <?php  print ($State == "OK") ? "selected" : "" ?>>OK</option>
                     <option <?php  print ($State == "OR") ? "selected" : "" ?>>OR</option>
                     <option <?php  print ($State == "PA") ? "selected" : "" ?>>PA</option>
                     <option <?php  print ($State == "PR") ? "selected" : "" ?>>PR</option>
                     <option <?php  print ($State == "PW") ? "selected" : "" ?>>PW</option>
                     <option <?php  print ($State == "RI") ? "selected" : "" ?>>RI</option>
                     <option <?php  print ($State == "SC") ? "selected" : "" ?>>SC</option>
                     <option <?php  print ($State == "SD") ? "selected" : "" ?>>SD</option>
                     <option <?php  print ($State == "TN") ? "selected" : "" ?>>TN</option>
                     <option <?php  print ($State == "TX") ? "selected" : "" ?>>TX</option>
                     <option <?php  print ($State == "UT") ? "selected" : "" ?>>UT</option>
                     <option <?php  print ($State == "VA") ? "selected" : "" ?>>VA</option>
                     <option <?php  print ($State == "VI") ? "selected" : "" ?>>VI</option>
                     <option <?php  print ($State == "VT") ? "selected" : "" ?>>VT</option>
                     <option <?php  print ($State == "WA") ? "selected" : "" ?>>WA</option>
                     <option <?php  print ($State == "WI") ? "selected" : "" ?>>WI</option>
                     <option <?php  print ($State == "WV") ? "selected" : "" ?>>WV</option>
                     <option <?php  print ($State == "WY") ? "selected" : "" ?>>WY</option>
                  </select>
               <?php
               }
               ?>
            </td>
         </tr>
         <tr>
            <td>Zip</td>
            <td>
               <?php
               if ($mode == "view")
               {
                  print $Zip;
               }
               else
               {
               ?>
                  <input type="text" name="Zip" size="20" <?php  print ($Zip != "") ? "value=\"" . $Zip . "\"" : ""; ?>>
               <?php
               }
               ?>
            </td>
         </tr>
         <tr>
            <td>Email</td>
            <td>
               <?php
               if ($mode == "view")
               {
                  print $Email;
               }
               else
               {
               ?>
                  <input type="text" name="Email" size="36" <?php  print ($Email != "") ? "value=\"" . $Email . "\"" : ""; ?>>
               <?php
               }
               ?>
            </td>
         </tr>
         <tr>
            <td>Phone</td>
            <td>
               <?php
               if ($mode == "view")
               {
                  print $Phone;
               }
               else
               {
               ?>
                  <input type="text" name="Phone" size="36" <?php  print ($Phone != "") ? "value=\"" . $Phone . "\"" : ""; ?>> (xxx) xxx-xxxx
               <?php
               }
               ?>
            </td>
         </tr>
         </table>
         <br>
      <p align="center">
         <?php
            if ($mode == 'update')
            {?>
               <input type="submit" value="Update" name="update">
               <input type="hidden" value="<?php  print $JudgeID; ?>" name=JudgeID>
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
   <?php footer("Return to Judge List","Judges.php")?>
   </body>

   </html>
<?php
}