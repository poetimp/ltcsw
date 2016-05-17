<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "ltcsw001_ltcreg", "Reg4God", "ltcsw001_ltcreg");

$sql = "SELECT contactID,
               contactName
        FROM LTC_PHX_MobileContacts
        order by contactName";
$result = $conn->query($sql);

$outp = "";
while($rs = $result->fetch_array(MYSQLI_ASSOC))
{
   if ($outp != "") {$outp .= ",\n";}
   $outp .= '{"ID":"'   . $rs["contactID"]   . '",'."\n";
   $outp .= ' "name":"' . $rs["contactName"] . '"'."\n}";
}
$outp ='['.$outp.']';
$conn->close();

echo($outp);
?>
