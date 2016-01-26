<?php
session_start();
require '../ssl-registration/include/config.php';
require '../ssl-registration/include/MySql-connect.inc.php';
require 'dropbox-sdk/Dropbox/autoload.php';

use \Dropbox as dbx;

//your access token from the Dropbox App Panel: https://www.dropbox.com/developers/apps
$accessToken = '***REMOVED***';

// load the dropbox credentials
$appInfo = dbx\AppInfo::loadFromJsonFile(__DIR__."/config.json");

//===================================================================
// Pickup and validate variables
//===================================================================
$SubmitterName   = isset($_POST['SubmitterName'])  ? $_POST['SubmitterName']  : '';
$SubmitterEmail  = isset($_POST['SubmitterEmail']) ? $_POST['SubmitterEmail'] : '';
$SubmitterCong   = isset($_POST['SubmitterCong'])  ? $_POST['SubmitterCong']  : '';
$SubmitterID     = isset($_POST['SubmitterID'])    ? $_POST['SubmitterID']    : '';

$thisMonth = date('m');
$thisYear  = date('Y');

if ($thisMonth > 5) $thisYear++;
$dropboxDirectory = '/Seniors';
//===========================================================================================
// Create the array with all of the Churches in it
//===========================================================================================

$query = "select   distinct
                   ChurchName,
                   c.ChurchID
          from     LTC_PHX_Churches c,
                   LTC_PHX_Registration r,
                   LTC_PHX_Participants p
          where    c.ChurchID=r.ChurchID
          and      r.ChurchID=p.ChurchID
          and      r.ParticipantID=p.ParticipantID
          and      p.Grade=12
          order    by ChurchName";
$result = $db->query($query)or die ("Unable to obtain church list:" . sqlError());
while($row = $result->fetch(PDO::FETCH_ASSOC)){
   $churches[] = array("id" => $row['ChurchID'], "val" => $row['ChurchName']);
   $church[$row['ChurchID']] = $row['ChurchName'];
}
//===========================================================================================
// Create the array with all of the Participant that are seniors
//===========================================================================================

$query   = "SELECT distinct
                      r.ChurchID,
                      r.ParticipantID,
                      concat(LastName,', ',FirstName) as ParticipantName
               FROM LTC_PHX_Registration r,
                    LTC_PHX_Participants p
               where r.ParticipantID=p.ParticipantID
               and   r.ChurchID=p.ChurchID
               and   p.Grade=12
               order by ParticipantName";
$result = $db->query($query)or die ("Unable to obtain participant list:" . sqlError());
while($row = $result->fetch(PDO::FETCH_ASSOC)){
   $participants[$row['ChurchID']][] = array("id" => $row['ParticipantID'], "val" => $row['ParticipantName']);
   $participant[$row['ParticipantID']] = $row['ParticipantName'];
}

//===========================================================================================
// Collect the results from above into json entities so that they can be accessed in javascript
//===========================================================================================
$jsonChurches     = json_encode($churches);
$jsonParticipants = json_encode($participants);

