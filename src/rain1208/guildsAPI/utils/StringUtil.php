<?php


namespace rain1208\guildsAPI\utils;


class StringUtil
{
    public static function partialMatches(string $token, array $originals): array
    {
        $result = [];
        if ($token === "") return $result;
        if (sizeof($originals) <= 0) return $result;

        foreach ($originals as $string) {
            if (self::startWithIgnoreCase($string, $token)) {
                $result[] = $string;
            }
        }
        return $result;
    }

    public static function startWithIgnoreCase(string $string, string $prefix): bool
    {
        $sLen = strlen($string);
        $pLen = strlen($prefix);

        if ($sLen < $pLen) {
            return false;
        }

        return (substr($string,0,$pLen) === $prefix);
    }
}