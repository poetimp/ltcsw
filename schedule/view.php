<?php
require_once 'include/general.php';

$ParticipantID = GET('id');
$ChurchID = GET('church');

$where = sprintf('P.ParticipantID = %d AND ChurchID = %d', $ParticipantID, $ChurchID);
$fields = "P.*, (SELECT GROUP_CONCAT(TeamID) FROM {$TeamMembersTable} TM WHERE TM.ParticipantID = P.ParticipantID) as teams";
$Participant = Fetch($ParticipantsTable . ' AS P', $where, $fields);
if (!$Participant) {
    die('Invalid Participant');
}

setParticipantsCookie($ParticipantID, $Participant['LastName'] . ', ' . $Participant['FirstName'], $ChurchID);

$list = explode(',', $Participant['teams']);
array_push($list, $Participant['ParticipantID']);
$listStr = implode(',', $list);
$sql = "SELECT StartTime, EndTime, RoomName, EventName\n"
        . "FROM {$RegistrationTable} AS Registration\n"
        . "LEFT JOIN {$EventScheduleTable} AS Schedule ON Registration.SchedID = Schedule.SchedID\n"
        . "LEFT JOIN {$RoomsTable} AS Room ON Schedule.RoomID = Room.RoomID\n"
        . "LEFT JOIN {$EventsTable} AS Event ON Registration.EventID = Event.EventID\n"
        . "WHERE Registration.ParticipantID IN ({$listStr})\n"
        . "AND Registration.SchedID > 0\n"
        . "ORDER BY StartTime";
        
$events = Query($sql) or die(sqlError());

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $Participant['LastName'] . ', ' . $Participant['FirstName'] ?></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    </head>
    <body>
        <div class="container">
            <h1><?php echo $Participant['LastName'] . ', ' . $Participant['FirstName'] ?> Schedule</h1>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Event</th>
                        <th>Room</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $e) { ?>
                        <tr>
                            <td><?php echo $e['StartTime'] ? (TimeToStr($e['StartTime']) . ' - ' . TimeToStr($e['EndTime'], false)) : 'unknown' ?></td>
                            <td><?php echo $e['EventName'] ?></td>
                            <td><?php echo $e['RoomName'] ? $e['RoomName'] : 'unknown' ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <p>
                <a href="./">Back to Participant Schedule Lookup</a>
            </p>
        </div>
    </body>
</html>


