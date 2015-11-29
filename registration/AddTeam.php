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
      <h1 align="center">Add New Team</h1>
      <?php
      if (isset($message) and $message != "")
      {
      print "<h3><font color=\"#FF0000\">$message</font></h3>\n";
      }
      ?>
   </head>
   <body style="background-color: rgb(217, 217, 255);">
      <form method="post" action="AddTeam.php">
         <table border="1" width="100%">
            <tr>
               <td width="25%" bgcolor="#000000">
                  <select size="1" name="EventID">
                     <option value="0">-- Please select event --</option>
                     <?php
                     $TeamList = $db->query("select   EventID,
                                                       EventName
                                              from     $EventsTable
                                              where    TeamEvent = 'Y'
                                              order by EventName")
                     or die ("Unable to get team list:" . sqlError($db->errorInfo()));
                     while ($Row = $TeamList->fetch(PDO::FETCH_ASSOC))
                     {
                       print "<option value=\"".$Row['EventID']."\">".$Row['EventName']."</option>\n";
                     }
                     ?>
                  </select></td>
               <td width="50%" bgcolor="#000000">
                  <p align="center"><font color="#FFFF00"> &lt;--- Select the type of
                  event to add, then press Add ---&gt; </font>
               </p></td>
               <td width="25%" bgcolor="#000000">
                  <p align="center"><input type="submit" value="Add" name="Add" /></p>
               </td>
            </tr>
         </table>
      </form>
      <?php footer("Return to Team List","SignupTeamEvents.php")?>
   </body>

</html>
