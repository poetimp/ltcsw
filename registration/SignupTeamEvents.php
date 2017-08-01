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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

<head>
<meta http-equiv="Content-Language" content="en-us">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel=stylesheet href="include/registration.css" type="text/css" />

<title>Signup for Team Events</title>

</head>

<body>
<h1 align="center">Signup for Team Events</h1>
      <form action=AddTeam.php>
         <table class='registrationTable'>
            <tr>
               <th style='width: 70px;  text-align: center' colspan=3>Action</th>
               <th style='width: 105px; text-align: left'>Participants</th>
               <th style='text-align: left'>Team Number</th>
               <th style='text-align: left'>Event Name</th>
            </tr>
         <?php
         $ChurchTeams = $db->query("SELECT E.EventName,
                                            E.EventID,
                                            E.MaxSize,
                                            E.MinSize,
                                            T.TeamID
                                     FROM   $EventsTable E,
                                            $TeamsTable  T
                                     WHERE  E.EventID  = T.EventID
                                     AND    T.ChurchID = $ChurchID")
                        or die ("Unable to retrieve Team List:" . sqlError());

         while ($row = $ChurchTeams->fetch(PDO::FETCH_ASSOC))
         {
            $cntResult = $db->query("select count(*) as count
                                      from   $TeamMembersTable
                                      where  TeamID = ".$row['TeamID'])
                         or die ("Can not determine team count:" . sqlError());
            $cntRow = $cntResult->fetch(PDO::FETCH_ASSOC);
            $numMembers = $cntRow['count'];
            ?>
            <tr>
               <td width="70" align="center">[<a href="AdminTeamEvents.php?Action=View<?php  print "&TeamID=".$row['TeamID']."&EventID=".$row['EventID']; ?>">View</a>]</td>
               <td width="70" align="center">[<a href="AdminTeamEvents.php?Action=Update<?php  print "&TeamID=".$row['TeamID']."&EventID=".$row['EventID']; ?>">Update</a>]</td>
               <td width="70" align="center"> [<a href="DelTeam.php?<?php  print "&TeamID=".$row['TeamID']; ?>">Delete</a>]</td>
               <td width="100" align="center"> <?php  print $numMembers?></td>
               <td width="100" align="center"> <?php  print $row['TeamID']?></td>
               <td>
               <?php
                     print $row['EventName'];

                     if ($numMembers > 0)
                     {
                        if ($numMembers < $row['MinSize'])
                        {
                           print "<b><font color=\"#FF0000\"> (Team does not have enough members)</font></b>";
                        }
                        else if ($numMembers > $row['MaxSize'])
                        {
                           print "<b><font color=\"#FF0000\"> (Team has too many members)</font></b>";
                        }
                     }
                  ?>
               </td>
            </tr>
         <?php
         }
         ?>
         </table>
         <p align="center"><input type="submit" value="Add" name="Add"></p>
         <?php footer("","")?>
      </form>
</body>

</html>