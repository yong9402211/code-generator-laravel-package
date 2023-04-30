<?php

namespace Yjh94\StandardCodeGenerator\Utils;

class Indent
{
    public static function make($size)
    {
        return str_repeat(" ", $size);
    }
    public static function whitespaceToIndent($string, $size)
    {
        return preg_replace("/^(\s+)/m", self::make($size), $string);
    }
}
