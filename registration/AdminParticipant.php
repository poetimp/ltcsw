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

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update')
{
   $mode = 'update';
   $ParticipantID = $_REQUEST['ParticipantID'];
}
else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'view')
{
   $mode = 'view';
   $ParticipantID = $_REQUEST['ParticipantID'];
}
else //if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add')
{
   $mode = 'add';
   $ParticipantID = "";
   $FirstName     = "";
   $LastName      = "";
   $Address       = "";
   $City          = "";
   $State         = "";
   $Zip           = "";
   $Grade         = "";
   $Gender        = "";
   $AttendConv    = "";
   $ShirtSize     = "";
   $InfoToUniv    = "";
   $Comments      = "";
   $Phone         = "";
   $Email         = "";
   $MealTicket    = "";
}

$ErrorMsg = "";

if ($mode == 'update' || $mode == 'view')
{
   $result = $db->query("select  *
                          from    $ParticipantsTable
                          where   ParticipantID='$ParticipantID'
                          and     ChurchID     ='$ChurchID'
                         ")
             or die ("Unable to get participant information: ".sqlError());
   $row = $result->fetch(PDO::FETCH_ASSOC);

   $ParticipantID = isset($row['ParticipantID']) ? $row['ParticipantID'] : "";
   $FirstName     = isset($row['FirstName'])     ? $row['FirstName']     : "";
   $LastName      = isset($row['LastName'])      ? $row['LastName']      : "";
   $Address       = isset($row['Address'])       ? $row['Address']       : "";
   $City          = isset($row['City'])          ? $row['City']          : "";
   $State         = isset($row['State'])         ? $row['State']         : "";
   $Zip           = isset($row['Zip'])           ? $row['Zip']           : "";
   $Grade         = isset($row['Grade'])         ? $row['Grade']         : "";
   $Gender        = isset($row['Gender'])        ? $row['Gender']        : "";
   $AttendConv    = isset($row['AttendConv'])    ? $row['AttendConv']    : "";
   $ShirtSize     = isset($row['ShirtSize'])     ? $row['ShirtSize']     : "";
   $InfoToUniv    = isset($row['InfoToUniv'])    ? $row['InfoToUniv']    : "";
   $Comments      = isset($row['Comments'])      ? $row['Comments']      : "";
   $Phone         = isset($row['Phone'])         ? $row['Phone']         : "";
   $Email         = isset($row['Email'])         ? $row['Email']         : "";
   $MealTicket    = isset($row['MealTicket'])    ? $row['MealTicket']    : "I";
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


   $ParticipantID = isset($_POST['ParticipantID']) ? $_POST['ParticipantID'] : "";
   $FirstName     = isset($_POST['FirstName'])     ? $_POST['FirstName']     : "";
   $LastName      = isset($_POST['LastName'])      ? $_POST['LastName']      : "";
   $Address       = isset($_POST['Address'])       ? $_POST['Address']       : "";
   $City          = isset($_POST['City'])          ? $_POST['City']          : "";
   $State         = isset($_POST['State'])         ? $_POST['State']         : "";
   $Zip           = isset($_POST['Zip'])           ? $_POST['Zip']           : "";
   $Grade         = isset($_POST['Grade'])         ? $_POST['Grade']         : "";
   $Gender        = isset($_POST['Gender'])        ? $_POST['Gender']        : "";
   $AttendConv    = isset($_POST['AttendConv'])    ? $_POST['AttendConv']    : "";
   $ShirtSize     = isset($_POST['ShirtSize'])     ? $_POST['ShirtSize']     : "";
   $InfoToUniv    = isset($_POST['InfoToUniv'])    ? $_POST['InfoToUniv']    : "";
   $Comments      = isset($_POST['Comments'])      ? $_POST['Comments']      : "";
   $Phone         = isset($_POST['Phone'])         ? $_POST['Phone']         : "";
   $Email         = isset($_POST['Email'])         ? $_POST['Email']         : "";
   $MealTicket    = isset($_POST['MealTicket'])    ? $_POST['MealTicket']    : "I";


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
   else if ($Grade == "0" or $Grade == "")
   {
      $ErrorMsg = "Please enter the required field: Grade";
   }
   else if ($ShirtSize == "0" or $ShirtSize == "")
   {
      $ErrorMsg = "Please enter the required field: Shirt Size";
   }
   else if ($Phone == "")
   {
      $ErrorMsg = "Please enter the required field: Phone Number";
   }
   else if ($Gender == "")
   {
      $ErrorMsg = "Please enter the required field: Sex";
   }
   else if ($AttendConv == "")
   {
      $ErrorMsg = "Please enter the required field: Attending Convention";
   }
   else if ($InfoToUniv == "")
   {
      $ErrorMsg = "Please enter the required field: Send information to Christian Universities";
   }
//   else if ($MealTicket == "0" or $MealTicket == "")
//   {
//      $ErrorMsg = "Please enter the required field: Meal Ticket";
//   }
   else if ($Grade == 12 and preg_match("/^\s*$/",$Comments))
   {
      $ErrorMsg = "Comments required for Seniors.<br>Please indicate how many years they have been in LTC and what their college plans are.<br>Thank you!";
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
      $FirstName = addslashes($FirstName);
      $LastName  = addslashes($LastName);
      $Address   = addslashes($Address);
      $City      = addslashes($City);
      $Zip       = addslashes($Zip);
      $Phone     = addslashes($Phone);
      $Email     = addslashes($Email);
      $Comments  = addslashes($Comments);


      if ($mode == 'update')
      {
//         print "<pre>
//                    update $ParticipantsTable
//                    set FirstName      = '$FirstName'  ,
//                        LastName       = '$LastName'   ,
//                        Address        = '$Address'    ,
//                        City           = '$City'       ,
//                        State          = '$State'      ,
//                        Zip            = '$Zip'        ,
//                        Grade          = '$Grade'      ,
//                        Gender         = '$Gender'     ,
//                        AttendConv     = '$AttendConv' ,
//                        ShirtSize      = '$ShirtSize'  ,
//                        InfoToUniv     = '$InfoToUniv' ,
//                        Email          = '$Email'      ,
//                        Phone          = '$Phone'      ,
//                        MealTicket     = '$MealTicket' ,
//                        Comments       = '$Comments'
//                  where ParticipantId  = '$ParticipantID'
//                  and   ChurchId       = '$ChurchID'
//                </pre>";
//
         $sql = "update $ParticipantsTable
                    set FirstName      = '$FirstName'  ,
                        LastName       = '$LastName'   ,
                        Address        = '$Address'    ,
                        City           = '$City'       ,
                        State          = '$State'      ,
                        Zip            = '$Zip'        ,
                        Grade          = '$Grade'      ,
                        Gender         = '$Gender'     ,
                        AttendConv     = '$AttendConv' ,
                        ShirtSize      = '$ShirtSize'  ,
                        InfoToUniv     = '$InfoToUniv' ,
                        Email          = '$Email'      ,
                        Phone          = '$Phone'      ,
                        MealTicket     = '$MealTicket' ,
                        Comments       = '$Comments'
                  where ParticipantId  = '$ParticipantID'
                  and   ChurchId       = '$ChurchID'
                 ";
      }
      else
      {
         $sql = "insert into $ParticipantsTable
                        (FirstName   ,
                         LastName    ,
                         Address     ,
                         City        ,
                         State       ,
                         Zip         ,
                         Grade       ,
                         Gender      ,
                         AttendConv  ,
                         ShirtSize   ,
                         InfoToUniv  ,
                         Email       ,
                         Phone       ,
                         MealTicket  ,
                         Comments    ,
                         ChurchID)
                 values ('$FirstName' ,
                         '$LastName'  ,
                         '$Address'   ,
                         '$City'      ,
                         '$State'     ,
                         '$Zip'       ,
                         '$Grade'     ,
                         '$Gender'    ,
                         '$AttendConv',
                         '$ShirtSize' ,
                         '$InfoToUniv',
                         '$Email'     ,
                         '$Phone'     ,
                         '$MealTicket',
                         '$Comments'  ,
                         '$ChurchID'
                         )";
      }

      $results = $db->query($sql) or die ("Unable to process update: " . sqlError());
   ?>
         <body>
         <?php
              if ($mode == 'update')
              {
                ?>
                  <h1 align=center>
                     Participant <br>"<?php  print $LastName . ", " . $FirstName; ?>"<br>Updated!
                  </h1>
                <?php
              }
              else
              {
                ?>
                  <h1 align=center>
                     Participant<br>"<?php  print $LastName . ", " . $FirstName; ?>"<br>Added!
                  </h1>
                <?php
              }

         ?>
            <center><a href="Participants.php">Return to Participant List</a></center>
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
         <title>Update Participant Record</title>
      <?php
      }
      else if ($mode == 'add')
      {
      ?>
         <title>Add a new Participant</title>
      <?php
      }
      else
      {
      ?>
         <title>Participant</title>
      <?php
      }
   ?>
   </head>

   <body>
   <?php
      if ($mode == 'update')
      {
      ?>
         <h1 align="center">Update Participant Record</h1>
      <?php
      }
      else if ($mode == 'add')
      {
      ?>
         <h1 align="center">Add a new Participant</h1>
      <?php
      }
      else
      {
      ?>
         <h1 align="center">Participant</h1>
      <?php
      }

      if ($ErrorMsg != "")
      {
         print "<center><font color=\"FF0000\"><b>" . $ErrorMsg . "</b></font></center><br>";
      }
   ?>

   <form method="post" action=AdminParticipant.php>
      <table class='registrationTable' style='width: 95%' id="table1">
         <tr>
            <th colspan="5" align="center">Participant Information
            </th>
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
            <th style='width: 15%;'>
               Last Name
            </th>
            <td style='width: 29%;' colspan="2">
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
            <th style='width: 12%;'>Address</td>
            <td style='width: 85%;' colspan="4">
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
            <th style='width: 12%;'>City</td>
            <td style='width: 36%;'>
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
            <th style='width: 12%;'>State</td>
            <td style='width: 30%;' colspan="2">
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
            <th style='width: 12%;'>&nbsp;</td>
            <th style='width: 36%;'>&nbsp;</td>
            <th style='width: 12%;'>Zip</td>
            <td style='width: 37%;' colspan="2">
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
            <th style='width: 12%;'>Grade</td>
            <td style='width: 29%;'>
            <?php
            if ($mode == "view")
            {
               print $Grade;
            }
            else
            {
            ?>
               <select name="Grade" size="1">
                  <option <?php  print ($Grade == "0")  ? "selected" : "" ?> value="0">Please Select</option>
                  <option <?php  print ($Grade == "3")  ? "selected" : "" ?>>3</option>
                  <option <?php  print ($Grade == "4")  ? "selected" : "" ?>>4</option>
                  <option <?php  print ($Grade == "5")  ? "selected" : "" ?>>5</option>
                  <option <?php  print ($Grade == "6")  ? "selected" : "" ?>>6</option>
                  <option <?php  print ($Grade == "7")  ? "selected" : "" ?>>7</option>
                  <option <?php  print ($Grade == "8")  ? "selected" : "" ?>>8</option>
                  <option <?php  print ($Grade == "9")  ? "selected" : "" ?>>9</option>
                  <option <?php  print ($Grade == "10") ? "selected" : "" ?>>10</option>
                  <option <?php  print ($Grade == "11") ? "selected" : "" ?>>11</option>
                  <option <?php  print ($Grade == "12") ? "selected" : "" ?>>12</option>
               </select>
            <?php
            }
            ?>
            </td>
            <td style='width: 28%;'>
            Sex</td>
            <?php
            if ($mode == "view")
            {
               print "<td style='width: 28%;' colspan=2>";
               print $Gender == "M" ? "Male" : "Female";
               print "</td>";
            }
            else
            {
            ?>
            <td style='width: 14%;'>
            <input type="radio" value="M" name="Gender" <?php  print ($Gender == "M") ? "checked" : "" ?>>Male</td>
            <td style='width: 14%;'>
            <input type="radio" value="F" name="Gender" <?php  print ($Gender == "F") ? "checked" : "" ?>>Female</td>
            <?php
            }
            ?>
         </tr>
         <tr>
            <th style='width: 12%;'>Shirt Size</td>
            <td style='width: 29%;'>
            <?php
            if ($mode == "view")
            {
               if ($ShirtSize == "YM")
               {
                  print "Youth Medium";
               }
               else if ($ShirtSize == "YL")
               {
                  print "Youth Large";
               }
               else if ($ShirtSize == "S")
               {
                  print "Adult Small";
               }
               else if ($ShirtSize == "M")
               {
                  print "Adult Medium";
               }
               else if ($ShirtSize == "LG")
               {
                  print "Adult Large";
               }
               else if ($ShirtSize == "XL")
               {
                  print "Adult X-Large";
               }
               else if ($ShirtSize == "XX")
               {
                  print "Adult XX-Large";
               }
            }
            else
            {
            ?>
               <select name="ShirtSize" size="1">
                  <option <?php  print ($ShirtSize == "0")  ? "selected" : "" ?> value="0">Please Select</option>
                  <option value="YM" <?php  print ($ShirtSize == "YM") ? "selected" : "" ?>>Youth Medium</option>
                  <option value="YL" <?php  print ($ShirtSize == "YL") ? "selected" : "" ?>>Youth Large</option>
                  <option value="S"  <?php  print ($ShirtSize == "S")  ? "selected" : "" ?>>Adult Small</option>
                  <option value="M"  <?php  print ($ShirtSize == "M")  ? "selected" : "" ?>>Adult Medium</option>
                  <option value="LG" <?php  print ($ShirtSize == "LG") ? "selected" : "" ?>>Adult Large</option>
                  <option value="XL" <?php  print ($ShirtSize == "XL") ? "selected" : "" ?>>Adult X-Large</option>
                  <option value="XX" <?php  print ($ShirtSize == "XX") ? "selected" : "" ?>>Adult XX-Large</option>
               </select>
            <?php
            }
            ?>
            </td>
            <?php
            if ($mode == "view")
            {
               print "<td style='width: 56%;' colspan=3>";
               print $AttendConv == "Y" ? "Attending Convention" : "Not Attending Convention";
               print "</td>";
            }
            else
            {
            ?>
            <th style='width: 28%;'>Attend Convention</td>
            <td style='width: 14%;'<input type="radio" name="AttendConv" value="Y" <?php  print ($AttendConv == "Y") ? "checked" : "" ?>>Yes</td>
            <td style='width: 14%;'<input type="radio" name="AttendConv" value="N" <?php  print ($AttendConv == "N") ? "checked" : "" ?>>No</td>
            <?php
            }
            ?>
         </tr>
         <tr>
            <th style='width: 12%;'>Email</td>
            <td style='width: 29%;'>
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
            <?php
            if ($mode == "view")
            {
               print "<td style='width: 56%;' colspan=3>";
               print $InfoToUniv == "Y" ? "Send information to Universities" : "Do Not Send information to Universities";
               print "</td>";
            }
            else
            {
            ?>
            <th style='width: 28%;'>Notify Christian Universities</td>
            <td style='width: 14%;'<input type="radio" name="InfoToUniv" value="Y" <?php  print ($InfoToUniv == "Y") ? "checked" : "" ?>>Yes</td>
            <td style='width: 14%;'<input type="radio" name="InfoToUniv" value="N" <?php  print ($InfoToUniv == "N") ? "checked" : "" ?>>No</td>
            <?php
            }
            ?>
         </tr>
         <tr>
            <th style='width: 12%;'>Phone</td>
            <td style='width: 29%;' colspan="1">
            <?php
            if ($mode == "view")
            {
               print $Phone;
            }
            else
            {
            ?>
               <input type="text" name="Phone" size="36" <?php  print ($Phone != "") ? "value=\"" . $Phone . "\"" : ""; ?>> <br>(xxx) xxx-xxxx
            <?php
            }
            ?>
            </td>
            <?php
            if ($mode == "view")
            {
               print "<td style='width: 56%;' colspan=3>";
//               if ($MealTicket == "N")
//               {
//                  print "Meal Ticket Declined";
//               }
//               else if ($MealTicket == "3")
//               {
//                  print "Three Meal Ticket";
//               }
//               else if ($MealTicket == "5")
//               {
//                  print "Five Meal Ticket";
//               }
//               else
//               {
//                  print "$MealTicket";
//               }
               print "&nbsp;";
               print "</td>";
            }
            else
            {
            ?>
                <td style='width: 56%;' colspan=3>&nbsp;</td>
<!--            <td style='width: 28%;' colspan=1>Meal Ticket (<A href="http://www.ltcsw.org/faq.htm#13" target="_blank">help</A>)</td>-->
<!--            <td style='width: 28%;' colspan=2>-->
<!--               <select name="MealTicket" size="1">-->
<!--                  <option <?php  print ($MealTicket == "0")  ? "selected" : "" ?> value="0">Please Select</option>-->
<!--                  <option value="N" <?php  print ($MealTicket == "N") ? "selected" : "" ?>>None</option>-->
<!--                  <option value="3" <?php  print ($MealTicket == "3") ? "selected" : "" ?>>3 Meals</option>-->
<!--                  <option value="5" <?php  print ($MealTicket == "5") ? "selected" : "" ?>>5 Meals</option>-->
<!--               </select>-->
<!--            </td>-->
            <?php
            }
            ?>
         </tr>
         <tr>
            <th style='width: 97%;' colspan="5" align="center">Comments</th>
         </tr>
         <tr>
            <td style='width: 97%;' colspan="5">
            <?php
            if ($mode == "view")
            {
               print str_replace("\n","<br>",$Comments);
            }
            else
            {
            ?>
               <p align="center">
               <textarea rows="4" name="Comments" cols="76" ><?php  print $Comments;?></textarea>
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
               <input type="submit" value="Update" name="update">
               <input type="hidden" value="<?php  print $ParticipantID; ?>" name=ParticipantID>
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
   <?php footer("Return to Participant List","Participants.php")?>   </body>

   </html>
<?php
}