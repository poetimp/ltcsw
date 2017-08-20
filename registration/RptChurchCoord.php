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


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

    <head>
       <meta http-equiv="Content-Language" content="en-us">
       <title>
          Church Coordinators
       </title>
       <meta http-equiv="Content-Language" content="en-us">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link rel=stylesheet href="include/registration.css" type="text/css" />
    </head>

    <body>
    <h1 align="center">Church Coordinators</h1>
    <hr>
    <?php
         $results = $db->query("select   CoordName,
                                          CoordPhone,
                                          CoordEmail,
                                          CoordAddr,
                                          CoordCity,
                                          CoordState,
                                          CoordZip,
                                          ChurchName
                                 from     $ChurchesTable
                                 order by ChurchName,CoordName")
                     or die ("Unable to obtain coordinator list:" . sqlError());
         $first = 1;
         ?>
         <table class='registrationTable' id="table1">
         <?php
         while ($row = $results->fetch(PDO::FETCH_ASSOC))
         {
            $CoordName    = $row['CoordName'];
            $CoordPhone   = $row['CoordPhone'];
            $CoordEmail   = $row['CoordEmail'];
            $CoordAddr    = $row['CoordAddr'];
            $CoordCity    = $row['CoordCity'];
            $CoordState   = $row['CoordState'];
            $CoordZip     = $row['CoordZip'];
            $ChurchName   = $row['ChurchName'];

            if ($first == 0)
            {
               ?>
               <tr>
                  <td colspan="4">&nbsp;</td>
               </tr>
               <?php
            }
            else
            {
               $first = 0;
            }
            ?>
            <tr>
               <th colspan='3'><?php  print $CoordName;  ?></th>
            </tr>
            <tr>
               <td><?php  print $CoordPhone; ?></td>
               <td><?php  print $CoordEmail; ?></td>
               <td><?php  print $ChurchName; ?></td>
            </tr>
            <tr>
               <td><?php  print $CoordAddr; ?></td>
               <td><?php  print $CoordCity; ?></td>
               <td><?php  print "$CoordState, $CoordZip"; ?></td>
            </tr>
            <?php
         }
         ?>
         </table>

    </body>

</html>
