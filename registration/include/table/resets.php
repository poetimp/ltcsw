<?php

function reset_new($Userid)
{

   global $ResetsTable;

   $code      = reset_generate_code();
   $created   = time();
   $anHourAgo = time()-(60*60);

   //-------------------------------------------------------------
   // First some housekeeping. Clear up expired entries and if
   // this person has tried multiple times, only the latest should
   // be valid
   //-------------------------------------------------------------
   $sql = "Delete From $ResetsTable
           where Userid = ".escape($Userid)."
           or Created <  $anHourAgo
           ";
   Query($sql);

   //-------------------------------------------------------------
   // Now enter the massively long random sequence into the table
   //-------------------------------------------------------------
   $sql = "INSERT INTO $ResetsTable
              (Userid, Code, Created)
              VALUES (".escape($Userid).",
                      ".escape($code).",
                      $created
                     )
           ";
   Query($sql);
   return $code;
}

function reset_by_code($code)
{
   global $ResetsTable;
   return Fetch($ResetsTable, "Code = ".escape($code));
}

function reset_generate_code()
{
   return substr(bin2hex(openssl_random_pseudo_bytes(32)), 0, 64);
}

function resets_delete_by_id($id)
{
   global $ResetsTable;
   return Query("DELETE FROM $ResetsTable WHERE Code = ".escape($id));
}
