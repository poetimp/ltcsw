<?php
include 'include/RegFunctions.php';

if ($Admin != 'Y')
{
   header("refresh: 0; URL=Admin.php");
   die();
}
//==================================================================================
// If we got here via a POSt there will be some variable set
//==================================================================================

$ChurchID = isset($_POST['ChurchID']) ? $_POST['ChurchID'] : "";
$ChurchID = $ChurchID != 0            ? $ChurchID          : "";

//==================================================================================
// Validate form if we were POSTed
//==================================================================================
$errorMsg = '';
if ($ChurchID != "" and isset($_POST['TxType']))
{
   $Amount     = isset($_POST['Amount'])     ? $_POST['Amount']     : "";
   $Annotation = isset($_POST['Annotation']) ? $_POST['Annotation'] : "";

   if ($Amount == "")
   {
      $errorMsg = "Enter an amount";
   }
   else if (!is_numeric($Amount))
   {
      $errorMsg = "Amount must be numeric";
   }
   else if ($Amount <= 0)
   {
      $errorMsg = "Amount must be greater than zero";
   }
   else if ($Annotation == "")
   {
      $errorMsg = "Please describe Transaction";
   }
   else
   {
   //==================================================================================
   // We were POSTed and the form validates. So process the information.
   //==================================================================================
      $errorMsg = "";
      $TxType = $_POST['TxType'];

      if ($TxType != 'Refund')
      {
         $Amount*=-1;
      }

      mysql_query("insert into $MoneyTable
                         (Date,
                          Amount,
                          Annotation,
                          ChurchID)
                   values(now(),
                          $Amount,
                          '$Annotation',
                          $ChurchID)
                 ")
      or die ("Unable to insert into Money table: " . mysql_error());
      WriteToLog(ChurchName($ChurchID) . " account updated by: \$$Amount");
      unset($_POST['Amount']);     unset($Amount);
      unset($_POST['Annotation']); unset($Annotation);
      unset($_POST['TxType']);     unset($TxType);
   }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

   <head>
      <title>Administer Monies</title>
      <h1 align=center>Administer Monies</h1>
   </head>

   <body style="background-color: rgb(217, 217, 255);">
      <form method="post" action=AdminMoney.php>
         <?php
         if ($ChurchID == "")
         {
         //==================================================================================
         // If ChurchID is not set via POSt then offer a list of churches to chose from
         //==================================================================================
            $ChuchList = ChurchesDefined();
            ?>
            <p align=center>
               <select name=ChurchID size=1>
                  <option value=0 selected>----- Select Church -----</option>
                  <?php
                  foreach ($ChuchList as $ChurchID=>$ChurchName)
                  {
                     ?>
                     <option value="<?php  print $ChurchID; ?>"><?php  print $ChurchName; ?></option>
                     <?php
                  }
                  ?>
               </select>
            </p>
            <?php
         }
         else
         {
         //==================================================================================
         // Church was selected so give opportunity to add a transaction
         //==================================================================================
            $ChurchName = ChurchName($ChurchID);
            //==================================================================================
            // First show current transaction history
            //==================================================================================
            ?>
            <h2 align="center">for<br><?php  print "$ChurchName</h2>"; ?>

            <input type=hidden name=ChurchID <?php print "value=$ChurchID"?>>
            <table border=1 width=100%>
               <tr>
                  <td colspan=4 bgcolor=#000000 align=center><font color=#FFFF00>History</font></td>
               </tr>
               <tr>
                  <td bgcolor=#000000><font color=#FFFF00>Date</font></td>
                  <td bgcolor=#000000><font color=#FFFF00>Type</font></td>
                  <td bgcolor=#000000><font color=#FFFF00>Amount</font></td>
                  <td bgcolor=#000000><font color=#FFFF00>Annotation</font></td>
               </tr>
               <?php

               $result = mysql_query("select date_format(Date,'%m-%d-%y') as Date,
                                             Amount,
                                             Annotation
                                      from   $MoneyTable
                                      where  ChurchID=$ChurchID
                                      ")
                         or die ("Unable to get accounting history: ".mysql_error());
               while ($row = mysql_fetch_assoc($result))
               {
                  $Date       = $row['Date'];
                  $Amount     = $row['Amount'];
                  $Annotation = $row['Annotation'];
                  if ($Amount < 0)
                  {
                     $Type   =  "Credit";
                     $Amount *= -1;
                  }
                  else if ($Amount > 0)
                  {
                     $Type    = "Refund";
                  }
                  else
                  {
                     $Type    = "--";
                  }

                  print "<tr>";
                  print "   <td>$Date</td>";
                  print "   <td>$Type</td>";
                  print "   <td>".FormatMoney($Amount)."</td>";
                  print "   <td>$Annotation</td>";
                  print "</tr>";
               }
               ?>
            </table>
            <br>
            <?php
            //==================================================================================
            // Now show the data entry form
            //==================================================================================
            if (isset($_POST['TxType']))
            {
               if ($_POST['TxType'] == 'Credit')
               {
                  $creditChecked = 'checked';
                  $debitChecked  = '';
               }
               else if ($_POST['TxType'] == 'Refund')
               {
                  $creditChecked = '';
                  $debitChecked  = 'checked';
               }
               else
               {
                  $creditChecked = 'checked';
                  $debitChecked  = '';
               }
            }
            else
            {
               $creditChecked = '';
               $debitChecked  = '';
            }

            if (isset($_POST["Amount"]))
               $valueAmount = "value=\"".$_POST["Amount"]."\"";
            else
               $valueAmount = '';

            if (isset($_POST["Annotation"]))
               $valueAnnotation = "value=\"".$_POST["Annotation"]."\"";
            else
               $valueAnnotation = "";

            ?>
            <table align=center border=1>
               <tr>
                  <td width="130">Transaction Type:</td>
                  <td width="80"><input type="radio" value="Credit" name="TxType" <?php print $creditChecked?>>Credit</td>
                  <td width="80"><input type="radio" value="Refund" name="TxType" <?php print $debitChecked?>>Refund</td>
               </tr>
               <tr>
                  <td width="130">Amount:</td>
                  <td width=160 colspan=2><input type="text" name="Amount" size="20" <?php print $valueAmount?>></td>
               </tr>
               <tr>
                  <td width="130">Annotation:</td>
                  <td width=160 colspan=2><input type="text" name="Annotation" size="20" <?php print $valueAnnotation?>></td>
               </tr>
            </table>
            <?php
            //==================================================================================
            // If there is an error message, display it
            //==================================================================================
            if ($errorMsg != "")
            {
               print "<p align=center><font color=#FF0000><b>$errorMsg</b></font></p>";
            }
         }
         ?>
         <p align=center>
            <input type="submit" value="Submit" name="Submit">
         </p>

      </form>
      <?php footer("","")?>
   </body>
</html>