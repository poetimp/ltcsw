<?php
//----------------------------------------------------------------------------
// This software is licensed under the MIT license. Use as you wish but give
// and take credit where due.
//
// Author: Paul Lemmons
//----------------------------------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <meta http-equiv="Content-Language" content="en-us" />
       <title>
          Scheduled Events Roster
       </title>
       <meta http-equiv="Content-Language" content="en-us" />
       <meta name="viewport" content="width=device-width, initial-scale=1.0" />
       <link rel="stylesheet" href="include/registration.css" type="text/css" />
    </head>

    <body>

<?php
include 'include/RegFunctions.php';

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

   $awards = $db->query("SELECT  c.ChurchName,
                                  e.EventName,
                                  IFNULL(r.Award,'Not Assigned') Award,
                                  count(*) AwardCount
                         FROM $RegistrationTable r,
                              $ChurchesTable     c,
                              $EventsTable       e
                         where c.ChurchId  = r.ChurchID
                         and   e.EventID   = r.EventID
                         and   e.ConvEvent = 'C'
                         group by ChurchName,EventName,Award
                         order by ChurchName,EventName,field(Award,'Gold','Silver','Bronze','No Award','No Show')
                         ")
   or die ("Unable to get Awards list:" . sqlError());

   $CurrentChurch = '';
   $CurrentEvent  = '';
   $pageBreak='';
   while ($row = $awards->fetch(PDO::FETCH_ASSOC))
   {
      $ChurchName = $row['ChurchName'];
      $EventName  = $row['EventName'];
      $Award      = $row['Award'];
      $AwardCount = $row['AwardCount'];

      if ($CurrentChurch != $ChurchName)
      {
      	 if ($CurrentChurch != '')
      	    print "</table>\n";

         print "<h1 align='center' $pageBreak>\n";
         print "   $ChurchName\n";
         print "</h1>\n";
         print "<hr>\n";
         print "<table class='registrationTable' style='width: 30%;margin-left: auto;margin-right: auto'>\n";

         $CurrentChurch = $ChurchName;
         $CurrentEvent  = '';
         $pageBreak="style=\"page-break-before:always;\"";
      }

      if ($CurrentEvent != $EventName)
      {
         print "   <tr>\n";
         print "      <th style='text-align: left' colspan=4>\n";
         print "         <b>$EventName</b>\n";
         print "      </th>\n";
         print "   </tr>\n";
         $CurrentEvent  = $EventName;
      }
      print "   <tr>\n";
      print "      <td style='width: 10%;'>&nbsp;</td>\n";
      print "      <td style='width: 10%; text-align: left;'>\n";
      print "         $Award\n";
      print "      </td>\n";
      print "      <td style='width: 10%; text-align: left;'>\n";
      print "         $AwardCount\n";
      print "      </td>\n";
      print "      <td style='width: 70%;'>&nbsp;</td>\n";
      print "   </tr>\n";

   }
?>

    </body>

</html>