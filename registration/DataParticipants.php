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
//SELECT P.ParticipantID, P.FirstName, P.LastName, P.Address, P.City, P.State,
//P.Zip, P.Grade, P.Gender, P.Comments, P.ChurchID FROM Participants P;
}
   $filename = "AllParticipants-".date("m-d-Y").".csv";

   header("Content-disposition: attachment; filename=$filename");
   header("Content-type: application/octet-stream");

   $rowCount=0;
   $fp = fopen('php://output', 'w');
   $church_list = ChurchesRegistered();
   foreach ($church_list as $ChurchID=>$ChurchName)
   {
      $ParticipantIDs = ActiveParticipants($ChurchID);
      foreach ($ParticipantIDs as $ParticipantID=>$ParticipantName)
      {
         $results = $db->query("select   ParticipantID,
                                          FirstName,
                                          LastName,
                                          Address,
                                          City,
                                          State,
                                          Zip,
                                          Grade,
                                          Gender,
                                          Comments,
                                          ChurchID,
                                          Case MealTicket
                                             When '3' Then '3 Meals'
                                             When '5' Then '5 Meals'
                                             When 'N' Then 'No Meals'
                                             else          '<error>'
                                          end
                                          MealTicket
                                 from     $ParticipantsTable
                                 where    ParticipantID = $ParticipantID
                                ")
                  or die ("Unable to get Participant Info:" . sqlError());

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
   }
   fclose($fp);
?>