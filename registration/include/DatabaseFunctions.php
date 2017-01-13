<?php
//-----------------------------------------------------------------------------
// Escapes strings/var to make them safe for query
// !notice! automatically adds quotes around strings
//---------------------------------------------------------------------------
function escape($var) {
    global $db;
    return $db->quote($var);
}

//-----------------------------------------------------------------------------
// Abstracted query, return multiple rows
//-----------------------------------------------------------------------------
function Query($sql) {
    global $db;
    $query = $db->query($sql) or die ("Unable to process query: " . sqlError());
    if ($query) {
        $rows = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }
        return $rows;
    } else {
        return false;
    }
}

//-----------------------------------------------------------------------------
// Simple query builder, returns multiple rows
//-----------------------------------------------------------------------------
function FetchAll($table, $where = null, $select = '*', $order = null, $limit = 0) {
    $sql = "SELECT {$select}\nFROM {$table}\n";
    if ($where){
        $sql .= "WHERE {$where}\n";
    }
    if ($order){
        $sql .= "ORDER BY {$order}\n";
    }
    if ($limit){
        $sql .= "LIMIT {$limit}\n";
    }
    return Query($sql);
}

//-----------------------------------------------------------------------------
// Simple query builder, returns single row and automatically limits query
//-----------------------------------------------------------------------------
function Fetch($table, $where = null, $select = '*', $order = null) {
    $rows = FetchAll($table, $where, $select, $order, 1);
    return isset($rows[0]) ? $rows[0] : false;
}