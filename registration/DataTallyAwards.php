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

$filename = "Awards-".date("m-d-Y").".csv";

header("Content-disposition: attachment; filename=$filename");
header("Content-type: application/octet-stream");

$fp = fopen('php://output', 'w');

$header=['ChurchName','ChurchID','ParticipantName','ParticipantID','Grade','EventName','EventID','Award'];
fputcsv($fp, $header);

$row=array();
$ChurchList = ChurchesRegistered();
foreach ($ChurchList as $ChurchID=>$ChurchName)
{
   $ParticipantList = ActiveParticipants($ChurchID);
   foreach ($ParticipantList as $ParticipantID=>$ParticipantName)
   {
      $EventList = ParticipantEvents($ParticipantID);
      foreach ($EventList as $EventID=>$EventName)
      {
         $award=ParticipantAward($ParticipantID,$EventID);
         if (preg_match('/^Team:.*Individual: (.*)/',$award,$matches))
            $award = $matches[1];

         $ParticipantDetails = ParticipantDetails($ParticipantID);

         $row[]=$ChurchName;
         $row[]=$ChurchID;
         $row[]=$ParticipantName;
         $row[]=$ParticipantID;
         $row[]=$ParticipantDetails['Grade'];
         $row[]=$EventName;
         $row[]=$EventID;
         $row[]=$award;
         fputcsv($fp, $row);
         $row = array();
      }
   }
}
fclose($fp);
?>