//===========================================================================================
// Validate an email address.
// Provide email address (raw input)
// Returns true if the email address has the email
// address format and the domain exists.
//===========================================================================================
function validEmail($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ( $isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")) )
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}
//===========================================================================================
// Debugging dump
//===========================================================================================
function showSysVars()
{
   if (isset($_POST))
   {
   ?>
      <table border=1>
         <TR>
            <TD colspan="2" align="center" bgcolor="Silver"><b>POST</b></TD>
         </TR>
         <TR>
            <TD>Variable</TD>
            <TD>Value</TD>
         </TR>
         <?php
            foreach ($_POST as $varName => $varValue)
            {
               print "<TR>\n";
               print "   <TD>$varName</TD>\n";
               print "   <TD>$varValue&nbsp;</TD>\n";
               print "</TR>\n";
            }
         ?>
      </table>
      <br>
      <?php
   }

   if (isset($_REQUEST))
   {
   ?>
      <table border=1>
         <TR>
            <TD colspan="2" align="center" bgcolor="Silver"><b>REQUEST</b></TD>
         </TR>
         <TR>
            <TD>Variable</TD>
            <TD>Value</TD>
         </TR>
         <?php
            foreach ($_REQUEST as $varName => $varValue)
            {
               print "<TR>\n";
               print "   <TD>$varName</TD>\n";
               print "   <TD>$varValue&nbsp;</TD>\n";
               print "</TR>\n";
            }
         ?>
      </table>
      <br>
      <?php
   }

   if (isset($_SESSION))
   {
   ?>
      <table border=1>
         <TR>
            <TD colspan="2" align="center" bgcolor="Silver"><b>SESSION</b></TD>
         </TR>
         <TR>
            <TD>Variable</TD>
            <TD>Value</TD>
         </TR>
         <?php
            foreach ($_SESSION as $varName => $varValue)
            {
               print "<TR>\n";
               print "   <TD>$varName</TD>\n";
               print "   <TD>$varValue&nbsp;</TD>\n";
               print "</TR>\n";
            }
         ?>
      </table>
      <br>
      <?php
   }

   if (isset($_SERVER))
   {
   ?>
      <table border=1>
         <TR>
            <TD colspan="2" align="center" bgcolor="Silver"><b>SERVER</b></TD>
         </TR>
         <TR>
            <TD>Variable</TD>
            <TD>Value</TD>
         </TR>
         <?php
            foreach ($_SERVER as $varName => $varValue)
            {
               print "<TR>\n";
               print "   <TD>$varName</TD>\n";
               print "   <TD>$varValue&nbsp;</TD>\n";
               print "</TR>\n";
            }
         ?>
      </table>
      <br>
      <?php
   }
   if (isset($_FILES))
   {
      print "<pre>\n";print_r($_FILES);print "\n</pre>\n";
   ?>
      <table border=1>
         <TR>
            <TD colspan="2" align="center" bgcolor="Silver"><b>$_FILES['File']</b></TD>
         </TR>
         <TR>
            <TD>Variable</TD>
            <TD>Value</TD>
         </TR>
         <?php
            foreach ($_FILES['File'] as $varName => $varValue)
            {
               print "<TR>\n";
               print "   <TD>$varName</TD>\n";
               print "   <TD>$varValue&nbsp;</TD>\n";
               print "</TR>\n";
            }
         ?>
      </table>
      <br>
      <?php
   }
}

if ($_POST)
{

//   showSysVars();

   $errorMsg='';
   if (preg_match('/^\s*$/',$SubmitterName) or strlen($SubmitterName) < 3)
   {
      $errorMsg .= "&#8226; Please enter a valid name in the name field<br />\n";
   }

   if ($SubmitterCong == '')
   {
      $errorMsg .= "&#8226; Please select your church name from the selection list<br />\n";
   }

   if ($SubmitterID == '')
   {
      $errorMsg .= "&#8226; Please select the participant ID from the selection list<br />\n";
   }

   if (!validEmail($SubmitterEmail))
   {
      $errorMsg .= "&#8226; Please enter a Valid email address<br />\n";
   }

   if ($errorMsg == '')
   {
      //===================================================================
      // Translate upload error codes to text
      //===================================================================
      $upldMessages = array(
               0=>"Success",
               1=>"The uploaded file is larger than system limit.",
               2=>"The uploaded file is larger than form limit.",
               3=>"The uploaded file was only partially uploaded",
               4=>"No file was uploaded",
               6=>"Missing a temporary folder"
      );

      //===================================================================
      // various variables used to store the file and log
      //===================================================================
      $target_path = "/webroot/l/t/ltcsw001/www/files/";

      $target_name = $target_path . date("mdYGis-").basename( $_FILES['File']['name']);
      $target_log  = $target_path . "uploads.log";

      //===================================================================
      // Debugging dump. Uncomment to use
      //===================================================================
      //print "<br /><hr><pre>";print_r($_FILES) ; print "</pre><br /><hr><br />";
      //print "<br /><hr><pre>";print_r($_SERVER); print "</pre><br /><hr><br />";

      //===================================================================
      // Start by logging the attempt with the details
      //===================================================================
      $fh = fopen($target_log, 'a') or die("can't open file: $target_log");
      fwrite($fh, " Attempting to upload file:  ". $_FILES['File']['name'] .
      " from IP: "                               . $_SERVER['REMOTE_ADDR']          .
      " on "                                     . date("D M j G:i:s T Y")          .
      " Type:"                                   . $_FILES['File']['type'] .
      " Size:"                                   . $_FILES['File']['size'] .
      " Status: "                                . $_FILES['File']['error'].
      "\n");

      //===================================================================
      // Lets check for an error first
      //===================================================================
      if ($_FILES['File']['error'] > 0)
      {
         fwrite($fh, "Error: " . $upldMessages[$_FILES['File']['error']]                  .
         "\n");
         $errorMsg .= "I am Sorry, the following error was encountered: ".$upldMessages[$_FILES['File']['error']];
      }
      //===================================================================
      // A file was successfully uploaded
      //===================================================================
      //================================================================
      // Try to move the file from the temp dir to the perm dir
      //================================================================
      if(move_uploaded_file($_FILES['File']['tmp_name'], $target_name))
      {
         //===================================================================
         // Zero length files are not valuable
         //===================================================================
         if ($_FILES['File']['size'] == 0)
         {
            fwrite($fh," tried to upload empty or missing:  " . $_FILES['File']['name'] .
            "\n");
            $errorMsg = "I am Sorry, the file you are uploading is either empty or not found.";
         }
         else
         {
            if (preg_match('/html$|php$|htm$|shtm$|shtml$|cgi$|pl$|php5$|css$/i',$_FILES['File']['name']))
            {
               $errorMsg = "I am Sorry, you cannot upload that type file.";
            }
            else
            {
               //============================
               // Note upload in log
               //============================
               fwrite($fh, "Successfully uploaded " . $_FILES['File']['name']  .
               "(" . $_FILES['File']['size'] . ")"                  .
               "\n");

               //============================
               // Let user know of success
               //============================
               $errorMsg = "The file ".
                     basename( $_FILES['File']['name']).
                     "has been uploaded";

               //============================
               // Copy file to dropbox
               //============================
               $dropboxDirectory.= '/LTC-'.$thisYear;
               $dropboxDirectory.= '/'.trim($church[$SubmitterCong]);
               $dropboxDirectory.= '/'.trim($participant[$SubmitterID]);
               $dropboxDirectory = trim($dropboxDirectory);
   //          print "[$dropboxDirectory]<br>[$target_name]<br>\n";

               //Login to dropbox
               $dbxClient = new dbx\Client($accessToken, "LTCSW-Submit");

               // Upload the file to dropbox
               $f = fopen($target_name, "rb");
               $result = $dbxClient->uploadFile("$dropboxDirectory/".$_FILES['File']['name'], dbx\WriteMode::force(), $f);
               fclose($f);

               //============================
               // Send note to IS to process
               //============================

               $toWho   =  'cepym1@msn.com';
               $toWho  .= ',blacksv@juno.com';
               $toWho  .= ',paul.lemmons@gmail.com';
               //          $toWho   = 'paul.lemmons@gmail.com';

               $from    = 'MIME-Version: 1.0' . "\r\n";
               $from   .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
               $from   .= "From: LTCSW Seniors Information Upload <webmaster@ltcsw.org>\r\n";

               $subject = "LTC File Uploaded";

               $message = "<html><body>\n"
                         ."Hello, This note is to let you know that $SubmitterName has "
                         ."uploaded the file:<br /><br />\n"
                         ."<a href=\"http://ltcsw.org/files/".basename($target_name)."\">"
                         . $_FILES['File']['name']
                         . "</a><br /><br />\n"
                         ."You can click on the link above to download this file right now. "
                         ."However, The file can also be found any time on the ltc "
                         ."<a href=\"https://www.dropbox.com/login\">dropbox</a> account."
                         ."Where it will be filed by church and participant for easier "
                         ."reconciliation.<br><br>"
                         ."If you need to contact the submitter they can be reached at: "
                         ."$SubmitterEmail <br>"
                         ."<br>"
                         ."Congregation: ".$church[$SubmitterCong]."<br>"
                         ."Participant: ".$participant[$SubmitterID]."<br>"
                         ."Event: SeniorsFiles<br>"
                         ."</body></html>\n";

               $ret= mail($toWho, $subject, $message, $from);

               if ($ret=='' or $ret)
               {
                  $errorMsg = "File successfully uploaded<br>";
               }
               else
               {
                  $errorMsg = "Notification Message to coordinator was not sent<br>";
                  print "<pre>";print_r($ret);print "</pre>";
               }
            }
         }
      }
      //================================================================
      // Move failed for some reason, tell the user and logg it
      //================================================================
      else
      {
         fwrite($fh, "failed upload of " . $_FILES['File']['name'] ."\n");
         $errorMsg = "Internal processing error: failed to move file from temp";
      }

      //===================================================================
      // Don't forget to close the log file
      //===================================================================
      fclose($fh);
   }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-us" lang="en-us" dir="ltr">
   <head>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <meta name="description"
            content="LTCSW LTC Leadership training for christ Tucson Phoenix " />
      <title>LTC Southwest Media Submission</title>
<!-- =========================================================================================== -->
<!--  Steal the stylesheets directly from our Joomla site so that this for looks like it fits in -->
<!-- =========================================================================================== -->
      <link rel="stylesheet"
            href="http://ltcsw.org/j3/templates/siteground-j16-46/css/general.css"
            type="text/css" />
      <link rel="stylesheet"
            href="http://ltcsw.org/j3/templates/siteground-j16-46/css/personal.css"
            type="text/css" />
<!-- =========================================================================================== -->
<!--  Java scrpt to populate select statements that lead to the selection of the file to upload  -->
<!-- =========================================================================================== -->
      <script type='text/javascript'>
      <?php
         print "var churches     = $jsonChurches;\n";
         print "var participants = $jsonParticipants;\n";
       ?>
         function loadChurches()
         {
            var select = document.getElementById("SubmitterID");
            select = document.getElementById("SubmitterCong");

            select.onchange = updateParticipants;

            select.options[0] = new Option('-----Select Congregation-----','');
            for(var i = 0; i < churches.length; i++){
               select.options[i+1] = new Option(churches[i].val,churches[i].id);
            }
         }
         function updateParticipants()
         {
            var churchSelect      = this;
            var churchid          = this.value;
            var participantSelect = document.getElementById("SubmitterID");
            participantSelect.options.length = 0; //delete all options if any present
            participantSelect.options[0] = new Option('-----Select Participant-----','');
            for(var i = 0; i < participants[churchid].length; i++){
               participantSelect.options[i+1] = new Option(participants[churchid][i].val,participants[churchid][i].id);
            }
         }

         var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
         document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));

         try
         {
            var pageTracker = _gat._getTracker("UA-6276861-7");
            pageTracker._trackPageview();
         } catch(err) {}
      </script>
   </head>
