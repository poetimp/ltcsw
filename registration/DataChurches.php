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
   $filename = "Churches-".date("m-d-Y").".csv";

   header("Content-disposition: attachment; filename=$filename");
   header("Content-type: application/octet-stream");

   $fp = fopen('php://output', 'w');
   $rowCount=0;
   $church_list = ChurchesRegistered();
   foreach ($church_list as $ChurchID=>$ChurchName)
   {
      $results = $db->query("select   ChurchAddr,
                                      ChurchCity,
                                      ChurchState,
                                      ChurchZip,
                                      ChurchPhone
                             from     $ChurchesTable
                             where    ChurchID=$ChurchID")
                 or die ("Unable to get Church Info:" . sqlError());

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
   }
   fclose($fp);
?>
