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
         Data Access
      </title>
   </head>
   <body style="background-color: rgb(217, 217, 255);">
      <h1 align=center>
         Data Access
      </h1>

         <table border="1" width="100%">
            <tr>
               <TD align="center" bgcolor="#C0C0C0" colspan="2">
                  <font size="+2">
                     <b>Download Data</b>
                  </font>
               </TD>
            </tr>
            <tr>
               <td width="50%">All active congregations listed along with their address and phone information</td>
               <td width="50%"><a target="_blank" href="DataChurches.php"    >Congregational Information</a></td>
            </tr>
            <tr>
               <td>All Judges listed along with their complete contact information</td>
               <td><a target="_blank" href="DataJudgesFull.php"      >Judges Complete info</a></td>
            </tr>
            <tr>
               <td>Abbreviated List of judges with name, church and location</td>
               <td><a target="_blank" href="DataJudgesShort.php"   >Judges Church, City and State</a></td>
            </tr>
            <tr>
               <td>All participants from all churches with contact information</td>
               <td><a target="_blank" href="DataParticipants.php">Participant's information</a></td>
            </tr>
            <tr>
               <td>List of all teams and their characteristics</td>
               <td><a target="_blank" href="DataTeams.php"       >Team information</a></td>
            </tr>
            <tr>
               <td>All Convention Events along with their participants. Bible Bowl is filterd out</td>
               <td><a target="_blank" href="DataEvents.php"      >Participants and events</a></td>
            </tr>
            <tr>
               <td>All Participants and Event Directors</td>
               <td><a target="_blank" href="DataMealTickets.php" >Participants and Directors Meal Ticket Info</a></td>
            </tr>
         </table>
      <?php footer("","")?>
   </body>
</html>