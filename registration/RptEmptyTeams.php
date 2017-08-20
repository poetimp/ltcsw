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
       <meta http-equiv="Content-Language" content="en-us">
       <title>
          Empty Teams
       </title>
    </head>

    <body>
    <h1 align="center">Empty Teams</h1>
    <hr>
    <?php
         $results = $db->query("select t.TeamID,
                                        e.EventName,
                                        c.ChurchName,
                                        s.StartTime
                                 from   $TeamsTable t left outer join $TeamMembersTable m on t.TeamID = m.TeamID,
                                        $ChurchesTable      c,
                                        $EventsTable        e,
                                        $EventScheduleTable s,
                                        $RegistrationTable  r
                                 where  c.ChurchID      = t.ChurchID
                                 and    e.EventID       = t.EventID
                                 and    r.EventID       = t.EventID
                                 and    r.ParticipantID = t.TeamID
                                 and    r.ChurchID      = t.ChurchID
                                 and    s.SchedID       = r.SchedID
                                 group by t.TeamID,
                                          e.EventName,
                                          c.ChurchName,
                                          s.StartTime
                                 having count(m.ParticipantID) = 0
                                 order by EventName,
                                          StartTime,
                                          ChurchName")
                    or die (":" . sqlError());
         ?>
         <table class='registrationTable' id="table1">
            <tr>
               <td bgcolor="#000000"><font color="#FFFF00">Event Name</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Event Time</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Team Number</font></td>
               <td bgcolor="#000000"><font color="#FFFF00">Church Name</font></td>
            </tr>
         <?php
         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            $TeamID      = $row['TeamID'];
            $EventName   = $row['EventName'];
            $EventTime   = TimeToStr($row['StartTime']);
            $ChurchName  = $row['ChurchName'];
            ?>
            <tr>
               <td><?php  print $EventName; ?></td>
               <td><?php  print $EventTime; ?></td>
               <td><?php  print $TeamID; ?></td>
               <td><?php  print $ChurchName; ?></td>
            </tr>
            <?php
         }
         ?>
         </table>

    </body>

</html>
