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

if (isset($_REQUEST['Admin']) and $Admin == 'Y')
{
   $AdminReport = 1;
}
else
{
   $AdminReport = 0;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <title>
          Judges List
       </title>
       <meta http-equiv="Content-Language" content="en-us">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link rel=stylesheet href="include/registration.css" type="text/css" />
    </head>

    <body>
    <?php
      $pageBreak='';

      function PrintRoster($ChurchID,$ChurchName)
      {
         global $EventScheduleTable;
         global $EventsTable;
         global $JudgeAssignmentsTable;
         global $RoomsTable;
         global $JudgesTable;
         global $allTimes;
         global $allRooms;
         global $pageBreak;
         global $db;

         $result = $db->query("select   count(distinct a.JudgeID,
                                                       j.FirstName,
                                                       j.LastName) count
                                from    $JudgeAssignmentsTable a,
                                        $JudgesTable           j
                                where   a.JudgeID     = j.JudgeID
                                and     a.ChurchID    = $ChurchID
                              ")
         or die ("Unable to obtain Judge List:" . sqlError());
         $row = $result->fetch(PDO::FETCH_ASSOC);
         $numRows = $row['count'];

         ?>
         <h1 align="center" <?php print $pageBreak;$pageBreak="style=\"page-break-before:always;\"";?>="<?php print $pageBreak;$pageBreak="style=\"page-break-before:always;\"";?>">
            <?php print $ChurchName; ?>
         </h1>
         <hr />
         <?php
         if ($numRows > 0)
         {
            $result = $db->query("select   distinct
                                           a.JudgeID,
                                           j.FirstName,
                                           j.LastName
                                   from    $JudgeAssignmentsTable a,
                                           $JudgesTable           j
                                   where   a.JudgeID     = j.JudgeID
                                   and     a.ChurchID    = $ChurchID
                                   order by j.LastName
                                 ")
            or die ("Unable to obtain Judge List:" . sqlError());
            ?>
            <table class='registrationTable'>
            <?php

            while ($row = $result->fetch(PDO::FETCH_ASSOC))
            {
               $FirstName = $row['FirstName'];
               $LastName  = $row['LastName'];
               $JudgeID   = $row['JudgeID'];
               ?>
               <tr>
                  <th style='width: 10%' colspan="4"><?php print "$LastName, $FirstName"?></th>
               </tr>
               <?php

               $details = $db->query("SELECT    r.RoomName,
                                                s.StartTime,
                                                e.EventName
                                       FROM     $JudgeAssignmentsTable  a,
                                                $RoomsTable             r,
                                                $EventScheduleTable     s,
                                                $EventsTable            e
                                       Where    a.JudgeID = $JudgeID
                                       and      a.RoomID  = r.RoomID
                                       and      s.SchedID = a.SchedID
                                       and      s.RoomID  = a.RoomID
                                       and      e.EventID = s.EventID
                                       order by s.StartTime
                                 ")
               or die ("Unable to obtain Judging Details:" . sqlError());
               while ($judgeDtails = $details->fetch(PDO::FETCH_ASSOC))
               {
                  $RoomName  = $judgeDtails['RoomName'];
                  $SchedTime = TimeToStr($judgeDtails['StartTime']);
                  $EventName = $judgeDtails['EventName'];
                  ?>
                  <tr>
                     <td style='width: 10%'>&nbsp;</td>
                     <td><?php print $SchedTime?></td>
                     <td><?php print $RoomName?></td>
                     <td><?php print $EventName?></td>
                  </tr>
                  <?php
               }
            }
            ?>
            </table>
            <?php
         }
         else
         {
            ?>
               <p align="center"><i>No Judges Assigned</i></p>
            <?php
         }
      }

      if ($AdminReport)
      {
         $ChuchList = ChurchesRegistered();
         if (count($ChuchList) > 0)
         {
            foreach ($ChuchList as $ChurchID=>$ChurchName)
            {
               PrintRoster($ChurchID,$ChurchName);
            }
         }
         else
         {
            ?>
               <center>
                  <h1>No churches with participating registrants have been defined</h1>
                  <h2>Judges report is empty</h2>
               </center>
            <?php
         }
      }
      else
      {
         PrintRoster($ChurchID,ChurchName($ChurchID));
      }
      ?>
   </body>

</html>