<!-- =========================================================================================== -->
<!--  The HTML for the form                                                                      -->
<!-- =========================================================================================== -->
   <body class="page_bg" onload='loadChurches()'>
      <div class="wrapper">
         <header>
            <div class="sitename">
               <!-- <h1><a href="/j3">Leadership Training for Christ Southwest Region</a></h1> -->
            </div>
         </header>

         <div class="nopad" align=center>
            <table class="blog" cellpadding="0" cellspacing="0" style=border:0>
               <tr>
                  <td valign="top">
                     <h2 style="text-align:center" >Upload LTCSW Seniors Files</h2>
                     <?php
                        if ($errorMsg != '')
                        {
                           print "<font size=\"-1\" color=\"yellow\"><b>\n";
                           print "<h3 style=\"text-align:center\">$errorMsg</h3>\n";
                           print "</b></font>\n";
                        }
                     ?>
                     <table class="contentpaneopen">
                        <tr>
                           <td valign="top" colspan="2">
                              <!-- Upload Seniors Data -->
                              <ol>
                                 <li>
                                    Enter your name and Email address so
                                    that we can follow up with you if there
                                    are any issues with the submission.
                                 </li>
                                 <li>
                                    Using the dropdown selection boxes select
                                    your congregation and participant.
                                 </li>
                                 <li>
                                    To select the file to upload from your
                                    computer you will need to
                                    Click Browse/Choose File button first
                                    to select file from your computer
                                 </li>
                                 <li>
                                    Click Upload button to send file to
                                    LTC
                                 </li>
                              </ol>

                              <form enctype="multipart/form-data"
                                    method="POST">
                                 <input type="hidden"
                                        name="MAX_FILE_SIZE"
                                        value="33554432">
                                 <fieldset class="input">

                                    <p>
                                       <label for="SubmitterName">
                                          Your Name
                                       </label>
                                       <input type='text'
                                              name='SubmitterName'
                                              class="inputbox" size="40">
                                    </p>

                                    <p>
                                       <label for="SubmitterEmail">
                                          Email Address
                                       </label>
                                       <input type='text'
                                              name='SubmitterEmail'
                                              class="inputbox" size="40">
                                    </p>

                                    <p>
                                       <label for="SubmitterCong">
                                          Congregation
                                       </label>
                                       <select size="1" name="SubmitterCong" id="SubmitterCong" width="350" style="width: 350px">
                                       </select>
                                    </p>

                                    <p>
                                       <label for="SubmitterID">
                                          Participant Name
                                       </label>
                                       <select size="1" name="SubmitterID" id="SubmitterID" width="350" style="width: 350px">
                                       </select>
                                    </p>

                                    <p>
                                       <label for="File">
                                          Choose the file to upload: &nbsp;
                                          <font size="-2">
                                             (max size=32Mb)
                                          </font>
                                       </label>
                                       <input name="File"
                                              type="file"
                                              class="inputbox"
                                              style=width:340px>
                                    </p>

                                    <p style="text-align: center;">
                                       <input type="submit"
                                          value="Upload File">
                                    </p>
                                 </fieldset>
                              </form>
                           </td>
                        </tr>
                        <!-- end Upload Seniors Data -->
                     </table>
                  </td>
               </tr>
            </table>
         </div>
      </div>
   </body>
</html>