<?php

function startsWith($needle, $haystack)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function endsWith($needle, $haystack)
{
    return substr($haystack, -strlen($needle))===$needle;
}

