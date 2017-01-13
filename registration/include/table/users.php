<?php

function user_by_userid($userid){
    global $UsersTable;
    return Fetch($UsersTable, sprintf('Userid = %s', escape($userid)));
}

function user_by_email($email){
    global $UsersTable;
    return Fetch($UsersTable, sprintf('email = %s', escape($email)));
}

function user_update_password($userid, $password){
    global $UsersTable;
    $hashed = password_hash($password,PASSWORD_DEFAULT);
    $sql = sprintf('UPDATE %s SET password = %s WHERE Userid = %s', $UsersTable, escape($hashed), escape($userid));
    return Query($sql);
}