<?php

namespace ChainLogger;

class Parser
{

    public static function jsonEncode($object)
    {
        return json_encode($object);
    }

    public static function jsonDecode($json)
    {
        return json_decode($json);
    }
}