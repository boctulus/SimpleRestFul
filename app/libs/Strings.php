<?php

namespace simplerest\libs;

class Strings {
    static function startsWith($needle, $haystack)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    static function endsWith($needle, $haystack)
    {
        return substr($haystack, -strlen($needle))===$needle;
    }
}


