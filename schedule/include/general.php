<?php

require_once dirname(dirname(__DIR__)) . '/registration/include/config.php';
require_once dirname(dirname(__DIR__)) . '/registration/include/MySql-connect.inc.php';
require_once dirname(dirname(__DIR__)) . '/registration/include/MoreFunctions.php';
require_once dirname(dirname(__DIR__)) . '/registration/include/DatabaseFunctions.php';

function getParticipantsCookie() {
    if(empty($_COOKIE['Participants'])){
        $_COOKIE['Participants'] = '{}';
    }
    return json_decode($_COOKIE['Participants'], true);
}

function setParticipantsCookie($ParticipantID, $name, $ChurchID){
    $participants = getParticipantsCookie();
    $participants[$ParticipantID] = [
        'ParticipantID' => $ParticipantID,
        'name' => $name,
        'ChurchID' => $ChurchID
    ];
    $_COOKIE['Participants'] = json_encode($participants);
    saveParticipantsCookie();
}

function saveParticipantsCookie() {
    setcookie('Participants', $_COOKIE['Participants'], (time() + 60 * 60 * 24 * 30), '/');
}
