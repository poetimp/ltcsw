<?php
//----------------------------------------------------------------------------
// This software is licensed under the MIT license. Use as you wish but give
// and take credit where due.
//
// Author: Paul Lemmons
//----------------------------------------------------------------------------
?>
<?php
include 'include/RegFunctions.php';

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

   <head>
      <title>
         Enrollment by Congregation
      </title>
   </head>
   <body bgcolor="White">
      <h1 align="center">Enrollment by Congregation</h1>
      <hr>
      <table class='registrationTable' border="0" width="40%">
   <?php
      //=================================================================================================
      // For Each Church see how many kids are actively registered
      //=================================================================================================
      $total = 0;
      $ChuchList = ChurchesRegistered();
      foreach ($ChuchList as $ChurchID=>$ChurchName)
      {
         $ChurchCount = count(ActiveParticipants($ChurchID));
         $total += $ChurchCount;
         ?>
         <tr>
            <td width=70%><?php  print $ChurchName;  ?>&nbsp;</td>
            <td width=30% align=right><?php  print $ChurchCount; ?>&nbsp;</td>
         </tr>
         <?php
      }
      ?>
      </table>
      <hr>
      <table class='registrationTable' width=40%>
         <tr>
            <td width=70%>Total</td>
            <td width=30% align=right><?php  print $total; ?>&nbsp;</td>
         </tr>
      </table>
   </body>
</html>