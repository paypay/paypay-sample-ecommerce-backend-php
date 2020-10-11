<?php

function server_info($field){
    $serverInfo = $_SERVER;
    return $serverInfo[$field];
}