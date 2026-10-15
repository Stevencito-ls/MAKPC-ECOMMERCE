<?php
declare(strict_types=1);

use Monolog\Logger as MonologLogger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

/**
 * ==============================================================================
 * MAKPC ENTERPRISES S.A.C. - SERVICIO DE LOGGING CENTRALIZADO (MONOLOG 3)
 * ==============================================================================
 * Provee logging unificado para auditoría, pagos Culqi, errores y transacciones.
 */
class AppLogger
{
    private static ?MonologLogger $logger = null;

    public static function getLogger(string $channel = 'makpc'): MonologLogger
    {
        if (self::$logger === null) {
            $logDir = dirname(__DIR__) . '/storage/logs';
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }

            $logPath = $logDir . '/app.log';
            $handler = new RotatingFileHandler($logPath, 14, MonologLogger::DEBUG);
            
            $output = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
            $formatter = new LineFormatter($output, "Y-m-d H:i:s", true, true);
            $handler->setFormatter($formatter);

            self::$logger = new MonologLogger($channel);
            self::$logger->pushHandler($handler);
        }

        return self::$logger;
    }

    public static function info(string $message, array $context = []): void
    {
        self::getLogger()->info($message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::getLogger()->error($message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::getLogger()->warning($message, $context);
    }

    public static function debug(string $message, array $context = []): void
    {
        self::getLogger()->debug($message, $context);
    }

    public static function critical(string $message, array $context = []): void
    {
        self::getLogger()->critical($message, $context);
    }
}
