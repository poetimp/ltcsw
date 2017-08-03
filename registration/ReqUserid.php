<?php
//----------------------------------------------------------------------------
// This software is licensed under the MIT license. Use as you wish but give
// and take credit where due.
//
// Author: Paul Lemmons
//----------------------------------------------------------------------------
?>
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
      <meta http-equiv="Content-Language" content="en-us">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel=stylesheet href="include/registration.css" type="text/css" />
   </head>

   <body>
      <h1 align="center">Request Userid/Password</h1>

      <form method="post" action=ReqUserid.php>
         <table class='registrationTable' style='width: 425px; margin-left: auto; margin-right: auto' id="table1">
            <tr>
               <th colspan="2" style='text-align: center'>User Information</th>
            </tr>
            <tr>

               <th>Userid</th>
               <td><input type="text" name="Userid" size="36" value="<?php print $Userid;?>"></td>
            </tr>
            <tr>
               <th>Name</th>
               <td><input type="text" name="Name" size="36" value="<?php print $Name;?>"></td>
            </tr>
            <tr>
               <th>Church</th>
               <td><input type="text" name="Church" size="36" value="<?php print $Church;?>"></td>
            </tr>
            <tr>
               <th>Email Address</th>
               <td><input type="text" name="Email" size="36" value="<?php print $Email;?>"></td>
            </tr>
            <tr>
               <th colspan="2" style='text-align: center'><b>Any Additional Information</b></th>
            </tr>
            <tr>
               <td colspan="2" style='text-align: center'>
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
