<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$p=isset($_REQUEST['p']) ? $_REQUEST['p'] : '';

$conn = new mysqli("localhost", "ltcsw001_ltcreg", "Reg4God", "ltcsw001_ltcreg");


$sql = "SELECT *
        FROM LTC_PHX_MobileContacts
        where contactID='$p'
        ";

$result = $conn->query($sql);
$rs = $result->fetch_array(MYSQLI_ASSOC);
$conn->close();

$outp  = '{"ID": "'.$rs['contactID'].'",'."\n";
$outp .= '"name":"'   . $rs['contactName']   . '",'."\n";
$outp .= '"Phone":"'  . $rs['contactPhone']  . '",'."\n";
$outp .= '"Email":"'  . $rs['contactEmail']  . '",'."\n";
$outp .= '"Duties":"' . $rs['contactDuties'] . '",'."\n";
$outp .= '"Bio":"'    . $rs['contactBio']    . '"' ."\n";
$outp .= '}'."\n";
echo($outp);
?>
