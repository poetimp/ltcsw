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

if (isset($_POST['Add']))
{
   $EventID=$_POST['EventID'];
   if ($EventID == 0)
   {
      $message = "Please select a team event to add";
   }
   else
   {
      header("refresh: 0; URL=AdminTeamEvents.php?Add=Add&EventID=$EventID");
   }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

   <head>
      <title>Add New Team</title>
      <meta http-equiv="Content-Language" content="en-us">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel=stylesheet href="include/registration.css" type="text/css" />

      <h1 align="center">Add New Team</h1>
      <?php
      if (isset($message) and $message != "")
      {
      print "<h3><font color=\"#FF0000\">$message</font></h3>\n";
      }
      ?>
   </head>
   <body>
      <form method="post" action="AddTeam.php">
         <table class='registrationTable'>
            <tr>
               <th style='width: 25%;'>
                  <select size="1" name="EventID">
                     <option value="0">-- Please select event --</option>
                     <?php
                     $TeamList = $db->query("select   EventID,
                                                       EventName
                                              from     $EventsTable
                                              where    TeamEvent = 'Y'
                                              order by EventName")
                     or die ("Unable to get team list:" . sqlError());
                     while ($Row = $TeamList->fetch(PDO::FETCH_ASSOC))
                     {
                       print "<option value=\"".$Row['EventID']."\">".$Row['EventName']."</option>\n";
                     }
                     ?>
                  </select>
               </th>
               <th style='width: 50%; text-align: center'>&lt;--- Select the type ofevent to add, then press Add ---&gt;</th>
               <td style='width: 25%; text-align: center'><input type="submit" value="Add" name="Add" /></td>
            </tr>
         </table>
      </form>
      <?php footer("Return to Team List","SignupTeamEvents.php")?>
   </body>

</html>
