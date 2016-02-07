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
<script language="javascript">
   function show_team(TeamNumber)
   {
      var Participant_index = 0;
      // Make sure the element exists before calling it's properties
      while ((document.getElementById("team_"+TeamNumber+"_"+String(Participant_index)) != null))
      {
         // Toggle visibility between none and inline
         if (document.getElementById("team_"+TeamNumber+"_"+String(Participant_index)).style.display == 'none')
         {
            document.getElementById("team_"+TeamNumber+"_"+String(Participant_index)).style.display = 'table-row';
            document.getElementById("team_"+TeamNumber).value = '(close)';
         }
         else
         {
            document.getElementById("team_"+TeamNumber+"_"+String(Participant_index)).style.display = 'none';
            document.getElementById("team_"+TeamNumber).value = '(open)';
         }
         Participant_index++;
      }
   }
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
   <head>
      <title>
         Awards Report
      </title>
   </head>
   <body>
      <h1 align="center">Awards Report</h1>
      <hr>
      <?php
         $ChurchList = ChurchesRegistered();
         $pageBreak='';
         foreach ($ChurchList as $ChurchID=>$ChurchName)
         {
            ?>
         <div <?php print $pageBreak;$pageBreak="style=\"page-break-before:always;\"";?>>
         <table border="0" width="100%">
            <tr>
               <td colspan="5" bgcolor="#C0C0C0">
                     <b>
                        <?php  print $ChurchName;  ?>
                     </b>
                  </div>
               </td>
            </tr>
               <?php
                  $ParticipantList = ActiveParticipants($ChurchID);
                  foreach ($ParticipantList as $ParticipantID=>$ParticipantName)
                  {
                  ?>
                     <tr>
                        <td width="5%" colspan="1">&nbsp;</td>
                        <td width="95%" colspan="4" bgcolor="#F0F0F0"><?php  print $ParticipantName;  ?></td>
                     </tr>
                     <?php

                     $EventList = ParticipantEvents($ParticipantID);
                     foreach ($EventList as $EventID=>$EventName)
                     {
                     ?>
                        <tr>
                           <td width="10%" colspan="2">&nbsp;</td>
                           <td width="40%" colspan="1"><?php  print $EventName;  ?></td>
                           <td width="45%" colspan="1"><?php print ParticipantAward($ParticipantID,$EventID)?></td>
                        </tr>
                     <?php
                     }
                  }
               ?>
         </table>
         <?php
         }
      ?>
   </body>
</html>
