<?php
include 'include/RegFunctions.php';

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

   $awards = mysql_query("SELECT  c.ChurchName,
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
   or die ("Unable to get Awards list:" . mysql_error());
   
   $CurrentChurch = '';
   $CurrentEvent  = '';
   $pageBreak='';
   while ($row = mysql_fetch_assoc($awards))
   {
      $ChurchName = $row['ChurchName'];
      $EventName  = $row['EventName'];
      $Award      = $row['Award'];
      $AwardCount = $row['AwardCount'];
      
      if ($CurrentChurch != $ChurchName)
      {
      	 if ($CurrentChurch != '')
      	    print "</table>\n";
      	 
         print "<h1 align=\"center\" $pageBreak>\n";
         print "   $ChurchName\n";
         print "</h1>\n";
         print "<hr>\n";
         print "<table border=1 width=100%>\n";
         
         $CurrentChurch = $ChurchName;
         $CurrentEvent  = '';
         $pageBreak="style=\"page-break-before:always;\"";
      }
      
      if ($CurrentEvent != $EventName)
      {
         print "   <tr>\n";
         print "      <td align=left colspan=4>\n";
         print "         <b>$EventName</b>\n";
         print "      </td>\n";
         print "   </tr>\n";
         $CurrentEvent  = $EventName;
      }
      print "   <tr>\n";
      print "      <td width=10%>&nbsp;</td>\n";
      print "      <td width=10% align=left>\n";
      print "         $Award\n";
      print "      </td>\n";
      print "      <td width=10% align=left>\n";
      print "         $AwardCount\n";
      print "      </td>\n";
      print "      <td width=70%>&nbsp;</td>\n";
      print "   </tr>\n";
      
   }
?>