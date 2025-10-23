<?php
/**
 * Logger-Klasse für strukturiertes Logging
 */

class Logger
{
    private $logPath;
    private $logLevel;
    private $logLevels = [
        'DEBUG' => 0,
        'INFO' => 1,
        'WARNING' => 2,
        'ERROR' => 3
    ];

    public function __construct()
    {
        $this->logPath = defined('LOG_PATH') ? LOG_PATH : __DIR__ . '/../../logs/';
        $this->logLevel = defined('LOG_LEVEL') ? LOG_LEVEL : 'INFO';

        // Erstelle Log-Verzeichnis falls nicht vorhanden
        if (!is_dir($this->logPath)) {
            mkdir($this->logPath, 0755, true);
        }
    }

    /**
     * Log-Eintrag schreiben
     */
    private function write($level, $message, $context = [])
    {
        if (!defined('LOG_ENABLED') || !LOG_ENABLED) {
            return;
        }

        // Prüfe Log-Level
        if ($this->logLevels[$level] < $this->logLevels[$this->logLevel]) {
            return;
        }

        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
        $uri = $_SERVER['REQUEST_URI'] ?? 'N/A';

        $logEntry = [
            'timestamp' => $timestamp,
            'level' => $level,
            'message' => $message,
            'ip' => $ip,
            'uri' => $uri,
            'context' => $context
        ];

        $logLine = json_encode($logEntry, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL;

        // Schreibe in tägliche Log-Datei
        $logFile = $this->logPath . 'app-' . date('Y-m-d') . '.log';
        file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);

        // Bei Errors auch in separate Error-Datei
        if ($level === 'ERROR') {
            $errorFile = $this->logPath . 'errors-' . date('Y-m-d') . '.log';
            file_put_contents($errorFile, $logLine, FILE_APPEND | LOCK_EX);
        }
    }

    /**
     * Debug-Log
     */
    public function debug($message, $context = [])
    {
        $this->write('DEBUG', $message, $context);
    }

    /**
     * Info-Log
     */
    public function info($message, $context = [])
    {
        $this->write('INFO', $message, $context);
    }

    /**
     * Warning-Log
     */
    public function warning($message, $context = [])
    {
        $this->write('WARNING', $message, $context);
    }

    /**
     * Error-Log
     */
    public function error($message, $context = [])
    {
        $this->write('ERROR', $message, $context);
    }

    /**
     * Alte Logs bereinigen
     */
    public function cleanup($days = 30)
    {
        $files = glob($this->logPath . '*.log');
        $now = time();

        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= 60 * 60 * 24 * $days) {
                    unlink($file);
                }
            }
        }
    }
}
