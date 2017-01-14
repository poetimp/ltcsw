<?php

function reset_new($Userid)
{
   //TODO: Delete any old entries
   //TODO: Delete any entries for this user if they exist

   global $ResetsTable;
   $code    = reset_generate_code();
   $created = time();
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
