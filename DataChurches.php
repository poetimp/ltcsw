<?php
include 'include/RegFunctions.php';
if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <meta http-equiv="Content-Language" content="en-us">
       <title>
          Church Data
       </title>
    </head>

    <body>
    <?php
         print "\"ChurchID\",";
         print "\"ChurchName\",";
         print "\"Address\",";
         print "\"City\",";
         print "\"State\",";
         print "\"Zip\",";
         print "\"Phone\"";
         print "<br>\n";

         $church_list = ChurchesRegistered();
         foreach ($church_list as $ChurchID=>$ChurchName)
         {

            $results = mysql_query("select   ChurchAddr,
                                             ChurchCity,
                                             ChurchState,
                                             ChurchZip,
                                             ChurchPhone
                                    from     $ChurchesTable
                                    where    ChurchID=$ChurchID")
                     or die ("Unable to get Church Info:" . mysql_error());


            $row = mysql_fetch_assoc($results);

            $ChurchAddr  = $row['ChurchAddr'];
            $ChurchCity  = $row['ChurchCity'];
            $ChurchState = $row['ChurchState'];
            $ChurchZip   = $row['ChurchZip'];
            $ChurchPhone = $row['ChurchPhone'];


            print "\"$ChurchID\",";
            print "\"$ChurchName\",";
            print "\"$ChurchAddr\",";
            print "\"$ChurchCity\",";
            print "\"$ChurchState\",";
            print "\"$ChurchZip\",";
            print "\"$ChurchPhone\"";
            print "<br>\n";
         }
         ?>
         </table>
    </body>
</html>