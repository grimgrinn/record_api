<?php

function jsonAnswer($payload, $status = 'ok', $message = '')
{
    $answer = array();
    $answer['status']  = $status;
    $answer['payload'] = $payload;
    $answer['message'] = $message;

    return json_encode($answer);
}