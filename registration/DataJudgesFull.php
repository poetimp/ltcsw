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
   $filename = "AllJudges-".date("m-d-Y").".csv";

   header("Content-disposition: attachment; filename=$filename");
   header("Content-type: application/octet-stream");

   $fp = fopen('php://output', 'w');
   $results = $db->query("select   JudgeID,
                                    FirstName,
                                    LastName,
                                    Address,
                                    City,
                                    State,
                                    Zip,
                                    Phone,
                                    ChurchID,
                                    Email
                           from     $JudgesTable
                           order by JudgeID")
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
