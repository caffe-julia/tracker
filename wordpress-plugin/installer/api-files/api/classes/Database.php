<?php
/**
 * Sichere Datenbank-Klasse mit PDO und Prepared Statements
 */

class Database
{
    private static $instance = null;
    private $connection;
    private $logger;

    private function __construct()
    {
        $this->logger = new Logger();
        $this->connect();
    }

    /**
     * Singleton-Pattern für Datenbankverbindung
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Verbindung zur Datenbank herstellen (mit Retry-Logik)
     */
    private function connect()
    {
        $maxRetries = 3;
        $retryDelay = 1;

        for ($i = 0; $i < $maxRetries; $i++) {
            try {
                $dsn = sprintf(
                    "mysql:host=%s;dbname=%s;charset=%s",
                    DB_HOST,
                    DB_NAME,
                    DB_CHARSET
                );

                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_PERSISTENT => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
                ];

                $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
                $this->logger->info('Datenbankverbindung erfolgreich hergestellt');
                return;

            } catch (PDOException $e) {
                $this->logger->error("Datenbankverbindung fehlgeschlagen (Versuch " . ($i + 1) . "/$maxRetries): " . $e->getMessage());

                if ($i < $maxRetries - 1) {
                    sleep($retryDelay);
                    $retryDelay *= 2; // Exponential Backoff
                } else {
                    throw new Exception('Datenbankverbindung konnte nicht hergestellt werden');
                }
            }
        }
    }

    /**
     * Query mit Prepared Statement ausführen
     */
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            $this->logger->error('Database Query Error: ' . $e->getMessage(), [
                'sql' => $sql,
                'params' => $params
            ]);
            throw new Exception('Datenbankfehler aufgetreten');
        }
    }

    /**
     * SELECT-Query (gibt alle Ergebnisse zurück)
     */
    public function select($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * SELECT-Query (gibt ein einzelnes Ergebnis zurück)
     */
    public function selectOne($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    /**
     * INSERT-Query (gibt Last Insert ID zurück)
     */
    public function insert($sql, $params = [])
    {
        $this->query($sql, $params);
        return $this->connection->lastInsertId();
    }

    /**
     * UPDATE-Query (gibt Anzahl betroffener Rows zurück)
     */
    public function update($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * DELETE-Query (gibt Anzahl gelöschter Rows zurück)
     */
    public function delete($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Transaktion starten
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Transaktion committen
     */
    public function commit()
    {
        return $this->connection->commit();
    }

    /**
     * Transaktion zurückrollen
     */
    public function rollback()
    {
        return $this->connection->rollBack();
    }

    /**
     * PDO-Connection zurückgeben (für komplexe Queries)
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Prüfen ob Verbindung aktiv ist
     */
    public function isConnected()
    {
        try {
            return $this->connection !== null && $this->connection->query('SELECT 1') !== false;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Destruktor - Verbindung schließen
     */
    public function __destruct()
    {
        $this->connection = null;
    }

    /**
     * Verhindere Klonen
     */
    private function __clone() {}

    /**
     * Verhindere Unserialisierung
     */
    public function __wakeup()
    {
        throw new Exception("Unserialisierung nicht erlaubt");
    }
}
