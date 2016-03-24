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
// JudgeID,FirstName, LastName, Address, City, State, Zip, Phone, ChurchID, Gender, MaxEvents, Comments, Email
}
   $filename = "AllJudgesSummary-".date("m-d-Y").".csv";

   header("Content-disposition: attachment; filename=$filename");
   header("Content-type: application/octet-stream");

   $fp = fopen('php://output', 'w');
   $results = $db->query("
                            SELECT DISTINCT
                                   j.LastName,
                                   j.FirstName,
                                   c.ChurchName,
                                   c.ChurchState,
                                   c.ChurchCity
                           FROM    $ChurchesTable         c,
                                   $JudgesTable           j,
                                   $JudgeAssignmentsTable a
                           where    c.ChurchId=j.ChurchID
                           and      j.JudgeID=a.JudgeID
                           order by j.LastName
                           ")
            or die ("Unable to get Judge list:" . sqlError());

   $rowCount=0;
   while ($row = $results->fetch(PDO::FETCH_ASSOC))
   {
      if ($rowCount++ == 0)
      {
         foreach ($row as $key => $value)
         {
            $heading[] = $key;
         }
         fputcsv($fp, $heading);
      }
      fputcsv($fp, $row);
   }
   fclose($fp);
?>
