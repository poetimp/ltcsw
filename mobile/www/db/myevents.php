<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$p=isset($_REQUEST['p']) ? $_REQUEST['p'] : '';

$conn = new mysqli("localhost", "ltcsw001_ltcreg", "Reg4God", "ltcsw001_ltcreg");


$sql = "SELECT s.StartTime,
               e.EventName,
               r.RoomName
        FROM LTC_PHX_EventSchedule  s,
             LTC_PHX_Events         e,
             LTC_PHX_Rooms          r,
             LTC_PHX_Registration   g
        where g.ParticipantID='$p'
        and   g.SchedID = s.SchedID
        and   s.RoomID  = r.RoomID
        and   s.EventID = e.EventID
        order by StartTime;
        ";
$result = $conn->query($sql);
while($rs = $result->fetch_array(MYSQLI_ASSOC))
{
   $events[$rs['StartTime']] = ['name' => $rs['EventName'],
                                'room' => $rs['RoomName']
                               ];
}

$sql = "SELECT s.StartTime,
               e.EventName,
               r.RoomName
        FROM LTC_PHX_EventSchedule  s,
             LTC_PHX_Events         e,
             LTC_PHX_Rooms          r,
             LTC_PHX_Registration   g,
             LTC_PHX_TeamMembers    t
       where t.ParticipantID='$p'
       and   g.ParticipantID=t.TeamID
       and   g.SchedID = s.SchedID
       and   s.RoomID  = r.RoomID
       and   s.EventID = e.EventID
       order by StartTime;
       ";
$result = $conn->query($sql);
while($rs = $result->fetch_array(MYSQLI_ASSOC))
{
   $events[$rs['StartTime']] = ['name' => $rs['EventName'],
                                'room' => $rs['RoomName']
                               ];
}
ksort($events);

$outp = "";
foreach ($events as $StartTime => $eventDetails)
{
    $startTimeDay = substr($StartTime,0,1);
    $startTimeHH  = substr($StartTime,1,2);
    $startTimeMM  = substr($StartTime,3,2);
    if ($startTimeHH > 12)
    {
       $startTimeHH-=12;
       $startTimeAmPm='pm';
    }
    else
       $startTimeAmPm='am';

    $startTime = "$startTimeHH:$startTimeMM $startTimeAmPm";
    $startTimeDay = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday')[$startTimeDay-1];

    $roomName  = preg_replace("/-[a-z]$/",'',$eventDetails['room']);
    $eventName = $eventDetails['name'];

    if ($outp != "") {$outp .= ",\n";}

    $outp .= '{"name":"'   . $eventName       . '",'."\n";
    $outp .= ' "day":"'    . $startTimeDay    . '",'."\n";
    $outp .= ' "time":"'   . $startTime       . '",'."\n";
    $outp .= ' "room":"'   . $roomName        . '"'."\n}";
}
$outp ='['.$outp.']';
$conn->close();

echo($outp);
?>
