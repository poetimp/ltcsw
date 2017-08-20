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
       <title>
          Charmers
       </title>
       <meta http-equiv="Content-Language" content="en-us">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link rel=stylesheet href="include/registration.css" type="text/css" />
    </head>

    <body>
    <h1 align="center">Charmers</h1>
    <hr>
    <?php
         $results = $db->query("select    c.charmerID,
                                          c.charmerName,
                                          c.charmerSex,
                                          c.charmerTshirtSize,
                                          c.charmerTshirtNeeded,
                                          c.charmerNeedRoom,
                                          c.charmerAvailibility,
                                          c.charmerEmail,
                                          c.charmerPhone,
                                          n.ChurchName
                                 from     $CharmersTable c,
                                          $ChurchesTable n
                                 where    c.ChurchID=n.ChurchID
                                 Order by ChurchName,charmerName")
                    or die ("Unable to get charmer list:" . sqlError());

         ?>
               <table class='registrationTable'>
                  <tr>
                     <th><b>Church        </b></th>
                     <th><b>Name          </b></th>
                     <th><b>Sex           </b></td>
                     <th><b>Need Room     </b></th>
                     <th><b>Shirt Needed  </b></th>
                     <th><b>Shirt Size    </b></th>
                     <th><b>Phone         </b></th>
                     <th><b>Email         </b></th>
                     <th><b>Availability  </b></th>
                  </tr>
               <?php
               while ($row = $results->fetch(PDO::FETCH_ASSOC))
               {
                  ?>
                  <tr>
                     <td>                           <?php  print $row['ChurchName'];                                       ?></td>
                     <td>                           <?php  print $row['charmerName'];                                      ?></td>
                     <td style='text-align: center'><?php  print $row['charmerSex'];                                       ?></td>
                     <td style='text-align: center'><?php  print $row['charmerNeedRoom']     == 'on' ? "Yes" : "No";       ?></td>
                     <td style='text-align: center'><?php  print $row['charmerTshirtNeeded'] == 'on' ? "Yes" : "No";       ?></td>
                     <td>                           <?php  print $row['charmerTshirtSize'];                                ?></td>
                     <td>                           <?php  print $row['charmerPhone'];                                     ?></td>
                     <td>                           <?php  print $row['charmerEmail'];                                     ?></td>
                     <td>                           <?php  print preg_replace("/\n/","<br>\n",$row['charmerAvailibility']);?></td>
                  </tr>
               <?php
               }
               ?>
               </table>
   </body>
</html>
