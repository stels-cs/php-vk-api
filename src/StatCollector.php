<?php

namespace Hs;

class StatCollector
{
    public static $host = "127.0.0.1";
    public static $port = 2777;
    public static $lastError = null;
    public static $appName = 'bad_app';
    const SumTag = "P";
    const SetTag = "S";
    const MaxTag = "M";
    const MinTag = "I";
    const AvgTag = "A";

    /**
     * @param string $paramName
     * @param string $paramType
     * @param int $value
     * @return string
     */
    public static function write(string $paramName, string $paramType, int $value)
    {
        try {
            $msg = "RL:" . self::$appName . ":$paramName:$paramType:$value";
            $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
            $len = strlen($msg);
            socket_sendto($sock, $msg, $len, 0, self::$host, self::$port);
            socket_close($sock);
            return true;
        } catch (\Exception $e) {
            self::$lastError = $e;
            return $e;
        }
    }

    public static function sum(string $paramName, int $value)
    {
        return self::write($paramName, self::SumTag, $value);
    }

    public static function set(string $paramName, int $value)
    {
        return self::write($paramName, self::SetTag, $value);
    }

    public static function min(string $paramName, int $value)
    {
        return self::write($paramName, self::MinTag, $value);
    }

    public static function max(string $paramName, int $value)
    {
        return self::write($paramName, self::MaxTag, $value);
    }

    public static function avg(string $paramName, int $value)
    {
        return self::write($paramName, self::AvgTag, $value);
    }

}