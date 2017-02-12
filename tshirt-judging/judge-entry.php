<?php
//----------------------------------------------------------------------------
// This software is licensed under the MIT license. Use as you wish but give
// and take credit where due.
//
// Author: Paul Lemmons
//----------------------------------------------------------------------------
?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="UTF-8">
      <title>LTC T-Shirt Design Judging</title>
   </head>
   <body>
      <?php
         $ShirtID    = isset($_POST['ShirtID'])    ? $_POST['ShirtID']    : "";
         $Judge      = isset($_POST['Judge'])      ? $_POST['Judge']      : "";
         $Tech       = isset($_POST['Tech'])       ? $_POST['Tech']       : "";
         $Transfer   = isset($_POST['Transfer'])   ? $_POST['Transfer']   : "";
         $Theme      = isset($_POST['Theme'])      ? $_POST['Theme']      : "";
         $Creative   = isset($_POST['Creative'])   ? $_POST['Creative']   : "";
         $Wearable   = isset($_POST['Wearable'])   ? $_POST['Wearable']   : "";
         $Appeal     = isset($_POST['Appeal'])     ? $_POST['Appeal']     : "";
         $Top3       = isset($_POST['Top3'])       ? $_POST['Top3']       : "";

         $DoneWell   = isset($_POST['DoneWell'])   ? $_POST['DoneWell']   : "";
         $Focus      = isset($_POST['Focus'])      ? $_POST['Focus']      : "";
         $Impression = isset($_POST['Impression']) ? $_POST['Impression'] : "";

         $picDir     = "/webroot/l/t/ltcsw001/www/tshirt-judging/shirts-2017";
         $emailToWho = 'paul@ltcsw.org';
         $subject    = 'T-Shirt Judging Submission for: ';
         $imageName =isset($_REQUEST['entry'])    ? $_REQUEST['entry']    : "";

         if ($imageName === '')
         {
            die("Invalid call to page. Missing entry number");
         }

         preg_match("/(\d+)+-.*/",$imageName,$matches);
         $entryNumber = $matches[1];

         if (($handle = fopen("$picDir/crossRef.csv", "r")) !== FALSE)
         {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE and !isset($Church))
            {
               if ($data[0] == $entryNumber)
               {
                  $Church     = $data[1];
                  $Grade      = $data[2];
                  $Artist     = $data[3];
                  $Coversheet = $data[4];
               }
            }
            fclose($handle);
         }
         else
         {
            die("Could not open cross reference file.");
         }
         if (!isset($Church))
         {
            die("Invalid entry number passed to page.");
         }
         $theFormTop='
         <table>
         ';
         $theFormLeft='
            <tr>
               <td width="49%" valign="top">
                  <table border="1" width="100%">
                     <tr>
                        <td colspan="2" align="center" bgcolor="silver">
                           <b>T-Shirt Design Contest<br>
                           Board Evaluation Form</b>
                        </td>
                     </tr>
                     <tr>
                        <td colspan="2">
                           T-Shirt ID Number: '.$imageName.'
                        </td>
                     </tr>
                     <tr>
                        <td colspan="2">
                           Judge Name: <input type="text" maxlength="25" name="Judge" id="Judge" value="'.$Judge.'">&nbsp;
                        </td>
                     </tr>
                     <tr>
                        <td>
                           <b>Rating</b>
                        </td>
                        <td>
                           <b>Evaluation Criteria</b>
                        </td>
                     </tr>
                     <tr>
                        <td valign="center">
                           <input type="text" maxlength="25" name="Tech" id="Tech" value="'.$Tech.'">&nbsp;
                        </td>
                        <td>
                           <b>Technical Review</b> – How well did the submission
                           meet the requirements as laid out in the Rules?<br>
                           <i><b>Very Well - OK or Does Not</b></i>
                        </td>
                     </tr>
                     <tr>
                        <td valign="center">
                           <input type="text" maxlength="25" name="Transfer" id="Transfer" value="'.$Transfer.'">&nbsp;
                        </td>
                        <td>
                           <b>Transfer</b> – How well will this design
                           transfer to a T-shirt? Level and
                           consistency of detail and simplicity<br>
                           <i><b>Very Well - OK or Does Not</b></i>
                        </td>
                     </tr>
                     <tr>
                        <td valign="center">
                           <input type="text" maxlength="25" name="Theme" id="Theme" value="'.$Theme.'">&nbsp;
                        </td>
                        <td>
                           <b>Theme</b> – How well is the <i>current LTC theme</i>
                           depicted through the entry itself? Is the message of the
                           artist clearly and easily conveyed?<br>
                           <i><b>Very Well - OK or Does Not</b></i>
                        </td>
                     </tr>
                     <tr>
                        <td valign="center">
                           <input type="text" maxlength="25" name="Creative" id="Creative" value="'.$Creative.'">&nbsp;
                        </td>
                        <td>
                           <b>Creativity and level of effort</b> – How apparent
                           is it that the participant spend time
                           and effort on developing a creative
                           submission vs. quickly drawing the first
                           idea that came to mind?<br>
                           <i><b>Very Apparent - Not Sure or Clearly not</b></i>
                        </td>
                     </tr>
                     <tr>
                        <td valign="center">
                           <input type="text" maxlength="25" name="Wearable" id="Wearable" value="'.$Wearable.'">&nbsp;
                        </td>
                        <td>
                           <b>Wearability</b> – How likely is it likely that the
                           t-shirt be worn after convention?<br>
                           <i><b>Very Likely, Maybe or Probably Not</b></i>
                        </td>
                     </tr>
                     <tr>
                        <td valign="center">
                           <input type="text" maxlength="25" name="Appeal" id="Appeal" value="'.$Appeal.'">&nbsp;
                        </td>
                        <td>
                           <b>Appeal</b> – How well does the design
                           and message appeal to you? Is it
                           consistent with the overall mission and
                           purpose of LTCSW.<br>
                           <i><b>Very Well - OK or Does Not</b></i>
                        </td>
                     </tr>
                     <tr>
                        <td valign="center">
                           <input type="text" maxlength="25" name="Top3" id="Top3" value="'.$Top3.'">&nbsp;
                        </td>
                        <td>
                           <b>Rank</b> – Is this one of your top three choices from
                           all of the entries and if so, is it <br>
                           <i><b>Not, 1, 2 or 3?</b></i>
                        </td>
                     </tr>
                  </table>
               </td>
               ';
         $theFormRight='
               <td width="2%">
                  &nbsp;
               </td>
               <td width="49%" valign="top">
                  <table border="1" width="100%">
                     <tr>
                        <td align="center" bgcolor="silver">
                           <b>T-Shirt Design Contest<br>
                           Participant’s Take Home Judge’s Critique</b>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           Artist Name:  <input type="hidden" name="Artist" id="Artist" value="'.$Artist.'"><br>
                           Congregation: <input type="hidden" name="Church" id="Church" value="'.$Church.'"><br>
                           Grade:        <input type="hidden" name="Grade"  id="Grade"  value="'.$Grade.'"><br>
                        </td>
                     </tr>
                     <tr>
                        <td align="center">
                           <b>Comments</b>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           <b>This was done well:</b>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           <textarea maxlength="1000" rows="8" cols="70" name="DoneWell" id="DoneWell">'.$DoneWell.'</textarea>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           <b>One area to focus on next time:</b>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           <textarea maxlength="1000" rows="8" cols="70" name="Focus" id="Focus">'.$Focus.'</textarea>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           <b>Overall Impression:</b>
                        </td>
                     </tr>
                     <tr>
                        <td>
                           <textarea maxlength="1000" rows="8" cols="70" name="Impression" id="Impression">'.$Impression.'</textarea>
                        </td>
                     </tr>
                  </table>
               </td>
            </tr>
            ';
         $theFormBottom='
            <tr>
               <td colspan="3" align="center">
                  <input type="submit" name="submit" id="submit" value="Submit">
               </td>
            </tr>
         </table>';

         $theForm = $theFormTop.$theFormLeft.$theFormRight.$theFormBottom;
         if (isset($_POST['submit']))
         {

            $errormsg = '';
            if ($Judge == '')      $errormsg .= "Missing Judge name<br>";
            if ($Tech  == '')      $errormsg .= "Missing Technical Review<br>";
            if ($Transfer == '')   $errormsg .= "Missing Transferability evaluation<br>";
            if ($Theme == '')      $errormsg .= "Missing adherence to Theme evaluation<br>";
            if ($Creative == '')   $errormsg .= "Missing creativity evaluation<br>";
            if ($Wearable == '')   $errormsg .= "Missing wearability evaluation<br>";
            if ($Appeal == '')     $errormsg .= "Missing Appeal evaluation<br>";
            if ($Top3 == '')       $errormsg .= "Missing is it a top three<br>";
            if ($DoneWell == '')   $errormsg .= "Missing what the artist did well<br>";
            if ($Focus == '')      $errormsg .= "Missing what artist should focus on<br>";
            if ($Impression == '') $errormsg .= "Missing your overall impression<br>";

            if ($errormsg == '')
            {
               $DoneWell   = preg_replace("/\n/","<br>\n",$DoneWell);
               $Focus      = preg_replace("/\n/","<br>\n",$Focus);
               $Impression = preg_replace("/\n/","<br>\n",$Impression);

               $headers  = 'MIME-Version: 1.0' . "\r\n";
               $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
               $headers .= "X-Mailer: PHP/" . phpversion();

               $inputValue=function($var)
                           {eval("global \$".$var[1].";");
                            eval("\$x = \$".$var[1].";");
                            return $x;
                           };

               $message = preg_replace('/<input.*type="submit"[^>]*>/','',$theForm);
               $message = preg_replace_callback('/<input.*type="text".*name="([^"]+)"[^>]*>/',$inputValue,$message);
               $message = preg_replace_callback('/<input.*type="hidden".*name="([^"]+)"[^>]*>/',$inputValue,$message);
               $message = preg_replace_callback('/<textarea.*name="([^"]+)"[^>]*>[^<]*<\/textarea>/',$inputValue,$message);

   //            print $message;
               $subject .= $imageName;
               if (mail($emailToWho, $subject, $message, $headers))
               {
                  $message = preg_replace('/<input.*type="submit"[^>]*>/','',$theFormTop.$theFormRight.$theFormBottom);
                  $message = preg_replace_callback('/<input.*type="text".*name="([^"]+)"[^>]*>/',$inputValue,$message);
                  $message = preg_replace_callback('/<input.*type="hidden".*name="([^"]+)"[^>]*>/',$inputValue,$message);
                  $message = preg_replace_callback('/<textarea.*name="([^"]+)"[^>]*>[^<]*<\/textarea>/',$inputValue,$message);
                                    $subject .= "(Right Side)";
                  mail($emailToWho, $subject, $message, $headers);

                  if (($JudgedInfo = fopen("$picDir/$imageName.csv", "a")) !== FALSE)
                  {
                     $now=date("Y-m-d H:i:s");
                     fputs($JudgedInfo,"$Judge,$now\n");
                     fclose($JudgedInfo);
                  }
                  ?>
                  Thank You! Your message has been successfully sent.<br>
                  You may close this window.
               <?php
               }
               else
               {
               ?>
                  Your message has not been successfully sent. <br>
                  Please click on your back button and try again.<br>
               <?php
               }
            }
         }
         if (!isset($_POST['submit']) or $errormsg != '')
         {?>
            <p align="center">
               Please fill out both sides of the form and when complete click on submit.
               You can leave the fields on the right side at the top blank.<br>
               The artwork is at the bottom of the page for reference.
            </p>
            <?php
            if ($errormsg != '')
            {
               print "<font color=red><b>".$errormsg."</b></font>";
            }
            ?>
            <form method="post">
               <?php print $theForm;?>
            </form>
            <br><br>
            <center>
               <img src="./printImage.php?fileName=<?php print urlencode("$picDir/$imageName");?>" width="576px">
            </center>
         <?php
         }
      ?>
   </body>
</html>


