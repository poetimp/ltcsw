<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$u=isset($_REQUEST['u']) ? $_REQUEST['u'] : '';
$p=isset($_REQUEST['p']) ? $_REQUEST['p'] : '';

if ($p != '' and $u != '')
{
   $conn = new mysqli("localhost", "ltcsw001_ltcreg", "Reg4God", "ltcsw001_ltcreg");

   $sql = "SELECT count(*) as found
           FROM LTC_PHX_Participants
           where ParticipantID = '$p'
           and   LastName      = '$u'";
   $result = $conn->query($sql);

   $rs   = $result->fetch_array(MYSQLI_ASSOC);
   $outp = '{"loggedIn":"'   . $rs["found"] . '"}';
   $conn->close();
}
else
   $outp = '[{"loggedIn": "0"}]"';

echo($outp);
?>
