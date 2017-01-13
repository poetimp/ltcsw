<?php

function reset_new($Userid) {
    global $ResetsTable;
    $code = reset_generate_code();
    $created = time();
    $sql = sprintf('INSERT INTO %s (Userid, code, created) VALUES (%s, %s, %s)',
            $ResetsTable,
            escape($Userid),
            escape($code),
            $created);
    Query($sql);
    return $code;
}

function reset_by_code($code){
    global $ResetsTable;
    return Fetch($ResetsTable, sprintf('code = %s', escape($code)));
}

function reset_generate_code() {
    return substr(bin2hex(openssl_random_pseudo_bytes(32)), 0, 64);
}

function resets_delete_by_id($id) {
    global $ResetsTable;
    return Query(sprintf('DELETE FROM %s WHERE id = %s', $ResetsTable, escape($id)));
}
