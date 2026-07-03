<?php
/**
 * Database Connection – PDO Singleton
 * Replaces the MongoDB/Mongoose connection from Node.js config/db.js
 */

require_once __DIR__ . '/config.php';

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                // In production, never expose raw error to the browser
                if (APP_ENV === 'development') {
                    die(json_encode([
                        'success' => false,
                        'message' => 'Database connection failed: ' . $e->getMessage()
                    ]));
                } else {
                    die(json_encode([
                        'success' => false,
                        'message' => 'A database error occurred. Please try again later.'
                    ]));
                }
            }
        }
        return self::$instance;
    }

    // Prevent direct instantiation / cloning
    private function __construct() {}
    private function __clone() {}
}

/**
 * Convenience helper – returns the shared PDO instance.
 */
function db(): PDO {
    return Database::getConnection();
}
