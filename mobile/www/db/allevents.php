<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "ltcsw001_ltcreg", "Reg4God", "ltcsw001_ltcreg");

$sql = "SELECT s.StartTime,
               e.EventName,
               r.RoomName
        FROM LTC_PHX_EventSchedule  s,
             LTC_PHX_Events         e,
             LTC_PHX_Rooms          r
        where s.RoomID = r.RoomID
        and   s.EventID = e.EventID
        order by StartTime";
$result = $conn->query($sql);

$outp = "";
while($rs = $result->fetch_array(MYSQLI_ASSOC))
{
    $startTimeDay = substr($rs['StartTime'],0,1);
    $startTimeHH  = substr($rs['StartTime'],1,2);
    $startTimeMM  = substr($rs['StartTime'],3,2);
    if ($startTimeHH > 12)
    {
       $startTimeHH-=12;
       $startTimeAmPm='pm';
    }
    else
       $startTimeAmPm='am';

    $startTime = "$startTimeHH:$startTimeMM $startTimeAmPm";
    $startTimeDay = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday')[$startTimeDay-1];

    $roomName = preg_replace("/-[a-z]$/",'',$rs['RoomName']);

    if ($outp != "") {$outp .= ",\n";}
    $outp .= '{"name":"'   . $rs["EventName"] . '",'."\n";
    $outp .= ' "day":"'    . $startTimeDay    . '",'."\n";
    $outp .= ' "time":"'   . $startTime       . '",'."\n";
    $outp .= ' "room":"'   . $roomName        . '"'."\n}";
}
$outp ='['.$outp.']';
$conn->close();

echo($outp);
?>
