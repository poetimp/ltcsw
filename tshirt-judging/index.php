<?php
$picColumns = 4;
$picWidth   = 300;

$picDir   = "/webroot/l/t/ltcsw001/www/tshirt-judging/shirts-2016";
$picFiles = scandir($picDir);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <title>Judge LTC T-Shirt Designs</title>
   </head>
   <body style="color: #FFFFFF; background: #9900cc">
   <?php
      if (isset($picFiles))
      {
         print "<table border=1 align=center>\n";
         print "   <tr>\n";
         $pics=0;
         foreach ($picFiles as $fileName)
         {
            if (preg_match("/\.gif$|\.jpg$|\.jpeg$|\.png$/i",$fileName))
            {
               if ($pics % $picColumns == 0 and $pics != 0)
               {
                  print "   </tr>\n";
                  print "   <tr>\n";
               }
               $pics++;
               print "      <td width=\"$picWidth\" align=\"center\" valign=\"top\">\n";
               print "         $fileName<br>\n";
               print "         <a href=judge-entry.php?entry=".urlencode("$fileName")." target=_blank>\n";
               print "            <img src=./printImage.php?fileName=".urlencode("$picDir/$fileName")." alt='$fileName' width='$picWidth'>\n";
               print "         </a>\n";
               print "      </td>\n";
            }
         }
         print "   </tr>\n";
         print "</table>\n";
      }
      else
         print "No Pictures found";
   ?>
   </body>
</html>
