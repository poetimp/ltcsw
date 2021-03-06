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
          Event Participation
       </title>
       <meta http-equiv="Content-Language" content="en-us" />
       <meta name="viewport" content="width=device-width, initial-scale=1.0" />
       <link rel="stylesheet" href="include/registration.css" type="text/css" />
    </head>

    <body>
    <h1 align="center">Event Participation</h1>
    <hr />
    <?php
       //--------------------------------------------------------------------
       // First collect a list of all of the events
       //--------------------------------------------------------------------
       $eventList = $db->query("select    SUBSTRING_INDEX(e.EventName,' (',1) as GenericEventName,
                                          CASE e.ConvEvent
                                              WHEN 'C' THEN 'Convention'
                                              WHEN 'P' THEN 'Preconvention'
                                              ELSE          'Other'
                                          END
                                          ConvEvent,
                                          CASE e.TeamEvent
                                             WHEN 'Y' THEN 'Team'
                                             WHEN 'N' THEN 'Individual'
                                             ELSE          'Other'
                                          END
                                          EventType,
                                          count(*) as Count
                                 from     $EventsTable       e,
                                          $RegistrationTable r
                                 where e.EventID=r.EventID
                                 group by GenericEventName,ConvEvent,EventType
                                 order by ConvEvent,EventType,GenericEventName
                                ")
                    or die ("Unable to get event list:" . sqlError());
       $first = 1;
       ?>
       <table class='registrationTable' id="table1" style='width: 50%;margin-left: auto; margin-right: auto'>
          <tr>
             <th>Event Name</th>
             <th>Convention Presence</th>
             <th>Event Type</th>
             <th>Participation</th>
          </tr>
       <?php
       while ($row = $eventList->fetch(PDO::FETCH_ASSOC))
       {
          $EventName = $row['GenericEventName'];
          $ConvEvent = $row['ConvEvent'];
          $EventType = $row['EventType'];
          $count     = $row['Count'];

             //----------------------------------------------------------------
             // Print the vitals for this event as a heading
             //-------------------------------------------------------------------
          ?>
          <tr>
             <td><?php  print $EventName ; ?></td>
             <td><?php  print $ConvEvent;  ?></td>
             <td><?php  print $EventType;  ?></td>
             <td><?php  print $count;      ?></td>
          </tr>
       <?php
       }
       ?>
      </table>

   </body>

</html>
