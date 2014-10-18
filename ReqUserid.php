<?php

$Name         = isset($_POST['Name'])      ? $_POST['Name']      : "";
$Userid       = isset($_POST['Userid'])    ? $_POST['Userid']    : "";
$Church       = isset($_POST['Church'])    ? $_POST['Church']    : "";
$Email        = isset($_POST['Email'])     ? $_POST['Email']     : "";
$Comments     = isset($_POST['Comments'])  ? $_POST['Comments']  : "";

if (isset($_POST['submit']))
{
   $ErrorMsg = "";
   if ($Name == "")
   {
      $ErrorMsg = "Please enter the required field: Name";
   }
   else if ($Church == "")
   {
      $ErrorMsg = "Please enter the required field: Church";
   }
   else if ($Email == "")
   {
      $ErrorMsg = "Please enter the required field: Email";
   }
   else
   {

      $to      = "paullem@fastmail.fm";
      $subject = "LTC Userid Request";
      $body    = "Userid: ". $Userid . "\n" .
                 "Name  : ".$Name    . "\n" .
                 "Church: ".$Church  . "\n" .
                 "Email : ".$Email   . "\n" .
                 "Comments:"         . "\n" .
                 "$Comments"         . "\n";
      $headers = "From: $Email" . "\r\n" .
                 "Reply-To: $Email" . "\r\n" .
                 "X-Mailer: PHP/" . phpversion();

      if (mail($to, $subject, $body, $headers))
      {
         $ErrorMsg  = "Request submitted.<br>You can expect a response in less than 24 hours<br>";
         $ErrorMsg .= "<a href=/>Click Here to return to the LTC Home page</a><br>";
      }
      else
      {
         $ErrorMsg = "Message delivery failed. Please Call Paul Lemmons at 520-722-2642";
      }
   }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

   <head>
      <title>Request Userid/Password</title>
   </head>

   <body style="background-color: rgb(217, 217, 255);">
      <h1 align="center">Request Userid/Password</h1>

      <form method="post" action=ReqUserid.php>
         <table align="center" border="1" width="425px" id="table1">
            <tr>
               <td colspan="2" bgcolor="#000000">
               <p align="center"><font color="#FFFF00">
               <span style="background-color: #000000">User Information</span></font></td>
            </tr>
            <tr>

               <td>Userid</td>
               <td><input type="text" name="Userid" size="36" value="<?php print $Userid;?>"></td>
            </tr>
            <tr>
               <td>Name</td>
               <td><input type="text" name="Name" size="36" value="<?php print $Name;?>"></td>
            </tr>
            <tr>
               <td>Church</td>
               <td><input type="text" name="Church" size="36" value="<?php print $Church;?>"></td>
            </tr>
            <tr>
               <td>Email Address</td>
               <td><input type="text" name="Email" size="36" value="<?php print $Email;?>"></td>
            </tr>
            <tr>
               <td colspan="2" align="center"><b>Any Additional Information</b></td>
            </tr>
            <tr>
               <td colspan="2" align="center">
                  <textarea name="Comments" rows="10" cols="50"><?php print $Comments;?></textarea>
               </td>
            </tr>
         </table>
         <p align="center">
            <?php
               if ($ErrorMsg != "")
               {
                  print "<font color=red>\n";
                  print "<b>$ErrorMsg</b><br>\n";
                  print "</font>\n";
               }
            ?>
            <input type="submit" value="Submit Request" name="submit">
         </p>
      </form>
   </body>

</html>
