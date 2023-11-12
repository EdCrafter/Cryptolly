<?php

class DataProcessor{
    public static function htmlEntitiesRecursive($input) {
        if (is_array($input)) {
            return array_map(array(__CLASS__, 'htmlEntitiesRecursive'), $input);
        } else {
            return htmlentities($input, ENT_QUOTES, 'UTF-8');
        }
    }
    public static function htmlSpecialCharsRecursive($input) {
        if (is_array($input)) {
            return array_map(array(__CLASS__, 'htmlSpecialCharsRecursive'), $input);
        } else {
            return htmlspecialChars($input, ENT_QUOTES, 'UTF-8');
        }
    }
    public static function htmlSpecialCharsDecodeRecursive($input) {
        if (is_array($input)) {
            return array_map(array(__CLASS__, 'htmlSpecialCharsDecodeRecursive'), $input);
        } else {
            return htmlspecialChars_decode($input, ENT_QUOTES, 'UTF-8');
        }
    }
}

?>