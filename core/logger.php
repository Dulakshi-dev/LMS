<?php
// core/Logger.php

use Monolog\Logger as MonoLogger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Processor\WebProcessor;
use Monolog\Formatter\JsonFormatter;

require_once __DIR__ . '/../vendor/autoload.php';

class Logger
{
    private static $logger;

    public static function getLogger(): MonoLogger
    {
        if (!self::$logger) {
            self::$logger = new MonoLogger('library_system');

            $logPath = __DIR__ . '/../logs/library.log';
            $handler = new RotatingFileHandler($logPath, 30, MonoLogger::DEBUG);

            // Use JsonFormatter for clean JSON logs

            $formatter = new JsonFormatter();
$formatter->setJsonPrettyPrint(true);


            $handler->setFormatter($formatter);

            self::$logger->pushHandler($handler);

            self::$logger->pushProcessor(new WebProcessor());

            // Add user agent and IP location info to extra
            self::$logger->pushProcessor(function ($record) {
                $record['extra']['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

                $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
                $location = self::getLocationFromIp($ip);

                $record['extra']['ip_location'] = $location['city'] . ', ' . $location['country'];

                return $record;
            });
        }

        return self::$logger;
    }

    public static function info(string $message, array $context = [])
    {
        self::getLogger()->info($message, $context);
    }

    public static function error(string $message, array $context = [])
    {
        self::getLogger()->error($message, $context);
    }

    public static function warning(string $message, array $context = [])
    {
        self::getLogger()->warning($message, $context);
    }

    public static function getLocationFromIp(string $ip): array
    {
        // Private IP ranges + localhost
        if (
            $ip === '::1' || 
            $ip === '127.0.0.1' || 
            preg_match('/^(10\.|192\.168\.|172\.(1[6-9]|2[0-9]|3[0-1]))/', $ip)
        ) {
            return ['city' => 'Private Network', 'country' => 'Local'];
        }

        $url = "http://ip-api.com/json/{$ip}?fields=status,message,country,city";
        $response = @file_get_contents($url);
        if ($response === false) {
            return ['city' => 'Unknown', 'country' => 'Unknown'];
        }

        $data = json_decode($response, true);
        if ($data['status'] !== 'success') {
            return ['city' => 'Unknown', 'country' => 'Unknown'];
        }

        return [
            'city' => $data['city'] ?? 'Unknown',
            'country' => $data['country'] ?? 'Unknown'
        ];
    }
}
