<?php

class Request
{
    static function post($key, $def = null) {
        return (isset($_POST[$key]))?$_POST[$key]:$def;
    }
    static function get($key, $def = null) {
        return (isset($_GET[$key]))?$_GET[$key]:$def;
    }

}
